<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
empty($_POST['tid']) && exit("E4");
$tid = (int)$_POST['tid'];
$ss = new threadlikesModel();
if ($ss->isLiked($tid)) {
	if ($ss->del($tid)) {
	}
} else {
	if ($ss->add($tid)) {
	}
}
