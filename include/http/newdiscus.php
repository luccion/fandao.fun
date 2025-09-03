<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
$lastSendTime = empty($_SESSION['cd']) ? 0 : $_SESSION['cd'];
time() - $lastSendTime < CD && exit("E6"); //发帖太快了
empty($_POST['cat']) && exit("E7"); //showMsg('请从正常页面发起讨论！', true, './');
(int)$_POST['cat'] < 1 && exit("E7"); //showMsg('参数错误！', true, './');
empty($_POST['content']) && exit("E4"); //showMsg('请输入正文!');

$data = array();
$data['title'] = empty($_POST['title']) ? '无标题' : $_POST['title'];
$data['content'] = $_POST['content'];
$data['cat'] = (int)$_POST['cat'];
$data['uid'] = (int)$_SESSION['uid'];
$data['name'] = $_SESSION['avatar'];
$data['type'] = $_SESSION['type'];
$msg = new msgModel();
if ($msg->addThread($data)) {
	$_SESSION['cd'] = time();
	exit("success");
} else {
	exit("E2");
}
