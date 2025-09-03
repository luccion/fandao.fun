<?php
define('ACC', true);
require('init.php');

userModel::logout();
header("Location: index.php");
exit;
