<?php defined('ACC') || exit('ACC Denied');
require(ROOT . 'view/header.php');


?>

<link href="./view/css/lottery.css" rel="stylesheet">
<div class="seagull-container">
    <svg class="seagull" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 124.77 241.47">
        <path class="cls-1" d="M32.23,57.52S17.56,66.19,12.73,78a30,30,0,0,0-1.58,15.17s-9.67,9.33-9,28.75S9,150.6,9,150.6l5.25-4.66s13.5,6.75,21.33,6.83S32.23,57.52,32.23,57.52Z" />
        <path class="cls-1" d="M82.4,51.1s28.83,10.84,30,37.17c0,0,24,36.17-.5,61.67L74.06,64.6Z" />
        <path class="cls-2" d="M32.17,47.1s-7.33-14.77-8.55-21.55.78-22,19.78-24.33S60,16.22,60,16.22s2.85,1.81,6.79,12a13.73,13.73,0,0,0,4.6,6c2.41,1.82,6.17,6,10.75,15.74A14.57,14.57,0,0,0,88,56.29a44.21,44.21,0,0,1,21.61,32.48s5.34,8.17,8.67,25.5c0,0,19.5,29.83-8.83,33.5,0,0-7-.67-17.34,6.5l5.84,17.5a58.61,58.61,0,0,1-24.5-4.67l-.17-2.16s-2,2.33-9.17.33c0,0-2.66-1.67-1.66-4.17,0,0-32,.34-27.84-8.33,0,0,.5-3.83-.33-3.83s-15.33,0-20-3c0,0-24.33-5-7-29.5,0,0,1.33-5.34,3.33-8.17,0,0-1.83-3.67,2.67-10.67,0,0-5-5.16,4.5-12.33,0,0-1-13.5,14-24Z" />
        <path class="cls-3" d="M34.41,23.37a2.42,2.42,0,0,0-.15-2.55,8.35,8.35,0,0,0-3.15-2.67c-2.33-1.15-2.72,1.48-2.79,2.66a2.62,2.62,0,0,0,.14,1c.41,1.11,1.56,4,2.44,3.7S33.13,25.74,34.41,23.37Z" />
        <path class="cls-3" d="M58.24,11.89a1.13,1.13,0,0,0-1.48,0,4.18,4.18,0,0,0-1.28,2.84A3.35,3.35,0,0,0,58,18.19s1.5.71,1.25-2.09C59.06,13.69,59.43,12.9,58.24,11.89Z" />
        <path class="cls-4" d="M45.51,28.1s15.69,6,16.08,9.53c0,0,.56,1,.58-.08S59.45,27.58,45.51,28.1Z" />
        <path class="cls-5" d="M76.31,156.77c-.16.5-2.16,12.83-2.16,12.83s-3.42,4.09-.84,11.17c0,0-.25,18.58-3.16,29.42l-5.92,6.41S56.06,218,48.48,227.69c0,0,2.58-.42,3.17,1.25,0,0,16.08-4,24.5,1.33,0,0,.66-7.25,4.16-.42,0,0,2.67-6.33,20.25-8.08,0,0-3.5-1.5-1.16-3.25,0,0-15.67-.42-19.84-1.92,0,0-4.33-1.66-4.83-4.41,0,0,.67-20,2.67-30.25,0,0,2.83-1.09,2.33-10.34l1.5-17.16-2.58,5.33Z" />
        <path class="cls-6" d="M76.15,230.27a10.73,10.73,0,0,0,2.33,3.08s2.06-2.91,2.15-4.37S76.5,224.27,76.15,230.27Z" />
        <path class="cls-6" d="M99.9,221.52s.94-1,3.33,1.33c0,0-.67-6.36-4.47-3.86C98.76,219,98.12,220.33,99.9,221.52Z" />
        <path class="cls-7" d="M44.4,28s8.41-1.92,6.75-7.54,3.71.46,3.71.46,8.3,3.1,7.31,16.61C62.17,37.55,61,27.69,44.4,28Z" />
        <path class="cls-8" d="M54.86,20.94S57.73,23.85,61,24.1c0,0,.25,2,1.38,13.17C62.36,37.27,63.69,26.44,54.86,20.94Z" />
        <path class="cls-6" d="M49.2,230.63a22.91,22.91,0,0,1,.86-3.22c.27-.4,1.9,1.28,1.75,1.67C51.81,229.08,51.49,228.28,49.2,230.63Z" />
        <path class="cls-9" d="M51.86,228.77s14.87-3.71,23.83,1.29c0,0-1.63-13.16-5.42-16.25,0,0-.87-.5-5.66,4.59C64.61,218.4,56.19,221.1,51.86,228.77Z" />
        <path class="cls-9" d="M75.81,215.69a56.73,56.73,0,0,1,5.06,12.89s5.86-5.89,19-7.06a2.05,2.05,0,0,1-1.67-2.5S81.73,222.23,75.81,215.69Z" />
        <path class="cls-5" d="M39.62,153.33,42,156.77l2.78-.44L44.4,167s3.11,3.34,0,12.45c0,0-1.11,19.11-.23,30l3.34,5.11L64.74,220a.2.2,0,0,1,.09.33,1.52,1.52,0,0,0-.43,1,.22.22,0,0,1-.39.12c-1.16-1.33-5.68-2.58-22.28,15.59,0,0-1.78-4.11-5.67,0,0,0-5.89-8.11-19.55-4.89,0,0-1-3.11-2.22-2.77S23,212,37.51,213.66l2.44-4s1.11-26,.22-31.22c0,0-2.55-5.34-.66-9.56C39.51,168.88,40.62,155.77,39.62,153.33Z" />
        <path class="cls-6" d="M15.12,228.52a5.72,5.72,0,0,0-.58,4.95l2.61-2.5Z" />
        <path class="cls-6" d="M36,235.69c-.08.08.55,4.39,2.83,5.78,0,0,3.44-3.53,3-5.56S37.87,233.72,36,235.69Z" />
        <path class="cls-6" d="M64.07,221.57a3,3,0,0,1,2.61,1.77s1.13-3.44-1.17-3.37S64.07,221.57,64.07,221.57Z" />
        <path class="cls-10" d="M17.23,231.6s6-17.45,20.5-16.29L36,235.94S30.9,229.88,17.23,231.6Z" />
        <path class="cls-10" d="M41.9,236.23S59,217.35,63.84,221.05c0,0-15.8-4.28-19.69-5C44.15,216.1,40.12,227,41.9,236.23Z" />
    </svg>
    <div class="seagullsword talk-bubble tri-right bubbleborder round btm-left-in invisible">
        <div class="talktext">
            <b>欢迎来玩！你想整点🍟吗？</b>
            <p>使用<b>3</b>x🍟购买一张彩票，选取你中意的零食~<br>每日21:00开奖，22:00开放购买！</p>
            <table class="table small">
                <tr>
                    <td>特等奖</td>
                    <td>全等</td>
                    <td>奖池90%，保底<b>200</b>x🍟</td>
                </tr>
                <tr>
                    <td>一等奖</td>
                    <td>4图案</td>
                    <td>奖池50%，保底<b>100</b>x🍟</td>
                </tr>
                <tr>
                    <td>二等奖</td>
                    <td>3图案</td>
                    <td><b>6</b>x🍟</td>
                </tr>
                <tr>
                    <td>安慰奖</td>
                    <td>2图案</td>
                    <td><b>4</b>x🍟</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12 main m-1 shadow-sm color-background rounded p-3 color-font-default align-middle">
            <div class="lottery-title color-background">
                <h2 class="d-flex justify-content-between mt-2">
                    <div class="d-flex flex-row">
                        <div>喜高乐透</div>
                        <div class="subTitle">SEAGULL LOTTERY</div>
                    </div>
                    <span data-toggle="tooltip" data-placement="bottom" title="我的余额"><span class="color-font-default myAmount"><?php echo $account ?></span>🍟</span>
                </h2>
                <hr class="lottery-hr" />
            </div>
            <!-- <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fa-solid fa-bell"></i>&nbsp;通知</strong> 为保证奖池积累，彩票价格即日起将上涨1🍟，奖金也将进行调整。
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> -->
            <div class="col d-flex flex-column justify-content-center text-center align-items-center mb-2">
                <div><span id="countTickets">本日已售出彩票<?php echo $ticktsCount; ?>张，</span><span id="countdownMSG"><i class="fa-regular fa-clock"></i>&nbsp;距离开奖还有</span></div>
                <div id="countdown">
                    <div id='tiles'></div>
                </div>
                <div class="slotMachine">
                    <canvas class="lottery"></canvas>
                </div>
                <div class="col-9 mb-4">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="emoji-random emoji-input-btn input-group-text" id=""><i class="fa-solid fa-shuffle"></i></span>
                        </div>
                        <input class="emoji-input form-control " type="text" id="emoji-input" maxlength="8" readonly>
                        <div class="input-group-append">
                            <span class="emoji-delete emoji-input-btn input-group-text" id=""><i class="fa-solid fa-delete-left"></i></span>
                        </div>
                    </div>
                    <div class="emoji-group mb-2">
                        <div class="row d-flex justify-content-center">
                            <?php $chars = ['🍺', '🍕', '🥑', '🍭', '🍟', '🍦', '🍉', '🍙', '🥐'];
                            $i = 0;
                            foreach ($chars as $c) {
                                echo '<button class="emoji-btn m-1">' . $c . '</button>';
                                $rows = 3; //希望有几列
                                if (($i + 1) % $rows == 0) {
                                    echo '</div> <div class="row d-flex justify-content-center">';
                                }
                                $i++;
                            } ?>
                        </div>
                    </div>
                    <button class="btn color-button-primary" id="buyaticket"><i class="fa-solid fa-ticket"></i>&nbsp;购买彩票!</button>
                    <div class="small color-font-secondary" id="account">余额：<span class="myAmount"><?php echo $account ?></span></div>
                </div>


                <h3 class="m-2"><i class="fa-solid fa-ticket"></i>&nbsp;本期购买的彩票</h3>
                <div class="lottery-mine">
                    <?php foreach ($myTickets as $t) {    ?>
                        <div class="lottery-ticket color-background-secondary"><?php echo implode($t); ?></div>
                    <?php }
                    ?>
                </div>


                <h3 class="m-2"><i class="fa-solid fa-pizza-slice"></i>&nbsp;开奖历史</h3>

                <div class="lottery-summary m-2">
                    <canvas id="lotterySum" height="200" width="200"></canvas>
                </div>
                <table class="table color-font-default">
                    <tr>
                        <th data-toggle="tooltip" data-placement="top" title="<? echo $asc == 1 ? '升序排列' : '降序排列'; ?>">时间&nbsp;<a id="descendingBtn" href="lottery.php?asc=<? echo $asc == 1 ? '0' : '1'; ?>"><i class="fa-solid fa-sort"></i></a></th>
                        <th>内容</th>
                        <th data-toggle="tooltip" data-placement="top" title="指包括安慰奖在内的所有奖项的中奖率">胜率</th>
                        <th>彩票</th>
                    </tr>
                    <?php foreach ($history as $t) {    ?>
                        <?php $temp = explode('|', $t['summary']);
                        if ($temp[4] != "0%") {
                            $winningdata = 'class="table-warning" data-toggle="tooltip" data-placement="top" title="本期有人中了一等奖！收获50%的薯条。"';
                        }
                        if ($temp[5] != "0%") {
                            $winningdata = 'class="table-success" data-toggle="tooltip" data-placement="top" title="本期有人中了特等奖！收获90%的薯条。"';
                        }  ?>
                        <tr <?php echo $winningdata ?>>
                            <td class="lottery-time"><?php echo date("ymd", $t['time']); ?></td>
                            <td class="lottery-content"><?php echo $t['num1'] . $t['num2'] . $t['num3'] . $t['num4']; ?></td>
                            <td class="lottery-rate"><?php echo $t['rate']; ?></td>
                            <td class="lottery-rate"><?php echo $t['count']; ?></td>

                        </tr>
                    <?php
                        $winningdata = "";
                    }
                    ?>
                </table>

                <h3 class="m-2 mb-3"><i class="fa-solid fa-code"></i>&nbsp;源码</h3>
                <p><span><img src='https://img.shields.io/github/license/luccion/fandao-lottery?style=social' /></span>
                    <span><img src='https://img.shields.io/github/watchers/luccion/fandao-lottery?style=social' /></span>
                    <span><img src='https://img.shields.io/github/forks/luccion/fandao-lottery?style=social' /></span>
                </p>
                <p><b><a href="https://github.com/luccion/fandao-lottery"><i class="fa-brands fa-github"></i>&nbsp;本乐透系统源码已公开，点击此处查看</a></b></p>


            </div>

            <div class="footText d-md-block">
                <hr class="footHr" />
                <div class="footContent color-font-secondary">
                    <div>&copy; <?php echo date("Y", time()); ?> <a href="https://whiteverse.city/">whiteverse.city</a>.All rights reserved.</div>
                    <div><?php echo SITEVERSION; ?></div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="talk-bubble bubbleborder round left-in lottery-pool-bubble">
    <div class="talktext">
        <span class="pool-container">奖池：<span class="pool  color-font-danger"><?php echo $pool ?></span>🍟&nbsp;<i class="question fa-solid fa-circle-question"></i></span>
    </div>
</div>
</div>
<script src="https://cdn.bootcdn.net/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    const sctx = document.getElementById('lotterySum').getContext('2d');
    const myChart = new Chart(sctx, {
        type: 'radar',
        data: {
            labels: ['<? echo implode("','", $labels); ?>'],
            datasets: [{
                label: '出现次数',
                data: [<? echo implode(",", $data); ?>],
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                pointBackgroundColor: 'rgb(54, 162, 235)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(54, 162, 235)'
            }]
        },
        options: {
            scales: {
                r: {
                    angleLines: {
                        color: '<? echo $color ?>'
                    },
                    grid: {
                        color: '<? echo $color ?>'
                    },
                    pointLabels: {
                        font: {
                            size: 16
                        }
                    },
                    ticks: {
                        display: false
                    }
                }
            },
            elements: {
                line: {
                    borderWidth: 1
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
        },
    });
</script>
<?php include(ROOT . 'view/footer.php'); ?>
<script type="text/javascript" src="./view/js/lottery.js"></script>