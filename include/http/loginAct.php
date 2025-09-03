<?php
define('ACC', true);
require('../../init.php');

if (empty($_POST['username']) || empty($_POST['password'])) {
	exit('ACC Denied');
}
if (userModel::isLogin()) {
	echo "E0"; //已经处于登陆状态
}

$user = $_POST['username'];
$pass = $_POST['password'];

$UM = new userModel();
if ($UM->login($user, $pass)) {
	echo "success"; //成功	
} else {
	echo "E2"; //用户名或密码错误
}