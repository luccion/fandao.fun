<?php
define('ACC', true);
require('init.php');

//实例化政务大厅
$msg = new proposalModel();

//总页数
$all = ceil($msg->countProposals());

//获取10个决议
$proposal = $msg->getTopProposals();

require(ROOT . "view/hall.php");
