<?php
define('ACC', true);
require('init.php');

$msg = new msgModel();
$allCats = catModel::getCatTree();
$curr_cat_id = empty($_GET['cat']) ? 1 : (int)$_GET['cat'];
$curr_cat_id < 1 && $curr_cat_id = 1;
$curr_cat = catModel::getInfo($curr_cat_id);

$title = $_GET['title'];
$article = $_GET['article'];

//view
require(ROOT . "view/article.php");
