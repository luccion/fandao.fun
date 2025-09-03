<?php
define('ACC', true);
require('init.php');

//获取全部分类
$allUsersData  = summaryModel::getAllUsers();
$countUsers = sizeof($allUsersData);
$countThreads = summaryModel::countThreads();
$countAvatars = summaryModel::countAvatars();
$countTransfer = summaryModel::countTransfer();
$countFunds = summaryModel::countFunds();
$count_nft = transferModel::count_nft();
$countReplies = summaryModel::countReplies();
$countFilesUploaded = summaryModel::countFilesUploaded();
$dataCount = $_GET['days'] ?: 7;

require(ROOT . "view/summary.php");
