<?php
define('ACC', true);
require_once __DIR__ . '/init.php';

// 输入与默认值
$genderRand = rand(1, 3);
$lastNameRand = rand(1, 3);
$gender = isset($_POST['gender']) ? intval($_POST['gender']) : $genderRand;          // 1/2/3
$lastName = isset($_POST['lastName']) ? intval($_POST['lastName']) : $lastNameRand;  // 1..N（限定使用）
$firstName = isset($_POST['firstName']) ? (array)$_POST['firstName'] : [];
$nameLength = isset($_POST['nameLength']) ? max(1, intval($_POST['nameLength'])) : 1; // 至少1
$switch = isset($_POST['switch']) ? ($_POST['switch'] === 'false' ? false : (bool)$_POST['switch']) : true;

/* $gender = 1;
$lastName = 2;
$firstName = [false, false, true, false, false,];
$nameLength = 5;
$switch = false; */

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
    // firstNameArray 期望为5个布尔/字符串"true"/"false"
    for ($i = 0; $i < 5; $i++) {
        if (isset($firstNameArray[$i]) && ($firstNameArray[$i] === true || $firstNameArray[$i] === 'true' || $firstNameArray[$i] === 1 || $firstNameArray[$i] === '1')) {
            if ($i !== 0) { // 0 表示人类
                return true;
            }
        }
    }
    return false;
}


function parseFirstNameRace($firstNameArray)
{
    $allowed = [];
    for ($i = 0; $i < 5; $i++) {
        if (isset($firstNameArray[$i]) && ($firstNameArray[$i] === true || $firstNameArray[$i] === 'true' || $firstNameArray[$i] === 1 || $firstNameArray[$i] === '1')) {
            $allowed[] = $i + 1; // race: 1..5
        }
    }
    if (empty($allowed)) {
        return '`race` = 1'; // 默认人类
    }
    // 生成安全的IN列表
    $in = implode(',', array_map('intval', $allowed));
    return "`race` IN ($in)";
}

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
} else if (!empty($firstName[0]) && ($firstName[0] === true || $firstName[0] === 'true' || $firstName[0] === 1 || $firstName[0] === '1')) {
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

$conf = conf::getInstance();
// 允许使用与主库不同的连接（兼容历史 whiteverse 数据库）
$servername = getenv('NAMEGEN_DB_HOST') ?: $conf->host;
$username = getenv('NAMEGEN_DB_USER') ?: $conf->user;
$password = getenv('NAMEGEN_DB_PASS') ?: $conf->pass;
$dbname = getenv('NAMEGEN_DB_NAME') ?: $conf->db;
$con = mysqli_connect($servername, $username, $password);
if (!$con) {
    die('Could not connect: ');
}
mysqli_select_db($con, "$dbname");

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
    $lastNameSafe = intval($lastName);
    $sql = "SELECT * FROM `namegenerator` WHERE `gender`= 0 AND `race`= $lastNameSafe ORDER BY RAND() LIMIT 1";
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



if ($switch === false) {
    $firstName_zh = implode('·', $firstNameArray_zh);
    $firstName_en = implode(' ', $firstNameArray_en);
    $fullName["zh"] = $firstName_zh . "·" . $lastName_zh;
    $fullName["en"] = $firstName_en . " " . $lastName_en;
} else {
    // 按字典序排序，避免对字符串使用数值排序
    rsort($firstNameArray_zh, SORT_STRING);
    rsort($firstNameArray_en, SORT_STRING);
    $firstName_zh = implode('·', $firstNameArray_zh);
    $firstName_en = implode(' ', $firstNameArray_en);
    $fullName["zh"] =  $lastName_zh . "·" . $firstName_zh;
    $fullName["en"] = $lastName_en . " " . $firstName_en;
}


echo json_encode($fullName);
mysqli_close($con);
