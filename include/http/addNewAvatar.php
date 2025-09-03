<?php
define('ACC', true);
require('../../init.php');
require('../model/Multiavatar.php');

if (!userModel::isLogin()) {
	exit('E1');
}
$avatar_cn = $_POST['avatar_cn'];
$avatar_en = $_POST['avatar_en'];
$md5 = $_POST['md5'];

$avatar = new Multiavatar();
$svg = $avatar($md5, null, null);

$UM = new userModel();
$TM = new transferModel();
$payment = $TM->charge_from($_SESSION['uid'], 50, "新的化身");
if (!$payment['status']) {
	exit("E3"); //余额不足
}
$aid = $UM->addAvatar($avatar_cn, $avatar_en, $svg);
$gid = $TM->reg_nft($avatar_en, $_SESSION['uid'], "avatar", "reg_nft", 4);
$UM->regAvatarGID($aid, $gid);

exit("success");
