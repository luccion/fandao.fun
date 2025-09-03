<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
empty($_POST['title']) && exit("E4"); //showMsg('请输入title!');
empty($_POST['content']) && exit("E4"); //showMsg('请输入正文!');

$data = array();
$data['uid'] = (int)$_SESSION['uid'];
$data['title'] = $_POST['title'];
$data['content'] = $_POST['content'];
$msg = new proposalModel();

$item = "创建决议";
$TM = new transferModel();
$payment = $TM->charge_from($data['uid'], 10, $item);
if (!$payment['status']) {
	exit("E3"); //余额不足
}
if ($msg->addProposal($data)) {
	exit("success");
} else {
	exit("E2");
}
