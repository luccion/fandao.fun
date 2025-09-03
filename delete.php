<?php
define('ACC', true);
require('init.php');

userModel::isLogin() || exit("E1");
$_SESSION['type'] > 0 || exit("E9");
empty($_GET['tid']) && exit("E4");
$tid = (int)$_GET['tid'];
$f = empty($_GET['f']) ? '' : (int)$_GET['f'];
$f = $f === 0 ? '' : $f;
$msg = new msgModel();
$message = '';
if ($f === '') {
	$re = $msg->delThread($tid);
	if ($re[0]) {
		$message = '已删除tid为' . $tid . '的讨论主题。<br />';
	} else {
		$message = '删除tid为' . $tid . '的讨论主题失败！<br />';
	}
	if ($re[1]) {
		$message .= '已删除tid为' . $tid . '的讨论主题下的所有回复。';
	} else {
		$message .= '删除tid为' . $tid . '的讨论主题下的所有回复失败！';
	}
	if (isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'view.php') === false) {
		showMsg($message, true);
	} else {
		showMsg($message, true, './');
	}
} else {
	$re = $msg->delReply($tid, $f);
	if ($re) {
		$message .= '已删除tid为' . $tid . '的讨论主题下第' . $f . '楼回复。';
	} else {
		$message .= '删除tid为' . $tid . '的讨论主题下第' . $f . '楼回复失败！';
	}
}
echo $message;
