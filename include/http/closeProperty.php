<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
$TM = new transferModel();

$openid = $_POST['openid'];

if ($TM->trade_close_by_openid($openid)) {
    exit("success");
} else {
    exit("E2");
};
