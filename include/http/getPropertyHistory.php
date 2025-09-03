<?php
define('ACC', true);
require('../../init.php');
userModel::isLogin() || exit("E1");

$TM = new transferModel();

$openid = $_POST['openid'];
$gid = $TM->openid2gid($openid);
$history = $TM->get_transfer_log($gid);
if ($history) {
    echo json_encode($history);
} else {
    exit("E2");
}
