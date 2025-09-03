<?php
define('ACC', true);
date_default_timezone_set('PRC');
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

$TM = new transferModel();
$TM->trade_purge();
