<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || showMsg('请先登陆!', true, '../../index.php');
$uid = $_SESSION['uid'];
$item = $_POST['item'];
$TM = new transferModel();
switch ($item) {
    case "makeProposal":
        $TM->charge_from($uid, 10, $item);
        break;
    case "vote":
        $TM->charge_from($uid, 1, $item);
        break;
    case "changeName":
        $TM->charge_from($uid, 100, $item);
        break;
}
