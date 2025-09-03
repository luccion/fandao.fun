<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");

$content = $_POST['content'];
$num = str_split($content, 4);;
$note = "BUYTICKET";

$LM = new lotteryModel();
$payment = $LM->lottery_charge_from($_SESSION['uid'], 3, $note);

if (!$payment['status']) {
    exit("E3"); //余额不足
} else {
    $LM->save_ticket($_SESSION['uid'], $num);
    exit("success");
}
