<?php defined('ACC') || exit('ACC Denied');
$isLogin = userModel::isLogin();
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Lunch" />
    <meta name="description" content="不是饭岛" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="theme-color" content="#007bff">
    <title>不是饭岛</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="57x57" href="./view/images/touch/homescreen57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="./view/images/touch/homescreen72.pn" />
    <link rel="apple-touch-icon" sizes="114x114" href="./view/images/touch/homescreen114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="./view/images/touch/homescreen144.png" />
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="./view/css/main.css" rel="stylesheet">
    <link rel="manifest" href="./view/manifest.json">
    <link href="./view/css/theOther-theme.css" rel="stylesheet" id="theme-link">
    <?php
    function fontawesomeNumber($number)
    {
        $n = str_split(strval($number));
        $fNumber = "";
        for ($i = 0; $i < sizeof($n); $i++) {
            if ($n[$i] == "-") {
                $fNumber = $fNumber . '<i class="fa-solid fa-minus"></i>';
            } else if ($n[$i] == ".") {
                $fNumber = $fNumber . '<b>.</b>';
            } else {
                $fNumber = $fNumber . '<i class="fa-solid fa-' . $n[$i] . '"></i>';
            }
        }

        return $fNumber;
    }
    ?>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker
                .register('serviceWorker.js', {
                    scope: '/'
                })
        }
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-sm shadow-sm p-1 color-background">
        <a class="navbar-brand" href="index.php" alt="饭岛">
            <svg class="logoText" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 69.08 32.95">
                <path class="cls-1" d="m13.7 8.94 5.53-.63 1.1-.22c-3.24-1.38-3.56-2.86-4.46-2.9l.38-1.34c0 3.63-7.95 6.6-8 9.65M13.27 15.55c1.29 0 1.8-.06 6.65-.07" />
                <path class="cls-1" d="M14.08 21.27c11.31 0 1.8 1.74.51 3.62-.27.4-.94 1-1.23.59-2.55-3.48.77-10.54-.69-13.5a5.15 5.15 0 0 1 .28-.47h.13c3.08-.67 6.89.71 7.19.54a24.65 24.65 0 0 1-.93 7.24L16.87 19c-1.28.11-3.13-.08-3.6-.07" />
                <path class="cls-1" d="M18.74 26.42c4.59-3 5.73-14.87 3.58-17.72-.05-.16-.1-.17 0-.23a.75.75 0 0 1 .55-.16c1.45.14.54-.6 10-1.68" />
                <path class="cls-1" d="M23.51 14.48c-.68 1.49 21-7.42.15 11.26" />
                <path class="cls-1" d="M24.58 15.55c2.73 2 5.94 8.26 10.66 9.87M42.6 9.57c2.13.36 6.55-.32 11-.6M41.8 13.31c1.67.2 1-1.24 11.21-.68" />
                <path class="cls-1" d="M42.35 15.88c3.21-.36 9.07-.81 9.66-.59.35.13 1.18-2.93 1.64-5.66a3.34 3.34 0 0 0-3.54-3.91c-2.34.17-4.76.5-7.2.52" />
                <path class="cls-1" d="M57.84 18.9c-.05-1.93-13 .1-16.5-.14l.17-1.13s.83-12.66 1.07-13l.55-.39c.14 0 4.89-1.62 6.21-1.86M53.25 27c7.31 2.49 4.63-6.14 4.59-8.07M45.59 25.32c0-.19.06-4.31.06-4.31" />
                <path class="cls-1" d="M39.12 22.77v.65a1.72 1.72 0 0 0 1.31 1.83c3.17.67 9.26-.11 11.7-1.29.53-.27.23-1.48-.07-2.54M3.29 8.92l-.11-7.36a.41.41 0 0 1 .06-.24.38.38 0 0 1 .26-.07H8M61.26 31.66l4.92-.19a.4.4 0 0 0 .24-.07.38.38 0 0 0 .06-.3l-.76-8.35" />
                <path style="stroke:#040000;stroke-width:10px;fill:none;stroke-linecap:square;stroke-linejoin:bevel" d="M5 14.95h59.08" />
            </svg>
        </a>
        <?php if (!$isLogin) { ?>
            <a class="nav-link btn meBtn color-theme" data-toggle="modal" data-target="#loginModal" alt="login"><i class="fa-solid fa-right-to-bracket"></i><i class="fa-solid fa-user-plus"></i>&nbsp;登陆/注册</a>
        <?php } else { ?>
            <a class="nav-link btn meBtn color-theme" href="./me.php" role="button">
                <span class="del">
                    <?php
                    echo $_SESSION['avatar'] . "</span>";
                    if ($_SESSION['notifications'] != 0) {
                    ?>
                        <span class="badge color-badge-danger ">
                            <?php echo $_SESSION['notifications'] ?>
                        </span>
                    <?php } ?>
            </a>
        <?php } ?>
        <button id="menuBtn" class="btn d-md-none color-theme" type="button" aria-controls="lmenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
    </nav>

    <div class="profileview color-background">
        <div class="avatarSVG-show"></div>
        <div class="profileName"></div>
    </div>







    <?php include(ROOT . 'view/tide.html'); ?>
    <?php include(ROOT . 'view/footer.php'); ?>