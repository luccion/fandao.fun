<?php
/*
file init.php
框架初始化
 */
header('Access-Control-Allow-Origin:https://whiteverse.com');
header('Content-type: text/html; charset=utf-8');

defined('ACC') || exit('ACC Denied');

session_start();
date_default_timezone_set('PRC');

spl_autoload_register(function ($class) {
	if (strtolower(substr($class, -5)) == 'model') {
		require(ROOT . 'include/model/' . $class . '.class.php');
	} else {
		require(ROOT . 'include/lib/' . $class . '.class.php');
	}
});

//定义常量
define('ROOT', str_replace('\\', '/', dirname(__FILE__)) . '/');
define('DEBUG', false);
define('SAVELOG', false);

//读取配置
$conf = conf::getInstance();
$report_tid = $conf->report_tid;
$privacy_tid = $conf->privacy_tid;
$whatsthis_tid = $conf->whatsthis_tid;
define('SITEVERSION', $conf->version);
define('SITENAME', $conf->site_name);
define('CONTENT_LENGTH_LIMIT', $conf->content_length_limit);
define('CD', $conf->post_cd);
define("USERID", $conf->aifadian_foundation_id);
define("TOKEN", $conf->aifadian_foundation_token);

//获取模板路径
$template_dir = isset($_SESSION['template']) ? $_SESSION['template'] : 'default';

//加载函数库
require(ROOT . '/include/lib/lib.base.php');

//设置报错级别
DEBUG ? error_reporting(E_ALL) : error_reporting(0);

//递归过滤参数

$_GET = _addslashes($_GET);
$_POST = _addslashes($_POST);
$_COOKIE = _addslashes($_COOKIE);

$UM = new userModel();
$UM->log2db();
$TM = new transferModel();


function fontawesomeNumber($number)
{
	$n = str_split(strval($number));
	$fNumber = "";
	for ($i = 0; $i < sizeof($n); $i++) {
		if ($n[$i] == "-") {
			$fNumber = $fNumber . '<i class="fa-solid fa-minus"></i>';
		} else {
			$fNumber = $fNumber . '<i class="fa-solid fa-' . $n[$i] . '"></i>';
		}
	}

	return $fNumber;
}
