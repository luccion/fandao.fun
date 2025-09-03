<?php

define('ACC', true);
require('init.php');
userModel::isLogin() || showMsg('请先登陆!', true, '../../index.php');
$asc = $_GET['asc'];

$LM = new lotteryModel();
$myTickets = $LM->get_tickets($_SESSION['uid']);

$history = $asc != 1 ? array_reverse($LM->get_history()) : $LM->get_history();

$pool = $LM->calculate_amount("lottery");
$account = $LM->calculate_amount($LM->get_wallet($_SESSION['uid']));
$ticktsCount = $LM->count_tickets();

$historyEmoji = $LM->get_history_emoji_count();
$labels = array_keys($historyEmoji);
$data = array_values($historyEmoji);

if ($_COOKIE["THEME"] == 'DARKMODE') {
    $color = "#555";
} else {
    $color = "#ddd";
}

require(ROOT . "view/lottery.php");
