<?php
define('ACC', true);
require('../../init.php');

$uid = $_POST['uid'];
$ban = $_POST['ban'];
$days = 7;
if ($ban) {
    userModel::banUser($uid, $days);
    exit();
} else {
    userModel::unbanUser($uid);
    exit();
}
