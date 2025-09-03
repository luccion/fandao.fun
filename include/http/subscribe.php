<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || showMsg('请先登陆!', true, '../../index.php');
empty($_POST['tid']) && showMsg('参数错误', true, '../../');
$tid = (int)$_POST['tid'];
$ss = new subscriptionModel();
if ($ss->isSubscribed($tid)) {
	if ($ss->del($tid)) {
	}
} else {
	if ($ss->add($tid)) {
	}
}
