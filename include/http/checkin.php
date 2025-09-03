<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");

$uid = $_SESSION['uid'];
$amount = 10;
$note = "签到奖励";
if ($_SESSION['checkin'] == 1) {
	exit("E14");
}

$TM = new transferModel();

if ($TM->check_in($uid)) {
	$TM->issue_to($uid, $amount, $note);
	$_SESSION['checkin'] = 1;

	exit("success");
} else {
	exit("E2");
}
