<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
$uid = $_SESSION['uid'];
$toWallet = $_POST['toWallet'];
$openid = $_POST['openid'];
$amount = $_POST['amount'];
$note = $_POST['note'];
if ($amount <= 0) {
    exit("E2");
}

$TM = new transferModel();
$toUid = $TM->wid2uid($toWallet);
if (!isset($toUid)) {
    exit("E5");
}
$gid = $TM->openid2gid($openid);

$result = $TM->transfer($uid, $toUid, $gid, $amount, $note);
$res = $result['payer'] . "";
if ($result['status']) {
    exit($res);
} else {
    exit("E3");
}
