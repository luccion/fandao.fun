<?php
define('ACC', true);
require('../../init.php');

userModel::isLogin() || exit("E1");
$lastSendTime = empty($_SESSION['cd']) ? 0 : $_SESSION['cd'];
time() - $lastSendTime < CD && exit("E6"); //发帖太快了
empty($_POST['cat']) && exit("E7"); //showMsg('请从正常页面发起讨论！', true, './');
(int)$_POST['cat'] < 1 && exit("E7"); //showMsg('参数错误！', true, './');
empty($_POST['content']) && exit("E4"); //showMsg('请输入正文!');

$data = array();
$data['title'] = empty($_POST['title']) ? '无标题' : $_POST['title'];
$data['content'] = $_POST['content'];
$data['cat'] = (int)$_POST['cat'];
$data['uid'] = (int)$_SESSION['uid'];
$data['name'] = $_SESSION['avatar'];
$data['type'] = $_SESSION['type'];
$abstract = "article by " . $_SESSION['username'] . "," . time();

$msg = new msgModel();
$TM = new transferModel();

$tid = $msg->addThread($data);
$gid = $TM->reg_nft($abstract, $_SESSION['uid'], "ip", "REGARTICLE", 3);
$msg->regThreadGID($tid, $gid);

if ($tid) {
    $_SESSION['cd'] = time();
    echo $tid;
    exit();
} else {
    exit("E2");
}
