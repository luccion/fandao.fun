<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1"); // showMsg('请先登陆!', true, './index.php');
$lastSendTime = empty($_SESSION['cd']) ? 0 : $_SESSION['cd'];
time() - $lastSendTime < CD && exit("E6"); //showMsg('发言CD中…先施法吧！');
empty($_POST['tid']) && exit("E7"); //showMsg('请从正常页面发起回复！', true, './');
empty($_POST['content']) && exit("E4"); //showMsg('请输入正文!');
$data = array();
$msg = new msgModel();
$data['content'] = $_POST['content'];
$data['tid'] = (int)$_POST['tid'];
$data['uid'] = (int)$_SESSION['uid'];
$data['name'] = $_SESSION['avatar'];
$data['type'] = $_SESSION['type'];
$data['floor'] = $msg->getNextFloor($data['tid']);
if ($msg->addreply($data)) {
	$_SESSION['cd'] = time();
	exit("success"); //showMsg('发出回复成功！', true, './view.php?id=' . $data['tid']);
} else {
	exit("E2"); //showMsg('发起讨论失败！');
}
