<?php
define('ACC', true);
require('../../init.php');
require('../model/Multiavatar.php');

if (empty($_POST['username']) || empty($_POST['password'])) {
	exit('ACC Denied');
}
if (userModel::isLogin()) {
	echo "E0";
	return; //请先退出登陆！
}
$email = $_POST['email'];
$avatar_cn = $_POST['avatar_cn'];
$avatar_en = $_POST['avatar_en'];
$email_re = '/^[\\w-]+(\\.\\w+)*@[\\w-]+((\\.\\w{2,3}){1,3})$/';
$UM = new userModel();
$username = $_POST['username'];
$md5 = $_POST['md5'];

$avatar = new Multiavatar();
$svg = $avatar($md5, null, null);

if ($UM->exists_uname($username)) {
	echo "E10";
	return;
}
if ($UM->exists_email($email)) {
	echo "E10";
	return;
}
$password = $_POST['password'];
if (mb_strlen($password) < 6 || mb_strlen($password) > 16) {
	echo "E11";
	return; //请输入6-16位密码
}

/** 
 *	1. 生成化身
 *	2. 注册用户
 *	3. 注册化身NFT
 *	4. 注册化身metadata->全局ID
 */

$TM = new transferModel();
$db = model::db();
$aid = $UM->addAvatar($avatar_cn, $avatar_en, $svg);
$uid = $UM->add($username, $password, $email, 0, $aid);
$gid = $TM->reg_nft($avatar_en, $uid[1], "avatar", "reg_nft", 4);
$UM->regAvatarGID($aid, $gid);

/* 内测奖励 */

$TM->issue_to($_SESSION['uid'], 70, "内测奖励");

$token = $uid[0];
require '../../mail.php';

echo "success";
