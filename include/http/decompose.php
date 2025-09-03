<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
$TM = new transferModel();

$openid = $_POST['openid'];
$uid = $_SESSION['uid'];
$gid = $TM->openid2gid($openid);
$amount = 1;
$estimation = $TM->decompose($uid, $gid, $amount);
if ($estimation) {
    exit($estimation);
} else {
    exit("E2");
};
