<?php
define('ACC', true);
require('init.php');
$json = file_get_contents('php://input');
$data = json_decode($json);
/* 获取用户ID和金额 */

$rmb = $data->data->order->show_amount;
$month = $data->data->order->month;
$total_amount = $data->data->order->total_amount;
$aifadianID = $data->data->order->user_id;
$plan = $total_amount / $month;
$uid = userModel::aifadianid2uid($aifadianID);
if ($uid) {
    $uct_to_issue = 0;
    if ($plan == 1299) {
        $uct_to_issue += 14000 * $month;
    } else {
        $uct_to_issue += $plan * $month * 10;
    }

    $TM = new transferModel();
    $wid = $TM->get_wallet($uid);
    if ($TM->transfer_log("ISSUED", "fandao", 123, $uct_to_issue * 2, "ISSUED", 5)) {
        if ($TM->transfer_log("fandao", $wid, 123, $uct_to_issue, "MINTUCT", 5)) {
            if (userModel::update_aifadian_rmb($aifadianID, $rmb)) {
                log::write("🌐UCT transfer(webhook) successed of user_id:" . $aifadianID);
                $rt = array();
                $rt['ec'] = "200";
                echo json_encode($rt);
                exit();
            }
        }
    }
} else {
    log::write("❌No such user in fandao of user_id:" . $aifadianID);
}
log::write("❌UCT transfer failed of user_id:" . $aifadianID);
$rt = array();
$rt['ec'] = "200";
echo json_encode($rt);
exit();
