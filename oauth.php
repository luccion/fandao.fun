<?php

define('ACC', true);
require('init.php');
require('include/model/afdianModel.class.php');

$afd_oauth_code = $_GET['code'];
$afd_oauth_state = $_GET['state'];
$uid = $_SESSION['uid'];
$url = "https://fandao.fun/me.php?cat=6";

// 从配置/环境读取
$conf = conf::getInstance();
if (!defined('USERID')) define('USERID', $conf->aifadian_foundation_id);
if (!defined('TOKEN')) define('TOKEN', $conf->aifadian_foundation_token);
if (!defined('CLIENT_SECRET')) define('CLIENT_SECRET', getenv('OAUTH_CLIENT_SECRET') ?: '');
if (!defined('CLIENT_ID')) define('CLIENT_ID', getenv('OAUTH_CLIENT_ID') ?: 'fandao');
if (!defined('TEST_USERID')) define('TEST_USERID', getenv('TEST_USERID') ?: '');

if ($afd_oauth_state == 111) {
    $post_data = array(
        'grant_type' => "authorization_code",
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'code' => $afd_oauth_code,
        'redirect_uri' => "https://fandao.fun/oauth.php"
    );
    $result = userModel::send_post('https://afdian.com/api/oauth2/access_token', $post_data);
    $data = json_decode($result);
    $aifadianID = $data->data->user_id;
    if ($aifadianID) {
        if (userModel::has_aifadian_bound($aifadianID)) {
            log::write("❌has been bound user_id:" . $aifadianID);
            header("Location: $url");
            exit();
        }
        $afdian = new Afdian(USERID, TOKEN);
        $allOrders = $afdian->getAllOrders();
        $rmb = 0;
        $uct_to_issue = 0;
        foreach ($allOrders['data']['list'] as $o) {
            if ($o['user_id'] == $aifadianID) {
                $plan = $o['show_amount'] / $o['month'];
                $rmb += $o['show_amount'];
                if ($plan == 1299) {
                    $uct_to_issue += 14000 * $o['month'];
                } else {
                    $uct_to_issue += $plan * 10 * $o['month'];
                }
            }
        }
        if ($rmb == 0) {
            userModel::update_aifadian_id_and_rmb($aifadianID, $rmb, $uid);
            log::write("💔registered but no income of user_id:" . $aifadianID);
            $_SESSION['aifadian_id'] = $aifadianID;
            $_SESSION['exchanged_rmb'] = $rmb;
            header("Location: $url");
            exit();
        } else {
            $TM = new transferModel();
            $wid = $TM->get_wallet($uid);
            if ($TM->transfer_log("ISSUED", "fandao", 123, $uct_to_issue * 2, "ISSUED", 5)) {
                if ($TM->transfer_log("fandao", $wid, 123, $uct_to_issue, "MINTUCT", 5)) {
                    log::write("✅UCT transfer successed of user_id:" . $aifadianID);
                    userModel::update_aifadian_id_and_rmb($aifadianID, $rmb, $uid);
                    $_SESSION['aifadian_id'] = $aifadianID;
                    $_SESSION['exchanged_rmb'] = $rmb;
                    header("Location: $url");
                    exit();
                }
            }
        }
    } else {
        log::write("❌empty user_id");
        header("Location: $url");
        exit();
    }
} else {
    log::write("❌wrong state number of" . $afd_oauth_state);
    header("Location: $url");
    exit();
}
log::write("❌UCT transfer failed of user_id:" . $aifadianID);
header("Location: $url");
exit();
