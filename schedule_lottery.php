<?php
define('ACC', true);
date_default_timezone_set('PRC');
//定义常量
define('ROOT', str_replace('\\', '/', dirname(__FILE__)) . '/');
define('DEBUG', false);
define('SAVELOG', false);

//加载函数库
require(ROOT . '/include/lib/lib.base.php');

//类自动加载
function __autoload($class)
{
    if (strtolower(substr($class, -5)) == 'model') {
        require(ROOT . 'include/model/' . $class . '.class.php');
    } else {
        require(ROOT . 'include/lib/' . $class . '.class.php');
    }
}

//设置报错级别
DEBUG ? error_reporting(E_ALL) : error_reporting(0);

/**
 * 定时任务，彩票生成
 * 每日22:00启动，使用彩票check函数进行彩票生成、检查、分发、记录 
 */


$LM = new lotteryModel();
$LM->check_lottery();
