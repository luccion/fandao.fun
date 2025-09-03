<?php
define('ACC', false);
require('init.php');
?>
<html>
<style>
    .board {
        margin-top: 20%;
        display: flex;
        flex-direction: column;
        text-align: center;
        align-items: center;
        background: #3e5bbd;
        color: #fff;
        border-radius: 2rem;
        filter: drop-shadow(2px 4px 6px #00000055);
    }
</style>

<body>
    <div class="board">
        <h1>
            本页面用于凭空发行任何资产。
        </h1>
</body>

</html>
<?
if ($_SESSION['type'] == 4) {
    $TM = new transferModel();
    if ($TM->transfer_log("ISSUED", "fandao", 123, 9000, "ISSUED", 5)) {
?>
        <h3>
            已发行。
        </h3>
    <?
    }
    exit();
} else {
    ?>
    <h3>
        权限不足。
    </h3>
    </div>
<?
    exit();
}
