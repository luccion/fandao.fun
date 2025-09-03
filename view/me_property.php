<?php defined('ACC') || exit('ACC Denied');
if ($_SESSION['type'] == 4) {
    //  print_r($property);
}
if ($_SESSION['email_verified']) { ?>
    <link href="./view/css/market.css" rel="stylesheet">
    <div class="color-background market-title">
        <h2 class="color-font-default d-flex justify-content-between mb-2"><b>我的资产</b>
            <button class="btn color-button-primary mt-1" onclick="location.href='market.php'"><i class="fa-solid fa-shop"></i>&nbsp;市场</button>
        </h2>
        <hr class="market-hr" />
    </div>

    <div class="row row-cols-1 row-cols-md-3">
        <?php
        foreach ($property as $p) {
            switch ($p['type']) {
                case 0: // 1=>user,
                    break;
                case 1: // 1=>currency,
                    break;
                case 2: // 2=>stock,
                    break;
                case 3: // 3=>article,   
                    $nft = 1;
                    $title =  base64_decode($p['title']);
                    $subtitle = "由" . $p['name'] . "创作";
                    $logo = '<i class="fa-solid fa-feather p-2" data-toggle="tooltip" data-placement="left" title="作品"></i>';
                    $content = '<div class="property-article-container">' .  mb_substr(base64_decode($p['content']), 0, 200) . '</div>';
                    break;
                case 4: // 4=>avatar,
                    $nft = 1;
                    $title = $p['chinese'];
                    $subtitle = $p['english'];
                    $logo = '<i class="fa-solid fa-person-rays p-2" data-toggle="tooltip" data-placement="left" title="化身"></i>';
                    $content = '<div class="property-avatarSVG-block">' . $p['svg'] . '</div>';
                    break;
                case 5: // 5=>item,
                    $nft = 0;
                    $title = $p['title'];
                    $subtitle = $p['subtitle'];
                    $logo = '<i class="fa-solid fa-puzzle-piece p-2" data-toggle="tooltip" data-placement="left" title="物品"></i>';
                    $content = '<div class="property-item-container">' . $p['svg'] . '</div>';
                    break;
                case 6: // 6=>equipment,
                    break;
                case 7: // 7=>artifact    
                    break;
                case 8: //8=>planet
                    $nft = 1;
                    $title = $p['chinese'];
                    $subtitle = $p['english'];
                    $logo = '<i class="fa-solid fa-earth-europe p-2" data-toggle="tooltip" data-placement="left" title="星球"></i>';
                    $content = '<div class="property-avatarSVG-block">' . $p['svg'] . '</div>';
                    break;
                    break;
            }
            $sellable = 1;
            $onsaleMark = 0;
            $sellBtn = 'primary';
            $dataTarget = "#sellModal";
            $btnClass = "sellThisProperty";
            $saleText = '<i class="fa-solid fa-scale-balanced"></i>&nbsp;上架';
            $ribbon = "";
            $description = !$nft ? $p['content'] : "";
            $prop_amount = !$nft ? ("x" . $p['amount']) : "NFT";

            if ($p['onsale']) {         //商品已上架              
                $onsaleMark = 1;
                $sellBtn = 'danger';
                $saleText = '<i class="fa-solid fa-tag"></i>&nbsp;已上架' . (!$nft ? '(x' . $p['onsale'] . ')' : '');
                $dataTarget = "#closeModal";
                $btnClass = "closeThisProperty";
                $ribbon = '<div class="ribbon"><div class="ribbon1 color-button-primary">ONSALE</div></div>';
            } else if ($_SESSION['current_aid'] == $p['aid']) {     //商品被占用
                $sellable = 0;
                $sellBtn = 'secondary';
                $saleText = '<i class="fa-solid fa-user-lock"></i>&nbsp;占用';
                $btnClass = "sellThisProperty unlockThisProperty";
            }

            /* 制作button */
            $buttonText = 'class="stretched-link btn btn-block '
                . $btnClass . ' color-button-' . $sellBtn
                . '" sellable="' . $sellable
                . '" data-toggle="modal"'
                . ' data-target="' . $dataTarget
                . '" openid="' . transferModel::gid2openid($p['gid'])
                . '" desc="' . $description
                . '" item-type="' . $p['type']
                . '" amount="' . ($p['amount'] ?: 1)
                . '"';
        ?>
            <div class="col mb-4">
                <div class="card color-font-default h-100 property-card shadow-sm">
                    <? echo $ribbon ?>
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
                    <div class="property-amount color-font-danger"><?php echo $prop_amount ?></div>
                    <div class="card-footer color-background-grey p-1 d-flex justify-content-between align-items-center">
                        <button type="button" <?php echo $buttonText ?>>
                            <? echo $saleText ?>
                        </button>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
    <? if ($_SESSION['type'] == 4) { ?>
        <style>
            .new-item-textarea {
                width: 49%;
                height: 8rem;
            }
        </style>
        <div class="main shadow-sm p-3 color-background rounded mb-3">
            <div id="newItem">
                <h3 class="color-font-default mb-3"><i class="fa-solid fa-snowflake"></i>&nbsp;创建新物品 COMMOM ITEM</h3>
                <div class="input-group mt-3 mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text textPrepend color-background-secondary color-font-default" for="newItemTitle">标题*</label>
                    </div>
                    <input type="text" class="form-control color-background-secondary color-font-default" id="newItemTitle" name="title" maxlength="255" placeholder="必填">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text textPrepend color-background-secondary color-font-default" for="newItemSubtitle">副标题</label>
                    </div>
                    <input type="text" class="form-control color-background-secondary color-font-default" id="newItemSubtitle" name="subtitle" maxlength="255" placeholder="可选，一般为英文，空则为主标题">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text textPrepend color-background-secondary color-font-default" for="newItemPrice">价格</label>
                    </div>
                    <input type="number" class="form-control color-background-secondary color-font-default" id="newItemPrice" name="subtitle" maxlength="255" placeholder="DEFAULT:1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text textPrepend color-background-secondary color-font-default" for="newItemRarity">稀有度</label>
                    </div>
                    <input type="number" class="form-control color-background-secondary color-font-default" id="newItemRarity" name="subtitle" maxlength="255" placeholder="DEFAULT:1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text textPrepend color-background-secondary color-font-default" for="newItemAmount">发行量</label>
                    </div>
                    <input type="number" class="form-control color-background-secondary color-font-default" id="newItemAmount" name="subtitle" maxlength="255" placeholder="DEFAULT:1">
                </div>
                <textarea class="color-background-secondary color-font-default new-item-textarea" name="content" id="newItemContent" placeholder="CONTENT(DESCRIPTION)"></textarea>
                <textarea class="color-background-secondary color-font-default new-item-textarea" name="svg" id="newItemSvg" placeholder="SVG"></textarea>
                <input class="btn btn-block color-button-primary " id="newItemBtn" type="button" value="创建" ifmodal="false">
            </div>
        </div>
    <? } ?>


    <div class="modal fade" id="sellModal" tabindex="-1" aria-labelledby="sellModalLabel" aria-hidden="true">
        <div class="modal-dialog mb-1">
            <div class="modal-content color-background color-font-default">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellModalLabel"><span id="propertyModalTitle">出售</span>资产"<span class="property-modal-title"></span>"</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <badge class="badge color-badge-danger" id="propertyDecompose" data-toggle="tooltip" data-placement="bottom" title="分解资产！"><i class="fa-solid fa-arrows-to-dot"></i></badge>
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
                    <div id="propertyInputContainer">
                        <div class="row">
                            <div class="col">
                                <label for="property-modal-price" class="col-form-label">定价<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-placement="bottom" title="单价"></i>：<span class="color-font-danger" id="property-modal-price-label"></span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control color-font-default color-background-secondary" id="property-modal-price">
                                    <div class="input-group-append">
                                        <span class="input-group-text color-background-secondary">x🍟</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-none col" id="propertyAmountInputContainer">
                                <label for="property-modal-amount" class="col-form-label">数量：<span class="color-font-danger" id="property-modal-amount-label"></span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control color-font-default color-background-secondary" id="property-modal-amount" value=1>
                                    <div class="input-group-append">
                                        <span class="input-group-text color-background-secondary">/<span id="property-modal-origin-amount"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="property-modal-fee" class="col-form-label">手续费<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-placement="bottom" title="饭岛基金会将收取价格5%的手续费用于服务维护"></i>：</label>
                        <div class="input-group">
                            <input class="form-control color-font-default color-background-secondary" id="property-modal-fee" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text color-background-secondary">x🍟</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="propertySellModalFooter">
                    <button type="button" class="btn color-button-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn color-button-primary" id="sellConfirm" balance="<? echo $money; ?>" openid=""><i class="fa-regular fa-circle-check"></i>&nbsp;上架！</button>
                </div>
            </div>
        </div>
        <div class="d-sm modal-account color-background" id="modalAccont">🍟<b><? echo $money; ?></b></div>
    </div>

    <div class="modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content color-background color-font-default">
                <div class="modal-header">
                    <h5 class="modal-title" id="closeModalLabel">商品下架</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body d-flex flex-column justify-content-center align-items-center m-1">
                    <p><i class="fa-solid fa-shop" id="shoplogo"></i><i class="fa-solid fa-person-walking-luggage" id="personluggage"></i></p>
                    <p class="mb-1">确定要将这件商品提前下架吗？</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn color-button-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn color-button-danger" id="closeConfirm"><i class="fa-regular fa-circle-check" openid=""></i>&nbsp;下架！</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="decomposePanel" tabindex="-1" aria-labelledby="decomposePanelTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content color-background color-font-default">
                <div class="modal-header">
                    <h5 class="modal-title" id="decomposePanelTitle">分解资产</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">分解资产以获得薯条</p>
                    <div class="property-current-price color-font-danger text-left"><span class="property-modal-price" id="estimationPrice"></span><span>🍟</span></div>
                    <p>资产分解后将永远消失！</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn color-button-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn color-button-danger" id="decomposeConfirmBtn" openid="">确认分解</button>
                </div>
            </div>
        </div>
    </div>

<?php } else { ?>
    <h2><i class=" fa-solid fa-face-frown"></i>&nbsp;因为没有验证邮箱所以不能使用资产功能~</h2>
    <small>前往<strong>信息设定</strong>以验证邮箱<br></small>
    </div>
<?php } ?>