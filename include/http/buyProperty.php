<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
$TM = new transferModel();

$tdid = $_POST['tdid'];
$amount = $_POST['amount']; //购买的资产数量
$trade = $TM->trade_get_one($tdid);

$from = $trade['uid'];
$to = $_SESSION['uid'];
$gid = $trade['gid'];
$forGid = $trade['forgid'];
$foramount = $trade['foramount']; //资产单价
$finalPrice = $foramount * $amount;
$note = "TRADENFT";
if ($trade['amount'] < $amount) {
    exit("E14");
}

if ($TM->deal($from, $to, $gid, $amount, $forGid, $finalPrice, $note)) {
    if ($trade['amount'] == $amount) {
        $TM->trade_close($tdid);
    } else {
        $TM->trade_update($tdid, $amount);
    }
    exit("success");
} else {
    exit("E2");
};
