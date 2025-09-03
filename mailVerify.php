<?php
define('ACC', true);
require('init.php');
require(ROOT . 'view/header.php'); ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12 main m-1 shadow-sm color-background rounded p-5">
            <?php
            $token = $_GET['token'];
            $username = $_GET['username'];
            $UM = new userModel();
            if ($UM->emailVerify($token, $username)) {
                $_SESSION['email_verified'] = 1;
                $_SESSION['type'] = 0;
            ?>
                <h3><i class="fa-solid fa-face-smile-wink"></i>&nbsp;账户验证成功。</h3>
                <small>现在你可以愉快地使用全部功能了！<br></small>
            <?php
            } else {
            ?>
                <h3><i class="fa-solid fa-face-frown"></i>&nbsp;验证失败。</h3>
                <small>怎么回事呢？<br></small>
            <?php
            }
            ?>
            <button class="btn color-button-primary mt-3 btn-block"><i class="fa-solid fa-arrow-left"></i><i class="fa-solid fa-home"></i>&nbsp;回到首页</button>
            <div class=" footText d-md-block">
                <hr class="footHr" />
                <div class="footContent color-font-secondary">
                    <div>&copy; 2022 <a href="https://whiteverse.city/">whiteverse.city</a>.All rights reserved.</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT . 'view/footer.php'); ?>
<script>
    $(document).ready(function() {
        $("#menuBtn,.nav-link").remove();
        $(".btn").click(() => {
            window.location.href = "index.php";
        })
    });
</script>