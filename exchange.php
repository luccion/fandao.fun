<?
define('ACC', true);
require('init.php');
userModel::isLogin() || showMsg('请先登陆!', true, '../../index.php');
require(ROOT . "view/exchange.php");