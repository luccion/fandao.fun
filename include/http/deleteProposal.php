<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || showMsg('没有登陆！', true, '../../');
$_SESSION['type'] > 0 || showMsg('权限不足！', true, '../../');
empty($_POST['pid']) && showMsg('参数错误！', true, '../../');
$pid = (int)$_POST['pid'];
$msg = new proposalModel();
$msg->delProposal($pid);

exit("deleted");
