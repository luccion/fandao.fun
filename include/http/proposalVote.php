<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
$pid = (int)$_POST['pid'];
$position = $_POST['position'];
$pm = new proposalModel();

function pay()
{
	$TM = new transferModel();
	$payment = $TM->charge_from($_SESSION['uid'], 1, "投票");
	if (!$payment['status']) {
		exit("E3"); //余额不足
	}
}

if ($pm->isVotedPro($pid) && $position == 1) {
	$pm->delProposalVote($pid);
	exit("Pro deleted");
} else if ($pm->isVotedCon($pid) && $position == 0) {
	$pm->delProposalVote($pid);
	exit("Con deleted");
} else if (!$pm->isVotedPro($pid) && $position == 0) {
	pay();
	$pm->delProposalVote($pid);
	$pm->addProposalVote($pid, $position);
	exit("Con Voted");
} else if (!$pm->isVotedCon($pid) && $position == 1) {
	pay();
	$pm->delProposalVote($pid);
	$pm->addProposalVote($pid, $position);
	exit("Pro Voted");
} else if ($pm->isVotedPro($pid) && $position == 0) {
	pay();
	$pm->delProposalVote($pid);
	$pm->addProposalVote($pid, $position);
	exit("Pro deleted and Con voted");
} else if ($pm->isVotedCon($pid) && $position == 1) {
	pay();
	$pm->delProposalVote($pid);
	$pm->addProposalVote($pid, $position);
	exit("Con deleted and Pro voted");
}
