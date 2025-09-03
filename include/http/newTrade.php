<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");

$price = $_POST['price'];
$openid = $_POST['openid'];
$uid = $_SESSION['uid'];
$amount = $_POST['amount'];

$TM = new transferModel();
$gid = transferModel::openid2gid($openid);

if ($TM->get_gid_amount($uid, $gid, $amount)) {
    if ($TM->trade($uid, $gid, $amount, $price)) {
        exit("success");
    } else {
        exit("E2");
    };
} else {
    exit("E14");
}
