<?php
define('ACC', true);
require('../../init.php');

define('PLANET_CARD', 168);
define('WPA', 145);
define('WPA_WALLET', 'PLANET');
define('NOTE', '兑换星球');
$request = (int)$_GET['request'];
$user = urldecode($_GET['user']);
$TM = new transferModel();
$uid = $TM->if_bind_wiki($user);

if (!$user) {
    exit('E2');
}

switch ($request) {
    case 0:
        /* 检查账户是否绑定，并返回卡片数量 */
        $amount = $TM->get_gid_amount($uid, PLANET_CARD);
        exit($amount);
        break;
    case 1:
        /* 查询用户名下星球 */
        $planets = $TM->get_all_of_type($uid, 8);
        $res = array();
        if ($planets) {
            foreach ($planets as $p) {
                array_push($res, ['wvplid' => $p['wvplid'], 'openid' => $TM->gid2openid($p['gid'])]);
            }
            exit(json_encode($res));
        } else {
            exit('E14');
        }
        break;
    case 2:
        /* 确认购买星球，传送 */
        $wvplid = $_GET['plid'];
        $gid_planet = $TM->get_planet_GID($wvplid);
        if (!$gid_planet) {
            exit('E12');
        }
        if ($TM->deal(WPA, $uid, $gid_planet, 1, PLANET_CARD, 1, NOTE)) {
            exit('success');
        } else {
            exit('E14');
        }
        break;
    case 3:
        $allPlanets = $TM->get_all_planets();
        $res = array();
        foreach ($allPlanets as $a) {
            if ($TM->get_last_owner($a['gid']) != WPA_WALLET) {
                array_push($res, $a['wvplid']);
            };
        }
        exit(json_encode($res));
        break;
    default:
        exit('E4');
}
