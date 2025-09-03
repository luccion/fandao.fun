<?php
define('ACC', true);
require('../../init.php');

$avatar_cn = $_POST['avatar'];
$email = $_POST['email'];
$token = $_POST['token'];
$username = $_POST['username'];

require '../../mail.php';
echo $timeLeft;
