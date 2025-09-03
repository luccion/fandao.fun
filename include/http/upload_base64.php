<?php
$base_img = $_POST['imgBase64'];
$base_img = str_replace('data:image/png;base64,', '', $base_img);
$result["file_name"] = time() . rand(100, 999) . ".png";
$path = "../../upload/" . $result["file_name"];
file_put_contents($path, base64_decode($base_img));

$sourceImg = imagecreatefrompng($path);
$width = imagesx($sourceImg);
$newimg = imagecreatetruecolor(150, 150);
imagecopyresampled($newimg, $sourceImg, 0, 0, 0, 0, 150, 150, 300, 300);
$path = "../../upload/thumbnail/" . $result["file_name"];
ImageJpeg($newimg, $path);
ImageDestroy($sourceImg);

$result["status"] = "success";
echo json_encode($result);
