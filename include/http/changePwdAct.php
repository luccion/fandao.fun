<?php
define('ACC', true);
require('../../init.php');
userModel::isLogin() || exit("E1");
if (empty($_POST['oldpass']) || empty($_POST['newpass'])) {
	exit('密码不可为空');
}

$data = array();
if (md5($_POST['oldpass']) == $_SESSION['password']) {
	if (mb_strlen($_POST['newpass'], 'UTF8') < 5 || mb_strlen($_POST['newpass'], 'UTF8') > 16) {
		exit("E2");
	} else {
		$UM = new userModel();
		$data['password'] = md5($_POST['newpass']);
		if ($UM->update($_SESSION['uid'], $data)) {
			$_SESSION['password'] = md5($_POST['newpass']);
			exit('success');
		}
	}
} else {
	exit('E8');
}
