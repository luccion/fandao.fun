<?php defined('ACC') || exit('ACC Denied');
$isLogin = userModel::isLogin();
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Lunch" />
    <meta name="description" content="来饭岛吹吹海风吧" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="google-adsense-account" content="ca-pub-7361138147829873" />
    <meta name="theme-color" content="#007bff">
    <title><?php echo SITENAME; ?></title>
    <link rel="shortcut icon" href="/favicon.svg" type="image/svg+xml" />

    <link rel="apple-touch-icon" sizes="57x57" href="./view/images/touch/homescreen57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="./view/images/touch/homescreen72.pn" />
    <link rel="apple-touch-icon" sizes="114x114" href="./view/images/touch/homescreen114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="./view/images/touch/homescreen144.png" />
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="./view/css/main.css" rel="stylesheet">
    <link rel="manifest" href="./view/manifest.json">
    <link href="<?php switch ($_COOKIE["THEME"]) {
                    case 'DARKMODE':
                        echo './view/css/dark-theme.css';
                        break;
                    case 'LIGHTMODE':
                        echo './view/css/light-theme.css';
                        break;
                    default:
                        echo './view/css/light-theme.css';
                }

                ?>" rel="stylesheet" id="theme-link">
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
        <a class="navbar-brand color-theme" href="index.php" alt="饭岛">
            <svg class="logoText" viewBox="0 0 65.8 32.95">
                <path d="m11.77 8.94 5.53-.63 1.09-.22c-3.23-1.38-3.55-2.86-4.45-2.9l.37-1.34c0 3.63-7.94 6.6-8 9.65M11.33 15.55c1.3 0 1.81-.06 6.66-.07" />
                <path d="M12.14 21.27c11.32 0 1.8 1.74.52 3.62-.28.4-.94 1-1.23.59-2.56-3.48.76-10.54-.69-13.48.18-.33.27-.47.27-.47h.14c3.08-.67 6.88.71 7.19.54a24.85 24.85 0 0 1-.94 7.24L14.93 19c-1.28.11-3.13-.08-3.59-.07" />
                <path d="M16.8 26.42c4.6-3 5.73-14.87 3.58-17.72-.05-.16-.09-.17 0-.23a.72.72 0 0 1 .55-.16c1.44.14.54-.6 10-1.68" />
                <path d="M21.58 14.48c-.68 1.49 21-7.42.14 11.26" />
                <path d="M22.64 15.55c2.73 2 5.94 8.26 10.66 9.87M40.67 9.57c2.12.36 6.54-.32 11-.6M39.86 13.31c1.68.2 1-1.24 11.21-.68" />
                <path d="M40.42 15.88c3.21-.36 9.07-.81 9.66-.59.34.13 1.17-2.93 1.64-5.66a3.36 3.36 0 0 0-3.55-3.91c-2.34.17-4.76.5-7.2.52" />
                <path d="M51.31 27c7.31 2.49 4.64-6.14 4.59-8.07s-13 .1-16.5-.14l.17-1.13S40.4 5 40.64 4.63l.56-.39c.13 0 4.88-1.62 6.21-1.86" />
                <path d="M43.65 25.32c0-.19.06-4.31.06-4.31" />
                <path d="M37.19 22.77v.65a1.71 1.71 0 0 0 1.3 1.83c3.17.67 9.27-.11 11.7-1.29.54-.27.24-1.48-.07-2.54M1.35 8.92l-.1-7.36a.35.35 0 0 1 .06-.24.37.37 0 0 1 .26-.07h4.51M59.32 31.66l4.92-.19a.38.38 0 0 0 .24-.07c.08-.07.07-.19.06-.3l-.75-8.35" />
            </svg>
        </a>
        <?php if (!$isLogin) { ?>
            <a class="nav-link btn meBtn color-theme" data-toggle="modal" data-target="#loginModal" alt="login"><i class="fa-solid fa-right-to-bracket"></i><i class="fa-solid fa-user-plus"></i>&nbsp;登陆/注册</a>
        <?php } else { ?>
            <a class="nav-link btn meBtn color-theme" href="me.php" role="button">
                <div class="avatarSVG-head">
                    <? echo usermodel::getUserCurrentAvatarSVG($_SESSION['current_aid']); ?>
                </div>
                <?php
                echo $_SESSION['avatar'];
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
    <div id="installContainer">
        <button id="butInstallCancel" type="button" title="cancel">
            <i class="fa-regular fa-circle-xmark"></i>
        </button>
        <div id="installText">🎉哇，你的设备支持安装饭岛lite!<br>要安装到设备上吗？</div>
        <button id="butInstall" type="button" title="install">
            <i class="fa-regular fa-circle-check"></i>
        </button>
    </div>
    <div class="quoteview color-background"></div>
    <div class="profileview color-background">
        <div class="avatarSVG-show"></div>
        <div class="profileName"></div>
    </div>

    <?php /* include(ROOT . 'view/tide.html'); */ ?>