<?php
define('ACC', true);
require('../../init.php');

$TM = new transferModel();
//获取一次score
$uid = $_SESSION['uid'];
$data = array();
$oldscore = userModel::get_wiki_score($_SESSION['wiki_user_name']);
$diff = userModel::update_wiki_score_and_get_diff($oldscore, $_SESSION['wiki_user_name']);

$data['uct'] = intval(10 * $diff);
$data['oldscore'] = round($oldscore, 3);
$data['diff'] = round($diff, 3);

if ($data['uct'] > 0) {
    $trans = $TM->transfer(1, $uid, 123, $data['uct'], "WIKISCORE");
    if ($trans['status']) {
        $data['msg'] = "success";
    };
} else if ($data['uct'] < 0) {
    if ($TM->transfer_forced($uid, 1, 123, $data['uct'], "FORFEIT")) {
        $data['msg'] = "forfeit";
    }
} else {
    $data['msg'] = "none";
}

echo json_encode($data);
