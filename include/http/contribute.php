<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
$amount = (int)$_POST['amount'];
$tid = $_POST['tid'];
$reciever = $_POST['reciever'];
$threadTitle = $_POST['threadTitle'];
$note = "contribution@" . $_POST['recieverAvatar'] . "@|@" . $threadTitle . "";

$TM = new transferModel();
$MM = new msgModel();
$payment = $TM->transfer($_SESSION['uid'], $reciever, 1, $amount, $note);
/* print_r($payment); */
if (!$payment['status']) {

	exit("E3"); //余额不足
} else {
	$MM->contribution($tid, $amount);
	exit("success");
}
