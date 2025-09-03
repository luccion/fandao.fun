<?php
define('ACC', true);
require('../../init.php');
if (!userModel::isLogin()) {
  exit('E1');
}
/*
    login.php

    MediaWiki API Demos
    Demo of `Login` module: Sending post request to login
    MIT license
*/

$lgName = $_POST["userName"];
$lgPassword = $_POST["userPassword"];

$endPoint = "https://wiki.whiteverse.com/api.php";

$login_Token = getLoginToken(); // Step 1
loginRequest($login_Token, $lgName, $lgPassword); // Step 2

// Step 1: GET request to fetch login token
function getLoginToken()
{
  global $endPoint;

  $params1 = [
    "action" => "query",
    "meta" => "tokens",
    "type" => "login",
    "format" => "json"
  ];

  $url = $endPoint . "?" . http_build_query($params1);

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/cookie.txt");
  curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");

  $output = curl_exec($ch);
  curl_close($ch);

  $result = json_decode($output, true);
  return $result["query"]["tokens"]["logintoken"];
}

// Step 2: POST request to log in. Use of main account for login is not
// supported. Obtain credentials via Special:BotPasswords
// (https://www.mediawiki.org/wiki/Special:BotPasswords) for lgname & lgpassword
function loginRequest($logintoken, $lgName, $lgPassword)
{
  global $endPoint;

  $params2 = [
    "action" => "clientlogin",
    "username" => $lgName,
    "password" => $lgPassword,
    "logintoken" => $logintoken,
    "loginreturnurl" => 'https://fandao.whiteverse.com/',
    "format" => "json"
  ];

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $endPoint);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params2));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/cookie.txt");
  curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");

  $output = curl_exec($ch);
  curl_close($ch);
  $result = json_decode($output, true);

  if ($result["clientlogin"]["status"] == "PASS") {
    $userName = $result["clientlogin"]["username"];

    //录入至fandao_user
    if (userModel::set_user_wiki_name($userName, $_SESSION['uid'])) {
      $_SESSION['wiki_user_name'] = $userName;
      echo "success";
    } else {
      exit("E10");
    }
  } else {
    exit("E2");
  }
}
