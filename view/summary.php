<?php defined('ACC') || exit('ACC Denied');
require(ROOT . 'view/header.php'); ?>


<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8 main m-1 shadow-sm color-background rounded p-3">
            <h3 class="color-font-default d-flex justify-content-between mb-3"><b><i class="fa-solid fa-magnifying-glass-chart"></i>&nbsp;饭岛统计</b></h3>
            <table class="table color-font-default">
                <tbody>
                    <thead class="color-background-grey">
                        <th colspan=2>内容</th>
                    </thead>
                    <tr>
                        <td><i class="fa-solid fa-comment-dots"></i>&nbsp;讨论总数</td>
                        <td><?php echo $countThreads; ?></td>

                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-comment-dots"></i>&nbsp;回复总数</td>
                        <td><?php echo $countReplies; ?></td>

                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-image"></i>&nbsp;上传的图片</td>
                        <td><?php echo $countFilesUploaded;  ?></td>

                    </tr>
                    <thead class="color-background-grey">
                        <th colspan=2>用户</th>
                    </thead>
                    <tr>
                        <td><i class="fa-regular fa-user"></i>&nbsp;用户总数</td>
                        <td><?php echo $countUsers; ?></td>
                    </tr>
                    <tr>
                        <td><i class="fa-solid fa-person-rays"></i>&nbsp;申请的化身</td>
                        <td><?php echo $countAvatars; ?></td>
                    </tr>
                    <thead class="color-background-grey">
                        <th colspan=2>薯条</th>
                    </thead>
                    <tr>
                        <td><i class="fa-solid fa-arrow-right-arrow-left"></i>&nbsp;交易总数</td>
                        <td><?php echo $countTransfer; ?></td>
                    </tr>
                    <tr>
                        <td><i class="fa-solid fa-piggy-bank"></i>&nbsp;流动资金量</td>
                        <td><?php echo $countFunds; ?></td>
                    </tr>
                    <tr>
                        <td><i class="fa-solid fa-gem"></i>&nbsp;已注册的NFT</td>
                        <td><?php echo $count_nft; ?></td>
                    </tr>
                </tbody>
            </table>
            <?php if ($_SESSION['type'] > 0) { ?>
                <h3 class="color-font-default d-flex justify-content-between mb-3">
                    <b><i class="fa-solid fa-chart-line"></i>&nbsp;<span><?php echo $dataCount ?></span>日流量趋势表</b>
                    <a class="calendarPicker" id="calendarPicker7" href="summary.php?days=7"><i class="fa-solid fa-calendar-week"></i></a>
                    <a class="calendarPicker" id="calendarPicker30" href="summary.php?days=30"><i class="fa-solid fa-calendar-days"></i></a>
                    <a class="calendarPicker" id="calendarPicker100" href="summary.php?days=100"><i class="fa-solid fa-calendar-plus"></i></a>
                </h3>
                <canvas id="myChart" width="400" height="200"></canvas>
                <h3 class="color-font-default d-flex justify-content-between mb-3"><b><i class="fa-solid fa-user"></i>&nbsp;用户列表</b></h3>
                <table class="table color-font-default transfer-log">
                    <thead class="color-background-grey">
                        <th>UID</th>
                        <th>用户名</th>
                        <th>最近登录</th>
                        <th>余额</th>
                        <th>用户组</th>
                        <th>管理组</th>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsersData as $ud) { ?>
                            <tr>
                                <td><?php echo $ud['uid']; ?></td>
                                <td><?php echo $ud['username']; ?></td>
                                <td class="<?php if (date("ymd", $ud['lastlogin']) === date("ymd")) {
                                                echo "color-font-important";
                                            } ?>"><?php echo date("ymd", $ud['lastlogin']); ?></td>
                                <td class="text-right"><? echo transferModel::calculate_amount(transferModel::get_wallet($ud['uid'])) ?></td>
                                <td><?php
                                    switch ($ud['type']) {
                                        case -1:
                                            echo "游客";
                                            break;
                                        case 0:
                                            echo "用户";
                                            break;
                                        case 1:
                                            echo "监督员";
                                            break;
                                        case 2:
                                            echo "开发者";
                                            break;
                                        case 3:
                                            echo "管理员";
                                            break;
                                        case 4:
                                            echo "执政官";
                                            break;
                                    }
                                    ?></td>
                                <td uid="<?php echo $ud['uid']; ?>" group="<?php echo $ud['type'] ?>">
                                    <?php $isBanned = userModel::isBanned($ud['uid']);
                                    if ($isBanned) {
                                        $expireTime = userModel::checkBanExpireTime($ud['uid']);
                                    ?>
                                        <span class="color-font-danger unBanUser"><i class="fa-solid fa-ban"></i><i class="fa-solid fa-arrow-right"></i><?php echo date("ymd", $expireTime) ?></span>
                                    <?php
                                    } else {
                                    ?>
                                        <span class="badge color-badge-default banUser <?php echo $ud['type'] > 3 ? "invisible" : "visible" ?>"><i class="fa-solid fa-ban"></i></span>
                                        <span class="badge color-badge-default levelChange 
                                        <?php if ($_SESSION['type'] > 2 && ($ud['type'] < 4 && $ud['type'] > 0)) {
                                            echo "visible";
                                        } else {
                                            echo "invisible";
                                        } ?>" level=-1><i class="fa-regular fa-circle-down"></i></span>
                                        <span class="badge color-badge-default levelChange 
                                        <?php if ($_SESSION['type'] > 2 && $ud['type'] < 3) {
                                            echo "visible";
                                        } else {
                                            echo "invisible";
                                        }
                                        ?>" level=1><i class="fa-regular fa-circle-up"></i></span>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            <div class=" footText d-md-block">
                <hr class="footHr" />
                <div class="footContent color-font-secondary">
                    <div>&copy; <?php echo date("Y", time()); ?> <a href="https://whiteverse.city/">whiteverse.city</a>.All rights reserved.</div>
                    <div><?php echo SITEVERSION; ?></div>
                </div>
            </div>

        </div>
    </div>
</div>

</div>
<script src="https://cdn.bootcdn.net/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<?php include(ROOT . 'view/footer.php'); ?>

<script>
    $(document).ready(function() {
        $("#menuBtn").remove();
    });
    <?php if ($_SESSION['type'] > 0) { ?>
        const ctx = document.getElementById('myChart').getContext('2d');
        const DATA_COUNT = <?php
                            echo $dataCount + 1 ?>;
        const labels = [];
        for (let i = 0; i < DATA_COUNT; ++i) {
            labels.push(i.toString());
        }
        const dataIPs = <?php
                        $countIPs = userModel::countIPs($dataCount);
                        $str = "[";
                        foreach ($countIPs as $k) {
                            $str .= $k . ",";
                        }
                        $str .= "]";
                        $str = str_replace(",]", "]", $str);
                        echo $str; ?>;
        const data = {
            labels: labels,
            datasets: [{
                label: 'ip',
                data: dataIPs,
                fill: false,
                borderColor: ["#e83e8c"],
                tension: 0.23
            }]
        };
        const myChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                interaction: {
                    intersect: false,
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: false,
                            text: 'Value'
                        },
                    }
                }
            },
        });

    <?php } ?>
</script>