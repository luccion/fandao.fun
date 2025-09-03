<div class="d-md-block row collapse p-3 shadow-sm color-background rounded" id="lmenu">
    <nav class="nav-links" aria-label="Main navigation">
        <?php foreach ($allCats as $_cat) { ?>
            <div class="nav-item"><a class="nav-link list-group-item-action color-font-grey  <?php echo $_cat['id'] == $curr_cat_id ? 'active' : ''; ?>" href="./?cat=<?php echo $_cat['id']; ?>"><?php echo str_repeat('&nbsp;', $_cat['lev'] * 2), $_cat['cat_name']; ?></a>
            </div>

        <?php } ?>
        <hr class="footHr" />
        <ul class="lmenu-grids color-font-grey clearfix">
            <li class="lmenu-grid list-group-item-action color-font-grey">
                <a class="lmenu-grid-link" href="hall.php">
                    <i class="lmenu-grid-icon fa-solid fa-landmark" style="color: #5563e5;"></i>
                    <span class="lmenu-grid-text">政务大厅</span>
                </a>
            </li>
            <li class="lmenu-grid list-group-item-action color-font-grey">
                <a class="lmenu-grid-link" href="market.php">
                    <i class="lmenu-grid-icon fa-solid fa-shop" style="color:#559ee5;"></i>
                    <span class="lmenu-grid-text">饭岛市场</span>
                </a>
            </li>
            <li class="lmenu-grid list-group-item-action color-font-grey">
                <a class="lmenu-grid-link" href="lottery.php">
                    <i class="lmenu-grid-icon fa-solid fa-ticket" style="color: #e5c555;"></i>
                    <span class="lmenu-grid-text">喜高乐透</span>
                </a>
            </li>
            <li class="lmenu-grid list-group-item-action color-font-grey">
                <a class="lmenu-grid-link" href="privacy.php">
                    <i class="lmenu-grid-icon fa-solid fa-key" style="color: #55bbe5;"></i>
                    <span class="lmenu-grid-text">关于饭岛</span>
                </a>
            </li>
            <li class="lmenu-grid list-group-item-action color-font-grey">
                <a class="lmenu-grid-link" href="summary.php">
                    <i class="lmenu-grid-icon fa-solid fa-magnifying-glass-chart" style="color: #5ac32c;"></i>
                    <span class="lmenu-grid-text">统计信息</span>
                </a>
            </li>
            <li class="lmenu-grid list-group-item-action color-font-grey">
                <a class="lmenu-grid-link" href=" ./view.php?id=<?php echo $report_tid; ?>">
                    <i class="lmenu-grid-icon fa-solid fa-bell" style="color: #d54f4f"></i>
                    <span class=" lmenu-grid-text">内容举报</span>
                </a>
            </li>
        </ul>
        <hr class="footHr" />
        <div class=" nav-item">
            <label class=" list-group-item-action switch_box" for="swithLight">
                <input type="checkbox" class="switchLight" id="swithLight" <?php switch ($_COOKIE["THEME"]) {
                                                                                case 'DARKMODE':
                                                                                    echo "";
                                                                                    break;
                                                                                case 'LIGHTMODE':
                                                                                    echo "checked='true'";
                                                                                    break;
                                                                                default:
                                                                                    echo "checked = 'true'";
                                                                            }  ?>>
                <div class="color-font-grey themeModeDescription">
                </div>
            </label>
        </div>

    </nav>

    <div class="footText d-md-block">
        <div class="footContent color-font-secondary">
            <div>&copy; <?php echo date("Y", time()); ?>&nbsp;<a class="footNote" href="https://fandao.fun">fandao.fun</a>.All rights reserved.</div>
            <span><?php echo SITEVERSION; ?></span>
            <div class=""><a href="https://beian.miit.gov.cn/" target="_blank">鲁ICP备16006260号-7</a></div>
        </div>
    </div>
</div>
<?php //include(ROOT . 'view/advertisement.php'); 
?>