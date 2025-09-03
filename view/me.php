<?php defined('ACC') || exit('ACC Denied');
require(ROOT . 'view/header.php'); ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="sticky-top">
                <div class="d-md-block row collapse p-3 shadow-sm color-background rounded" id="lmenu">
                    <nav class="nav-links" aria-label="Main navigation">
                        <div class="userinfo">
                            <div class="avatarSVG-block">
                                <a href="./me.php?cat=4">
                                    <?php
                                    $allAvatar = $TM->get_all_of_type($_SESSION['uid'], 4);
                                    echo userModel::getUserCurrentAvatarSVG($allAvatar);
                                    ?></a>
                            </div>
                            <div class="text-nickName color-font-default font-weight-bold mb-2">
                                <?php echo !$_SESSION['avatar'] ? '匿名' : $_SESSION['avatar'];  ?></div>
                            <div class="text-userName color-font-default mb-2"><i class="fa-solid fa-at"></i>
                                <?php echo $_SESSION['username'] ?></div>
                            <div class="text-date color-font-secondary">
                                注册日期：<?php echo date('Y-m-d', $_SESSION['regtime']); ?></div>
                            <div class="text-date color-font-secondary mb-2">
                                <?php echo !date('Y-m-d', $_SESSION['lastlogin']) ? "刚刚注册" : '上次登录：' . date('Y-m-d', $_SESSION['lastlogin']); ?>
                            </div>
                        </div>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link list-group-item-action color-font-grey balanceValue" href=" ./me.php?cat=5" style="padding-left: 0.7rem;">
                                    🍟<b><?php echo $money ?></b>
                                </a>
                            </li>
                            <li class="nav-item<?php echo $cat == 6 ? ' active ' : '' ?>"><a class="nav-link list-group-item-action color-font-grey " href=" ./me.php?cat=6"><i class="fa-solid fa-box"></i>&nbsp;我的资产</a></li>
                            <li class="nav-item<?php echo $cat == 1 ? ' active ' : '' ?>"><a class="nav-link list-group-item-action color-font-grey " href=" ./me.php?cat=1"><i class="fa-solid fa-message"></i>&nbsp;我发起的讨论</a></li>
                            <li class="nav-item<?php echo $cat == 2 ? ' active ' : '' ?>"><a class="nav-link list-group-item-action color-font-grey " href="./me.php?cat=2"><i class="fa-solid fa-comment-dots"></i>&nbsp;我的历史回复</a></li>
                            <li class="nav-item<?php echo $cat == 3 ? ' active ' : '' ?>"><a class="nav-link list-group-item-action color-font-grey " href="./me.php?cat=3"><i class="fa-solid fa-bookmark"></i>&nbsp;我的订阅</a></li>
                            <li class="nav-item<?php echo $cat == 4 ? ' active ' : '' ?>"><a class="nav-link list-group-item-action color-font-grey " href="./me.php?cat=4"><i class="fa-solid fa-sliders"></i>&nbsp;信息设定</a></li>
                            <div class="nav-item">
                                <label class=" list-group-item-action switch_box" for="swithLight">
                                    <input type="checkbox" class="switchLight" id="swithLight" <?php switch ($_COOKIE["theme"]) {
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
                            <li class="nav-item"><a class="nav-link list-group-item-action color-font-grey " data-toggle="modal" data-target="#logOutModal" id="logoutBtn"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;退出登录</a></li>
                        </ul>
                    </nav>
                    <div class="footText d-md-block">
                        <hr class="footHr" />
                        <div class="footContent color-font-secondary">
                            <div>&copy; <?php echo date("Y", time()); ?>&nbsp;<a class="footNote" href="https://fandao.fun">fandao.fun</a>.All rights reserved.</div>
                            <span><?php echo SITEVERSION; ?></span>
                            <div class=""><a href="https://beian.miit.gov.cn/" target="_blank">鲁ICP备16006260号-7</a></div>
                        </div>
                    </div>
                </div>
                <?php // include(ROOT . 'view/advertisement.php'); 
                ?>
            </div>
        </div>

        <div class="col-md-9 main">
            <div class="main shadow-sm p-3 color-background rounded mb-3">
                <?php
                $arr = array('threads', 'replies', 'subscribes', 'setting', 'wallet', 'property');
                require(ROOT . 'view/me_' . $arr[$cat - 1] . '.php');
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="logOutModal" tabindex="-1" aria-labelledby="logOutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logOutModalLabel"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;退出登录
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                确定要退出登录吗
            </div>
            <div class="modal-footer">
                <button type="button" class="btn color-button-secondary" data-dismiss="modal">关闭</button>
                <button type="button" class="btn  color-button-danger  logoutBtn"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;退出登录</button>
            </div>
        </div>
    </div>
</div><?php include(ROOT . 'view/footer.php'); ?>
<script src="https://cdn.bootcdn.net/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script type="text/javascript" src="./view/js/property.js"></script>