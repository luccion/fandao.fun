<?php
define('ACC', true);
require('../../init.php');
userModel::isLogin() || exit("E1");

$TM = new transferModel();

$openid = $_POST['openid'];
$gid = $TM->openid2gid($openid);
$history = $TM->get_price_history($gid);
echo json_encode($history);
