<?php defined('ACC') || exit('ACC Denied');
require(ROOT . 'view/header.php');
if ($_SESSION['type'] == 4) {
    //   print_r($allTrade);
}
?>
<link href="./view/css/market.css" rel="stylesheet">
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12 main m-1 shadow-sm color-background rounded p-3 color-font-default">
            <div class="color-background market-title">
                <h2 class="mb-2 d-flex justify-content-between">
                    <div><b>饭岛市场</b></div>
                    <div class="d-flex align-items-bottom">
                        <span data-toggle="tooltip" data-placement="bottom" title="我的余额"><b><span class="color-font-default myAmount"><?php echo $account ?></span></b>🍟</span>
                        <button class="btn color-button-primary ml-3 mt-1" onclick='location.href=" ./me.php?cat=6"' data-toggle="tooltip" data-placement="bottom" title="我的资产"><i class="fa-solid fa-box"></i></button>
                    </div>
                </h2>
                <hr class="market-hr" />
            </div>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fa-solid fa-hand-spock"></i>&nbsp;欢迎！</strong> 前往<a href="me.php?cat=6"><b>我的资产</b></a>界面上架商品！<br>如有任何不解请在饭岛开发帖子中询问。
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--         <ul class="nav nav-tabs nav-fill mb-3 color-font-default">
                <li class="nav-item">
                    <a class="nav-link active" href="#">全部</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">化身</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">作品</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">道具</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">装备</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">遗物</a>
                </li>
            </ul> -->
            <div class="row row-cols-1 row-cols-md-3">
                <?php
                foreach ($allTrade as $t) {
                    switch ($t['type']) {
                        case 0: // 1=>user,
                            break;
                        case 1: // 1=>currency,
                            break;
                        case 2: // 2=>stock,
                            break;
                        case 3: // 3=>article,    
                            $nft = 1;
                            $title =  base64_decode($t['info']['title']);
                            $subtitle = "由" . $t['info']['name'] . "创作";
                            $logo = '<i class="fa-solid fa-feather p-2" data-toggle="tooltip" data-placement="left" title="作品"></i>';
                            $content = '<div class="property-article-container">' .  mb_substr(base64_decode($t['info']['content']), 0, 200) . '</div>';
                            break;
                        case 4: // 4=>avatar,
                            $nft = 1;
                            $title = $t['info']['chinese'];
                            $subtitle = $t['info']['english'];
                            $logo = '<i class="fa-solid fa-person-rays p-2" data-toggle="tooltip" data-placement="left" title="化身"></i>';
                            $content = '<div class="property-avatarSVG-block">' . $t['info']['svg'] . '</div>';
                            break;
                        case 5: // 5=>item,
                            $nft = 0;
                            $title = $t['info']['title'];
                            $subtitle = $t['info']['subtitle'];
                            $logo = '<i class="fa-solid fa-puzzle-piece p-2" data-toggle="tooltip" data-placement="left" title="物品"></i>';
                            $content = '<div class="property-item-container">' . $t['info']['svg'] . '</div>';
                            break;
                        case 6: // 6=>equipment,
                            break;
                        case 7: // 7=>artifact    
                            break;
                    }
                    $buyable = 1;
                    $saleText = '<i class="fa-solid fa-cart-shopping"></i>&nbsp;购买';
                    $description = !$nft ? $t['info']['content'] : "";
                    $prop_amount = !$nft ? ("x" . $t['amount']) : "NFT";

                    if ($t['uid'] == $_SESSION['uid']) {
                        $buyable = 0;
                        $saleText = '<i class="fa-solid fa-tag"></i>&nbsp;我上架的资产';
                    }
                    $buyBtn = $buyable ? 'primary' : 'secondary';
                    $openid = transferModel::gid2openid($t['info']['gid']);
                    /* 制作button */
                    $buttonText = 'class="stretched-link btn btn-block buyThisProperty '
                        . $btnClass . ' color-button-' . $buyBtn
                        . '" buyable="' . $buyable
                        . '" data-toggle="modal"'
                        . ' data-target="#buyModal"'
                        . '" openid="' .  $openid
                        . '" tdid="' .  $t['tdid']
                        . '" desc="' . $description
                        . '" item-type="' . $t['type']
                        . '" amount="' . $t['amount']
                        . '"';
                ?>
                    <div class="col mb-4" id="<? echo $openid ?>">
                        <div class="card color-font-default h-100 property-card shadow-sm">
                            <div class="card-header color-background-grey p-1 d-flex flex-row justify-content-between align-items-center">
                                <span class="d-flex flex-column">
                                    <span class="property-title"><?php echo $title ?></span>
                                    <span class="property-subtitle color-font-secondary"><?php echo $subtitle ?></span>
                                </span>
                                <span class="property-type-logo"><?php echo $logo ?></span>
                            </div>
                            <div class="card-body property-content color-background d-flex flex-column justify-content-center align-items-center p-0">
                                <?php echo $content ?>
                            </div>
                            <div class="property-amount-market color-font-danger"><?php echo $prop_amount ?></div>
                            <div class="card-footer color-background-grey p-1 pt-0 d-flex flex-column">
                                <div class="d-flex flex-row justify-content-between align-items-center">
                                    <div class="property-expire-time" data-toggle="tooltip" data-placement="right" title="于<? echo  date('y年m月d日H:i:s', $t['time']) ?>上架"><span class="date-to" date="<?php echo date('YmdHis', $t['expiretime']); ?>"></span>下架</div>
                                    <div class="property-current-price color-font-danger"><span class="price"><? echo $t['foramount']; ?></span><span>🍟</span></div>
                                </div>
                                <button type="button" <?php echo $buttonText ?>>
                                    <? echo $saleText ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
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


<!-- Modal -->
<div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
    <div class="modal-dialog mb-1">
        <div class="modal-content color-background color-font-default">
            <div class="modal-header">
                <h5 class="modal-title" id="buyModalLabel"><span id="propertyModalTitle">出售</span>资产"<span class="property-modal-title"></span>"</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <a class="badge color-badge-active d-none" id="propertyLink" data-toggle="tooltip" data-placement="bottom" title="前往星球" target="_blank"><i class="fa-solid fa-link"></i></a>
                <div class="property-modal-content d-flex flex-column align-items-center justify-content-center"></div>
                <div class="accordion" id="property-modal-info" openid="">
                    <div class="card color-background-secondary">
                        <div class="card-header p-0" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-block text-center color-font-default" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fa-solid fa-circle-info"></i>&nbsp;详细信息
                                </button>
                            </h2>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#property-modal-info">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center p-0">
                                <p class="property-modal-title"></p>
                                <p class="property-modal-subtitle"></p>
                                <p class="text-center small"><i class="fa-regular fa-eye" data-toggle="tooltip" data-placement="bottom" title="OPENID是每个资产独一无二的公开ID，取得该ID后可以藉此查询资产信息或传递资产。"></i>&nbsp;<span class="property-modal-openid user-select-all"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="card color-background-secondary" id="propertyHistory-card">
                        <div class="card-header p-0" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-block text-center color-font-default" id="propertyHistory" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                    <i class="fa-solid fa-timeline"></i>&nbsp;交易历史
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#property-modal-info">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center p-0">
                                <table class="table text-center mt-0" id="propertyHistoryContent">
                                    <tr>
                                        <th><i class="fa-solid fa-hand-point-up"></i></th>
                                        <th><i class="fa-solid fa-tag"></i></th>
                                        <th><i class="fa-solid fa-person-walking-arrow-right"></i></th>
                                        <th><i class="fa-solid fa-child-reaching"></i></th>
                                        <th><i class="fa-regular fa-clock"></i></th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card color-background-secondary">
                        <div class="card-header p-0" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-block text-center color-font-default" id="propertyPriceTrend" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    <i class="fa-solid fa-chart-line"></i>&nbsp;价格趋势
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#property-modal-info">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center p-0" id="trend-container">
                                <canvas id="trend" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0" id="propertyBuyModalFooter">
                <div class="w-100 mt-0 d-flex flex-column align-items-center">
                    <div class="d-flex flex-row align-items-center justify-content-center">
                        <div class="property-current-price color-font-danger"><span class="property-modal-price"></span><span>🍟</span></div>
                        <div class="property-ft-amount">&nbsp;<i class="fa-solid fa-xmark"></i></div>
                        <div class="d-none col property-ft-amount">
                            <div class="input-group">
                                <input type="number" class="form-control color-font-default color-background-secondary" id="property-buy-amount" value=1>
                                <div class="input-group-append">
                                    <span class="input-group-text color-background-secondary">/<span id="property-modal-origin-amount"></span></span>
                                </div>
                            </div>
                        </div>
                        <i class="property-ft-amount fa-solid fa-equals color-font-default"></i>
                    </div>
                    <div class="property-ft-amount property-current-price color-font-danger"><span id="property-modal-final-price"></span><span>🍟</span></div>
                    <button type="button" class="btn btn-block color-button-primary" id="buyConfirm" openid=""><i class="fa-regular fa-circle-check"></i>&nbsp;购买资产！</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-sm modal-account color-background" id="modalAccont">🍟<b><? echo $account; ?></b></div>
</div>
<script src="https://cdn.bootcdn.net/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<?php include(ROOT . 'view/footer.php'); ?>
<script type="text/javascript" src="./view/js/property.js"></script>

<script>
    $(document).ready(function() {
        $("#menuBtn").remove();
    });
</script>