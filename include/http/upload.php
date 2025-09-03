<?php
/**
 * 生成缩略图
 * @param string $imgSrc     图片源路径
 * @param int $thumbWidth   缩略图宽度
 * @param int $thumbHeight  缩略图高度
 * @param string  $thumbSrc    缩略图路径
 * @param bool $isCut     是否剪切图片
 */
function createThumbImg($imgSrc, $thumbWidth, $thumbHeight, $thumbSrc, $isCut = false)
{
	//1.获取图片的类型
	$type = substr(strrchr($imgSrc, "."), 1);
	//2.初始化图象
	if ($type == "jpg" || $type == "jpeg") {
		//创建一块画布，并从JPEG文件或URL地址载入一副图像
		$sourceImg = imagecreatefromjpeg($imgSrc);
	} elseif ($type == "png") {
		//创建一块画布，并从PNG文件或URL地址载入一副图像
		$sourceImg = imagecreatefrompng($imgSrc);
	} elseif ($type == "wbmp") {
		//创建一块画布，并从WBMP文件或URL地址载入一副图像
		$sourceImg = imagecreatefromwbmp($imgSrc);
	} else {
		copy($imgSrc, $thumbSrc);
		return;
	}
	//取得图像宽度
	$width = imagesx($sourceImg);
	//取得图像高度
	$height = imagesy($sourceImg);

	//判定图像是否比预期缩略图小
	if ($width * $height <= $thumbHeight * $thumbWidth) {
		ImageJpeg($sourceImg, $thumbSrc);
		ImageDestroy($sourceImg);
		return;
	}

	//3.生成图象
	//缩略图的图象比例
	$scale = ($thumbWidth) / ($thumbHeight);
	//源图片的图象比例
	$ratio = ($width) / ($height);
	if (($isCut) == 1) {
		//高度优先
		if ($ratio >= $scale) {
			//创建真彩图像资源（imagecreatetruecolor()函数使用GDLibrary创建新的真彩色图像）
			$newimg = imagecreatetruecolor($thumbWidth, $thumbHeight);
			imagecopyresampled($newimg, $sourceImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, (($height) * $scale), $height);
			ImageJpeg($newimg, $thumbSrc);
		}
		//宽度优先
		if ($ratio < $scale) {
			$newimg = imagecreatetruecolor($thumbWidth, $thumbHeight);
			imagecopyresampled($newimg, $sourceImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, (($width) / $scale));
			ImageJpeg($newimg, $thumbSrc);
		}
	} else {
		if ($ratio >= $scale) {
			$newimg = imagecreatetruecolor($thumbWidth, ($thumbWidth) / $ratio);
			imagecopyresampled($newimg, $sourceImg, 0, 0, 0, 0, $thumbWidth, ($thumbWidth) / $ratio, $width, $height);
			ImageJpeg($newimg, $thumbSrc);
		}
		if ($ratio < $scale) {
			$newimg = imagecreatetruecolor(($thumbHeight) * $ratio, $thumbHeight);
			imagecopyresampled($newimg, $sourceImg, 0, 0, 0, 0, ($thumbHeight) * $ratio, $thumbHeight, $width, $height);
			ImageJpeg($newimg, $thumbSrc);
		}
	}
	//销毁图像
	ImageDestroy($sourceImg);
}


$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);     // 获取文件后缀名
$md5file = md5_file($_FILES["file"]["tmp_name"]); //获取文件md5值

$result["md5"] = $md5file;
$result["ext"] = $extension;
if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png")
	)
	&& ($_FILES["file"]["size"] < 5242880)   // 小于 2000 kb
	&& in_array($extension, $allowedExts)
) {
	if ($_FILES["file"]["error"] > 0) {
		$result["status"] = "E13";
		return json_encode($result);
	} else {
		$result["upload_name"] = $_FILES["file"]["name"];
		$result["file_type"] = $_FILES["file"]["type"];
		$result["file_size"] = $_FILES["file"]["size"] / 1024 . "kB";
		$result["temp_location"] = $_FILES["file"]["tmp_name"];
		$result["file_exists"] = false;
		if (file_exists("../../upload/" . $md5file . "." . $extension)) { //如果文件存在（拥有相同md5）
			$result["file_exists"] = true;
		} else {

			move_uploaded_file($_FILES["file"]["tmp_name"], "../../upload/" . $md5file . "." . $extension);
			createThumbImg("../../upload/" . $md5file . "." . $extension, 400, 400, "../../upload/thumbnail/" . $md5file . "." . $extension);
		}
		$result["file_name"] = $md5file . "." . $extension;
		$result["status"] = "success";
		echo json_encode($result);
	}
} else {
	$result["status"] = "E12";
	return json_encode($result);
}
