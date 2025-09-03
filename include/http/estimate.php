<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
$TM = new transferModel();

$openid = $_POST['openid'];
$gid = transferModel::openid2gid($openid);
$estimation = $TM->estimation($gid);

echo ($estimation);
