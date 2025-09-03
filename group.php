<?php
define('ACC', true);
require('init.php');

$uid = $_POST['uid'];
$group = $_POST['group'];

userModel::setGroup($uid, $group);
exit();
