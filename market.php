<?php
define('ACC', true);
require('init.php');
userModel::isLogin() || showMsg('请先登陆!', true, '../../index.php');

$TM = new transferModel();
$allTrade = array_reverse($TM->trade_get_all());
$account = $TM->calculate_amount($TM->get_wallet($_SESSION['uid']));


require(ROOT . "view/market.php");
