<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");

/* get winners info */
$uid = $_SESSION['uid']; //$_SESSION['uid'];
$LM = new lotteryModel();
$winnerInfo = $LM->get_user_prize($uid);
echo json_encode($winnerInfo);
