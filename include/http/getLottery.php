<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");


$LM = new lotteryModel();
$winnerInfo = $LM->get_history();
$lottery = $winnerInfo[array_key_last($winnerInfo)];

echo json_encode($lottery);
