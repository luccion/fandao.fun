<?php
define('ACC', true);
require('../../init.php');
require('../model/Multiavatar.php');
$count = $_POST['count'];
$fullName = array();
$servername = "localhost";
$username = "whiteverse";
$password = "TLG3TGjanYXbALBP";
$dbname = "whiteverse";
$con = mysqli_connect($servername, $username, $password);
if (!$con) {
    die('Could not connect: ');
}
mysqli_select_db($con, "$dbname");

function weightDiff($a, $b)
{
    if ($b <= 0) { //稳健
        $b = 1;
    }
    $c = $a / $b;
    $r = rand(0, 100) / 100;
    if ($r <= $c) {
        return true;
    } else {
        return false;
    }
}
function hasInhumanRace($firstNameArray)
{
    for ($i = 1; $i < 5; $i++) {
        if ($firstNameArray[$i] == "true") {
            return true;
        } else {
            return false;
        }
    }
}

function parseFirstNameRace($firstNameArray)
{
    /* $array = false; */
    for ($i = 0; $i < 5; $i++) {
        if ($firstNameArray[$i] == "true") {
            $a = $i + 1;
            $array[$i] = "`race`=" . $a;
        }
    }
    /*     if (!$array) {
        $str = '`race` = 1';
        return $str;
    } */

    $str = implode(' OR ', $array);
    return $str;
}
for ($m = 0; $m < $count; $m++) {
    $gender = rand(1, 3);
    $lastName = rand(1, 3);
    $firstName = [true, true, true, false, false];
    $nameLength = 1;
    $switch = true;

    if (!hasInhumanRace($firstName)) { //数据库未整理时的妥协
        switch ($gender) {
            case 1:
                $strGender = "`gender`= 1 OR `gender`= 3";
                break;
            case 2:
                $strGender = "`gender`= 2 OR `gender`= 3";
                break;
            case 3:
                $strGender = "`gender`= 1 OR `gender`= 2 OR `gender`= 3";
                break;
        }
    } else if ($firstName[0]) {
        switch ($gender) {
            case 1:
                $strGender = "`gender`= 1 OR `gender`= 3";
                break;
            case 2:
                $strGender = "`gender`= 2 OR `gender`= 3";
                break;
            case 3:
                $strGender = "`gender`= 1 OR `gender`= 2 OR `gender`= 3";
                break;
        }
    } else {
        $strGender = "`gender`= 1 OR `gender`= 2 OR `gender`= 3";
    }
    $strRace = parseFirstNameRace($firstName);

    //获取符合条件的名字
    for ($i = 0; $i < $nameLength; $i++) {
        $sql = "SELECT * FROM `namegenerator` WHERE (" . $strGender . " ) AND (" . $strRace . " ) ORDER BY RAND() LIMIT 1";
        $result = $con->query($sql);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($con));
            exit();
        }
        $row = mysqli_fetch_assoc($result);

        $firstNameArray_zh[$i] = $row["name_zh"];
        $firstNameArray_en[$i] = $row["name_en"];
    }
    //生成复姓
    //思路：获取两次姓氏，并用-连接

    if (weightDiff(25, 100)) {
        $compoundSurnameTimes = 2;
    } else {
        $compoundSurnameTimes = 1;
    };
    $lastName_zh = "";
    $lastName_en = "";
    for ($i = 0; $i < $compoundSurnameTimes; $i++) {
        //获取符合条件的姓氏
        $sql = "SELECT * FROM `namegenerator` WHERE `gender`= 0 AND  `race`= " . $lastName . "  ORDER BY RAND() LIMIT 1";
        $result = $con->query($sql);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($con));
            exit();
        }
        $row = mysqli_fetch_assoc($result);
        $lastName_zh = $lastName_zh . "-" . $row["name_zh"];
        $lastName_en = $lastName_en . "-" . $row["name_en"];
    }
    $lastName_zh = ltrim($lastName_zh, "-");
    $lastName_en = ltrim($lastName_en, "-");

    if ($switch == "false") {
        $firstName_zh = implode('·', $firstNameArray_zh);
        $firstName_en = implode(' ', $firstNameArray_en);
        $fullName[$m]["zh"] = $firstName_zh . "·" . $lastName_zh;
        $fullName[$m]["en"] = $firstName_en . " " . $lastName_en;
    } else {
        rsort($firstNameArray_zh, SORT_NUMERIC);
        rsort($firstNameArray_en, SORT_NUMERIC);
        $firstName_zh = implode('·', $firstNameArray_zh);
        $firstName_en = implode(' ', $firstNameArray_en);
        $fullName[$m]["zh"] =  $lastName_zh . "·" . $firstName_zh;
        $fullName[$m]["en"] = $lastName_en . " " . $firstName_en;
    }
    $avatar = new Multiavatar();
    $fullName[$m]['md5'] = md5($fullName[$m]["zh"] . $fullName[$m]["en"]);
    $fullName[$m]["svg"] = $avatar($fullName[$m]['md5'], null, null);
}
echo json_encode($fullName);