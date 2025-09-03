<?php

define('ACC', true);
require('../../init.php');
userModel::isLogin() || exit("E1");

$uid = (int)$_SESSION['uid'];
$aid = $_POST['aid'];

if (userModel::switchToAid($aid, $uid)) {
    exit("success");
} else {
    exit("E2");
}
