<?php

//[*邮件发送逻辑处理过程*系统核心配置文件*]

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

// 加载配置
define('ACC', true);
require_once __DIR__ . '/init.php';
// 读取 SMTP 配置
$conf = conf::getInstance();
$smtpHost = getenv('SMTP_HOST') ?: 'smtp.exmail.qq.com';
$smtpUser = getenv('SMTP_USER') ?: 'codd@whiteverse.com';
$smtpPass = getenv('SMTP_PASS') ?: '';
$smtpPort = (int)(getenv('SMTP_PORT') ?: 465);
$smtpSecure = getenv('SMTP_SECURE') ?: 'ssl';
$mailFrom = getenv('MAIL_FROM') ?: $smtpUser;
$mailFromName = getenv('MAIL_FROM_NAME') ?: (defined('SITENAME') ? SITENAME : 'fandao');

// 基础参数校验（这些变量应由上游注入）
$email = isset($email) ? $email : ($_POST['email'] ?? $_GET['email'] ?? '');
$avatar_cn = isset($avatar_cn) ? $avatar_cn : ($_POST['avatar_cn'] ?? '');
$token = isset($token) ? $token : ($_POST['token'] ?? '');
$username = isset($username) ? $username : ($_POST['username'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('无效的邮箱地址');
}
if (!$token || !$username) {
    exit('缺少必要参数');
}

$mail = new PHPMailer(true);       // Passing `true` enables exceptions
try {
    //服务器配置
    $mail->CharSet = 'UTF-8';                     //设定邮件编码
    $mail->SMTPDebug = 0;                        // 调试模式输出
    $mail->isSMTP();                             // 使用SMTP
    $mail->Host = $smtpHost;                     // SMTP服务器
    $mail->SMTPAuth = true;                      // 允许 SMTP 认证
    $mail->Username = $smtpUser;                 // SMTP 用户名
    $mail->Password = $smtpPass;                 // SMTP 密码/授权码
    $mail->SMTPSecure = $smtpSecure;             // 加密: ssl/tls
    $mail->Port = $smtpPort;                     // 服务器端口

    $mail->setFrom($mailFrom, $mailFromName);
    $mail->addAddress($email, $avatar_cn ?: $username);  // 收件人
    $mail->addReplyTo($mailFrom, ''); // 回复地址

    $verifyBase = getenv('OAUTH_REDIRECT_URI') ? preg_replace('/\/oauth\.php$/', '', getenv('OAUTH_REDIRECT_URI')) : 'https://fandao.fun';
    $url = rtrim($verifyBase, '/') . '/mailVerify.php?token=' . urlencode($token) . '&username=' . urlencode($username);
    //Content
    $mail->isHTML(true); // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
    $mail->Subject = ($mailFromName ?: '饭岛') . '|邮箱验证';
    $mail->Body    = '
     <style>
        #confirm {
            color: white;
            background: #007bff;
            font-size: 1.5rem;
            padding: 1rem;
            margin: 1rem;
            border-radius: 0.3rem;
            border-width: 0rem 0.5rem 2rem 0.5rem;
            border-color: #006bdd #228afd #006bdd #1970cd;
            box-shadow: 1px 2px 9px 0px #00000066;
            transition: all 0.6s;
        }

        #confirm:hover {
            background: #238dff;
            cursor: pointer;
        }

        #confirm:active {
            color: white;
            background: #007bff;
            font-size: 1.5rem;
            padding: 1rem;
            margin: 1rem;
            border-radius: 0.3rem;
            border-width: 0rem 0.5rem 1rem 0.5rem;
            border-color: #006bdd #0053af #004997 #1970cd;
            box-shadow: 1px 2px 9px 0px #00000066;
            transition: all 0.1s;
        }
    </style>
    <div style="display:flex;flex-direction:column;align-items:center;background:
        white;box-shadow:1px 1px 6px 1px #00000042;margin:1rem;padding:1rem;border-radius:0.3rem;">
        <div>
            <a href="fandao.fun" alt="饭岛">
         <svg viewBox="0 0 65.8 32.95" style="height:2.3rem;stroke:#007bff;fill:none;stroke-linecap:square;stroke-linejoin:bevel;stroke-width:2.5px;margin:2rem"><path d="m11.77 8.94 5.53-.63 1.09-.22c-3.23-1.38-3.55-2.86-4.45-2.9l.37-1.34c0 3.63-7.94 6.6-8 9.65M11.33 15.55c1.3 0 1.81-.06 6.66-.07"/><path d="M12.14 21.27c11.32 0 1.8 1.74.52 3.62-.28.4-.94 1-1.23.59-2.56-3.48.76-10.54-.69-13.48.18-.33.27-.47.27-.47h.14c3.08-.67 6.88.71 7.19.54a24.85 24.85 0 0 1-.94 7.24L14.93 19c-1.28.11-3.13-.08-3.59-.07"/><path d="M16.8 26.42c4.6-3 5.73-14.87 3.58-17.72-.05-.16-.09-.17 0-.23a.72.72 0 0 1 .55-.16c1.44.14.54-.6 10-1.68"/><path d="M21.58 14.48c-.68 1.49 21-7.42.14 11.26"/><path d="M22.64 15.55c2.73 2 5.94 8.26 10.66 9.87M40.67 9.57c2.12.36 6.54-.32 11-.6M39.86 13.31c1.68.2 1-1.24 11.21-.68"/><path d="M40.42 15.88c3.21-.36 9.07-.81 9.66-.59.34.13 1.17-2.93 1.64-5.66a3.36 3.36 0 0 0-3.55-3.91c-2.34.17-4.76.5-7.2.52"/><path d="M51.31 27c7.31 2.49 4.64-6.14 4.59-8.07s-13 .1-16.5-.14l.17-1.13S40.4 5 40.64 4.63l.56-.39c.13 0 4.88-1.62 6.21-1.86"/><path d="M43.65 25.32c0-.19.06-4.31.06-4.31"/><path d="M37.19 22.77v.65a1.71 1.71 0 0 0 1.3 1.83c3.17.67 9.27-.11 11.7-1.29.54-.27.24-1.48-.07-2.54M1.35 8.92l-.1-7.36a.35.35 0 0 1 .06-.24.37.37 0 0 1 .26-.07h4.51M59.32 31.66l4.92-.19a.38.38 0 0 0 .24-.07c.08-.07.07-.19.06-.3l-.75-8.35"/></svg>
            </a>
        </div>
        <h3>您在饭岛上创建了账户' . $avatar_cn . '@' . $username . '，并提交了您的电子邮箱地址。请点击下方按钮验证邮箱：</h3>
        <div style="
    display: flex;
    height: 7rem;
    align-items: flex-end;margin:2rem">
            <a href="' . $url . '"><button type="button" id="confirm">验证邮箱</button></a>
        </div>
        <div style="margin-bottom:2rem;color:grey;word-break: break-all;">
            <small>或直接复制该链接到浏览器进行邮箱验证：<br></small>
            <small>' . $url . '</small>
        </div>

        <div>您需要向饭岛提供一个验证过的电子邮件地址来使用饭岛的完整功能，以及在未来能够安全的找回您的帐户。</div>
        <small style="margin: 2rem;
    border-color: #007bff;
    border-style: dashed;
    padding: 1rem;
    border-width: 0rem 0.1rem 0rem 0.1rem;">祝您玩得开心，<br>科德@whiteverse.com<br>' . date('Y-m-d H:i:s') . '</small>
    </div>
    <div style="text-align:center">
        <div style=" font-size:small;color:grey;">本邮件由机器人科德自动发出，请不要回复。</div>
        <div style=" font-size:small;color:grey;">©<a href="fandao.fun"
                style=" text-decoration:none;color:grey;">fandao.fun</a>
            All Rights Reserved.</div>
    </div>  ';
    $mail->AltBody = '欢迎来到' . ($mailFromName ?: '饭岛') . '！复制该链接到浏览器进行邮箱验证：' . $url . ',' . date('Y-m-d H:i:s');
    $mail->send();
    /*  echo '验证邮件发送成功，请注意查收！'; */
} catch (Exception $e) {
    exit('邮件发送失败: ' . $mail->ErrorInfo);
}
