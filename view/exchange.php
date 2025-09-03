<?
defined('ACC') || exit('ACC Denied');
require(ROOT . 'view/header.php'); ?>
<link href="./view/css/exchange.css" rel="stylesheet">
<div class="balance-head d-flex flex-row justify-content-between p-1 m-1">
    <div id="currentTokenTypeName">STOCK</div>
    <div class="balance-container d-flex flex-row justify-content-around">
        <div class="balance-content mr-3">
            🍟&nbsp;<span class="balance-content-value" id="current_frenchfries">5</span>
        </div>
        <div class="balance-content mr-3">
            UCT&nbsp;<span class="balance-content-value" id="current_frenchfries">5</span>
        </div>
        <div class="balance-content">
            FANDAO&nbsp;<span class="balance-content-value" id="current_frenchfries">5%</span>
        </div>
    </div>
</div>
<hr>
<ul class="nav nav-tabs nav-fill mb-3 color-font-default">
    <li class="nav-item">
        <a class="nav-link active" href="#">🍟&nbsp;<i class="fa-solid fa-arrow-right-arrow-left"></i>&nbsp;UCT</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">UCT&nbsp;<i class="fa-solid fa-arrow-right-arrow-left"></i>&nbsp;FANDAO</a>
    </li>
</ul>
<div id="chartContainer"><canvas id="chartHistory"></canvas></div>
<div class="current-price-container d-flex flex-row justify-content-center">
    <span id="currentPriceValue">15</span><span class="left-token">🍟</span><i class="fa-solid fa-arrow-right-arrow-left"></i>1&nbsp;<span class="right-token">UCT</span>
</div>
<hr>
<div class="order-container row d-flex flex-row justify-content-center m-3">
    <div class="col col-md-3 d-flex flex-column">
        <div class="order-title"><span class="left-token">🍟</span><i class="fa-solid fa-right-long"></i><span class="right-token">UCT</span></div>
        <div class="order-content">
            <table class="table">
                <tbody>
                    <tr>
                        <td class="left-amount">15</td>
                        <td class="right-amount">1</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col col-md-3 d-flex flex-column">
        <div class="order-title"><span class="left-token">🍟</span><i class="fa-solid fa-left-long"></i><span class="right-token">UCT</span></div>
        <div class="order-content">
            <table class="table">
                <tbody>
                    <tr>
                        <td class="left-amount">5</td>
                        <td class="right-amount">1</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr>
<div class="d-flex flex-column">
    <div class="text-center"><b>我的委托</b></div>
    <div class="order-container row d-flex flex-row justify-content-center m-3">
        <div class="col col-md-3 d-flex flex-column">
            <div class="input-group">
                <input type="number" class="form-control color-font-default color-background-secondary" id="" value=1>
                <div class="input-group-append">
                    <span class="input-group-text color-background-secondary"><span class="left-token">🍟</span></span>
                </div>
            </div>
        </div>
        <div class="col col-md-3 d-flex flex-column">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text color-background-secondary">每<span class="right-token">UCT</span></span>
                </div>
                <input type="number" class="form-control color-font-default color-background-secondary" id="" value=1>
                <div class="input-group-append">
                    <span class="input-group-text color-background-secondary">个</span>
                </div>
            </div>
        </div>
    </div>
    <div class="button-container row d-flex flex-row justify-content-center m-1">
        <div class="d-flex flex-row justify-content-center align-items-center col-md-6">
            <button class="btn btn-block color-button-primary mt-1 mr-3" data-toggle="tooltip" data-placement="bottom" title="提交买入委托">以&nbsp;<span id="forLeftAmount">15</span>&nbsp;<span class="left-token">🍟</span>&nbsp;<b>买入</b>&nbsp;<span id="forRightAmount">1</span>&nbsp;<span class="right-token">UCT</span></button>
            <button class="btn btn-block color-button-primary mt-1" data-toggle="tooltip" data-placement="bottom" title="提交卖出委托">以&nbsp;<span id="forLeftAmount">15</span>&nbsp;<span class="left-token">🍟</span>&nbsp;<b>卖出</b>&nbsp;<span id="forRightAmount">1</span>&nbsp;<span class="right-token">UCT</span></button>
        </div>
    </div>
</div>
<script src="https://cdn.bootcdn.net/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<?php include(ROOT . 'view/footer.php'); ?>

<script src="./view/js/exchange.js"></script>