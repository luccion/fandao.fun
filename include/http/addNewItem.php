<?php
define('ACC', true);
require('../../init.php');

if (!userModel::isLogin()) {
	exit('E1');
}
$defaultSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.07 45.19"><defs><style>.f1313dd7-94d7-4139-8636-b4648e12ebbd{fill:#131e3d}.aeddf0b1-9561-4a86-a011-b0c0c8898e9a{fill:#2a489c}.aaf9e6b0-0c36-4c1a-863f-5d70d039e184{fill:#222185}.b5346ebe-1e4b-4731-94fd-2f4ccf77a700{fill:#bd6b56}.b99db2df-18df-410a-be63-c28309cf7282{fill:#f5af79}</style></defs><path class="f1313dd7-94d7-4139-8636-b4648e12ebbd" d="M4.2 15.23h4.55l-.41 9.43H3.67l.53-9.43zM10.02 15.23h9.14l-.05 4.12h-2.32l-.1 5.31H12l.17-5.31H9.86l.16-4.12zM20.43 15.23h8.54l.03 2.32h-4.02l.01 1.15h2.81l.02 2.37H25v1.18h4.08l.04 2.41h-8.78l.09-9.43zM42.88 15.23l.53 9.43h-4.68l-.2-4.78-1.69 1.83-1.81-1.83.16 4.78H30.5l-.18-9.43h4.42l1.93 1.92 1.8-1.92h4.41z" /><path class="aeddf0b1-9561-4a86-a011-b0c0c8898e9a" d="M8.59 24.91H3.92l.52-9.43H9Zm10.77-5.31H17l-.1 5.31h-4.65l.17-5.31H10.1l.16-4.12h9.15Zm9.89-1.8h-4V19H28v2.37h-2.76v1.13h4.07v2.41h-8.72l.08-9.43h8.54Zm14.41 7.11H39l-.21-4.78-1.7 1.87-1.81-1.83.15 4.78h-4.68l-.18-9.43H35l1.93 1.92 1.79-1.92h4.42ZM47.13 20.65a.09.09 0 0 1 0 .05v-.05Z" transform="translate(-.25 -.25)" /><path class="aeddf0b1-9561-4a86-a011-b0c0c8898e9a" d="M.46 20.13C1.21 8.87 11.64.25 23.78.25S46.36 8.87 47.1 20.13V20.72C47 32.09 36.86 41.55 23.78 41.55S.68 32.15.44 20.84c0-.26.01-.49.02-.71Z" transform="translate(-.25 -.25)" /><path d="M.44 20.84c.24 11.31 10.32 20.71 23.34 20.71S47 32.09 47.13 20.73v.67c0 11.5-10.15 21.15-23.36 21.15S.41 32.9.43 21.4c0-.19 0-.4.01-.56Z" transform="translate(-.25 -.25)" style="fill:#6173b7" /><path d="M.43 21.4c0 11.5 10.16 21.15 23.35 21.15S47.16 32.9 47.14 21.4v-.67l.13 2c.81 12.22-9.68 22.76-23.49 22.76S-.52 34.9.29 22.68l.17-2.55v.68c-.03.19-.03.4-.03.59Z" transform="translate(-.25 -.25)" style="fill:#1d267b" /><path class="aaf9e6b0-0c36-4c1a-863f-5d70d039e184" d="M4.2 15.23h4.55l-.41 9.43H3.67l.53-9.43zM10.02 15.23h9.14l-.05 4.12h-2.32l-.1 5.31H12l.17-5.31H9.86l.16-4.12zM20.43 15.23h8.54l.03 2.32h-4.02l.01 1.15h2.81l.02 2.37H25v1.18h4.08l.04 2.41h-8.78l.09-9.43zM42.88 15.23l.53 9.43h-4.68l-.2-4.78-1.69 1.83-1.81-1.83.16 4.78H30.5l-.18-9.43h4.42l1.93 1.92 1.8-1.92h4.41z" /><path class="b5346ebe-1e4b-4731-94fd-2f4ccf77a700" d="m4.44 16.31-.52 9.43h4.67L9 16.31ZM10.26 16.31l-.16 4.12h2.32l-.17 5.31h4.69l.1-5.31h2.32l.05-4.12ZM29.25 18.63v-2.32h-8.58l-.08 9.43h8.77v-2.41h-4.11v-1.17h2.83v-2.37h-2.85v-1.16ZM38.71 16.31l-1.79 1.93L35 16.31h-4.43l.18 9.43h4.68L35.28 21l1.81 1.83L38.77 21l.23 4.74h4.68l-.53-9.43Z" transform="translate(-.25 -.25)" /><path class="b99db2df-18df-410a-be63-c28309cf7282" d="m4.44 15.48-.52 9.43h4.67L9 15.48ZM10.26 15.48l-.16 4.12h2.32l-.17 5.31h4.69L17 19.6h2.32l.05-4.12ZM29.25 17.8v-2.32h-8.58l-.08 9.43h8.77V22.5h-4.11v-1.18h2.83L28 19h-2.77v-1.2ZM38.71 15.48l-1.79 1.92L35 15.48h-4.43l.18 9.43h4.68l-.15-4.78L37.09 22l1.68-1.83.23 4.74h4.68l-.53-9.43Z" transform="translate(-.25 -.25)" /></svg>';
$type = 5;

$arr = array();
$arr['title'] = $_POST['title'];
$arr['subtitle'] = $_POST['subtitle'] ?: $_POST['title'];
$arr['content'] = $_POST['content'] ?: $_POST['title'];
$arr['svg'] = $_POST['svg'] ?: $defaultSVG;
$arr['rarity'] = $_POST['rarity'] ?: 1;
$arr['price'] = $_POST['price'] ?: 1;

$amount = $_POST['amount'] ?: 1;

$TM = new transferModel();
$item_id = $TM->add_new_item($arr);

/* 判断是否是NFT，如果是NFT，则通过reg_nft广播；如果是FT，则直接加入global，并genesis给铸造者。当前版本功能默认item */

$gid = $TM->reg_nft($arr['title'], $_SESSION['uid'], "ISSUED", "GENESIS", $type, time(), $amount);
if ($TM->reg_item_gid($item_id, $gid)) {
	exit("success");
} else {
	exit("E2");
}
