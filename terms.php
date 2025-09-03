<?php

define('ACC', true);
require('init.php');
//获取全部分类
$allCats = catModel::getCatTree();

//获取当前分类
$curr_cat_id = $threads[0]['cat'];
$curr_cat = catModel::getInfo($curr_cat_id, false);

require(ROOT . "view/terms.php");
