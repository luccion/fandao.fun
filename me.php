<?php

define('ACC', true);
require('init.php');
userModel::isLogin() || showMsg('请先登陆!', true, './index.php');

$TM = new transferModel();
$money = $TM->calculate_amount($_SESSION['wallet']);
$property = $TM->get_all_of($_SESSION['uid']);

$cat = empty($_GET['cat']) ? 1 : (int)$_GET['cat'];

switch ($cat) {
		//我发起的讨论 栏目
	case 1:
		$msg = new msgModel;
		//总页数
		$all = ceil($msg->countUserThreads($_SESSION['uid']) / 10);
		//获取当前页数
		$curr_page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
		$curr_page > $all && $curr_page = $all;
		$curr_page < 1 && $curr_page = 1;
		//按页获取用户发送主题
		$threads = $msg->getUserThreads($_SESSION['uid'], ($curr_page - 1) * 10);
		//取得每个主题10个以内回复
		/* 		$replies = array();
		foreach ($threads as $k=>$v) {
			$replies[$k] = $msg->getTopReplies($v['tid'], 0);
		} */
		break;
		//我的历史回复 栏目
	case 2:
		$msg = new msgModel;
		//总页数
		$all = ceil($msg->countUserReplies($_SESSION['uid']) / 10);
		//获取当前页数
		$curr_page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
		$curr_page > $all && $curr_page = $all;
		$curr_page < 1 && $curr_page = 1;
		//按页获取用户回复所在主题(10个内)
		$threads = $msg->getUserReplies($_SESSION['uid'], ($curr_page - 1) * 10);

		break;
		//我的订阅 栏目
	case 3:
		$ss = new subscriptionModel();
		//总页数
		$all = ceil($ss->count() / 10);
		//获取当前页数
		$curr_page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
		$curr_page > $all && $curr_page = $all;
		$curr_page < 1 && $curr_page = 1;
		//按页获取用户订阅主题
		$threads = $ss->get(($curr_page - 1) * 10);
		break;
}
//分页
if ($cat == 1 || $cat == 2 || $cat == 3) {
	//$i开始显示页数，$show结束显示页数
	if ($curr_page <= 6) {
		$i = 1;
		$show = $all < 11 ? $all : 11;
	} elseif ($curr_page > 6 && $curr_page <= $all - 10) {
		$i = $curr_page - 10;
		$show = $curr_page + 10;
	} else {
		$i = $all - 10;
		$show = $all;
	}
}

require(ROOT . "view/me.php");
