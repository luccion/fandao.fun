<?php defined('ACC') || exit('ACC Denied');

?>
<h3 class="color-font-default d-flex justify-content-between"><b><i class="fa-solid fa-person-rays"></i>&nbsp;化身</b>
    <div type="button" data-toggle="collapse" data-target="#addNewAvatar" aria-expanded="false" aria-controls="addNewAvatar" id="addNewAvatarOpen"><i class="fa-solid fa-circle-plus addAvatarBtn" clicked="0"></i></div>
</h3>
<small class="color-font-default">这是你所有的化身列表</small>
<table class="table mt-3">
    <tbody>
        <?php
        foreach ($allAvatar as $allAvatarValue) {
            $onsale = 0;
            foreach ($property as $p) {
                if ($allAvatarValue['aid'] == $p['aid'] && $p['onsale'] == 1) {
                    $onsale = 1;
                }
            }
        ?>
            <tr class="color-font-default" aid="<?php echo $allAvatarValue['aid']; ?>">
                <td class="avatarProfileImg">
                    <div class="avatarSVG">
                        <?php echo $allAvatarValue['svg']; ?></div>
                </td>
                <td><?php echo $allAvatarValue['chinese'] ?><br><?php echo $allAvatarValue['english'] ?></td>
                <!-- <td><?php echo date('Y-m-d', $allAvatarValue['createtime']) ?></td> -->
                <td class="avatarOperation">
                    <?php if ($_SESSION['current_aid'] == $allAvatarValue['aid']) { ?>
                        <small><i class="fa-solid fa-person-rays"></i><br>当前化身</small>
                    <?php } else if ($onsale == 1) {
                    ?>
                        <small><i class="fa-solid fa-tag"></i><br>已上架</small>
                    <?
                    } else { ?>
                        <div class="badge color-badge-default switchAvatar"><i class="fa-solid fa-repeat"></i>&nbsp;切换</div>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div class="collapse multi-collapse" id="addNewAvatar">
    <form>
        <div class="form-group text-left">
            <div class="color-font-default mb-2">选择新的化身 <a type="button" calss="btn color-font-default" id="refreshBtn"><i class="fa-solid fa-rotate"></i></a></div>
            <div id="avatarContainer">
                <?php
                for ($i = 0; $i < 3; $i++) {
                ?>
                    <label class="card-radio-btn">
                        <input type="radio" name="avatar" class="card-input-element d-none" value="<?php echo $i ?>" checked="" title="check<?php echo $i ?>">
                        <div class="card card-body color-background avatarSelector">
                            <div class="avatarSVG-gen"></div>
                            <div>
                                <div class="content_head color-font-default" id="avatar_chinese_<?php echo $i ?>"></div>
                                <div class="content_sub color-font-grey" id="avatar_english_<?php echo $i ?>"></div>
                            </div>
                        </div>
                    </label>
                <?php } ?>
            </div>
        </div>
        <button type="button" class="btn color-button-primary btn-block" id="selectAvatarBtn" data-toggle="modal" data-target="#selectAvatarModal">选择( 🍟-50 )</button>
    </form>
</div>
</div>
<div class="main shadow-sm p-3 color-background rounded mb-3">
    <form class="form-group" id="uuinfo">
        <br>
        <h3 class="color-font-default"><b><i class="fa-solid fa-address-card"></i>&nbsp;基础信息</b></h3>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="color-font-default" for="n"><i class="fa-solid fa-person-rays"></i>&nbsp;化身</label>
                <input id="n" type="text" class="form-control color-background-secondary" name="nickname" value="<?php echo !$_SESSION['avatar'] ? '匿名' : $_SESSION['avatar']; ?>" maxlength="50" type="text" disabled>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="color-font-default" for="e"><i class="fa-solid fa-envelope"></i>&nbsp;E-mail <small class="color-font-<? echo $_SESSION['email_verified'] ? " important" : "danger" ?>" id="emailHelp">
                        <?php if ($_SESSION['email_verified'] == 1) { ?>
                            <span class="badge color-badge-active"><i class="fa-solid fa-circle-check"></i>&nbsp;已验证</span>
                        <?php } else { ?>
                            <span class="badge color-badge-danger"><i class="fa-solid fa-circle-question"></i>&nbsp;未验证</span>
                        <?php } ?>
                    </small></label>
                <input id="e" placeholder="me@fandao.fun" type="email" class="form-control color-background-secondary" name="email" value="<?php echo $_SESSION['email']; ?>" maxlength="50" type="text" aria-describedby="emailHelp" disabled>
                <?php if ($_SESSION['email_verified'] != 1) { ?>
                    <input type="button" class="btn btn-block color-button-primary mt-2" type="button" id="verifyEmailBtn" avatar="<?php echo $_SESSION['avatar'] ?>" token="<?php echo $_SESSION['token'] ?>" username="<?php echo $_SESSION['username'] ?>">验证邮箱 <i class="fa-solid fa-circle-notch rotateit" id="veriftEmailBtnSpinner"></i></input>
                <?php } ?>
            </div>

        </div>

        <div class="form-row">
            <div class="col-md-6">
                <label for="wikiINFO" class="color-font-default"><i class="fa-solid fa-book"></i>&nbsp;白宙知识库ID <i class="fa-solid fa-circle-question" data-toggle="tooltip" data-placement="top" title="尊贵的白宙知识库档案员"></i></label>
                <? if ($_SESSION['wiki_user_name']) { ?>
                    <div class="d-flex flex-row " id="wikiINFO">
                        <div class="input-group-text color-background-secondary color-font-default wiki-info mr-2" id="wikiID" data-toggle="tooltip" data-placement="top" title="你在知识库的ID"><i class="fa-solid fa-chess-queen"></i>&nbsp;
                            <? echo $_SESSION['wiki_user_name'] ?>
                        </div>
                        <div class="input-group-text color-background-secondary color-font-default wiki-info mr-2" for="wikiID" data-toggle="tooltip" data-placement="top" title="你在知识库的贡献得分，将转化为UCT发放！"><i class="fa-solid fa-trophy"></i>&nbsp;贡献得分：
                            <? echo round(userModel::get_wiki_score($_SESSION['wiki_user_name']), 3);  ?>
                        </div>
                        <div id="wikiScoreToUCT" class="input-group-text color-font-default wiki-info wiki-charge" data-toggle="tooltip" data-placement="top" title="冲！">
                            <i class="fa-solid fa-caret-right"></i><i class="fa-solid fa-charging-station"></i>
                        </div>
                    </div>
                <? } else { ?>
                    <div class=" btn btn-block wiki-verify-btn" data-toggle="modal" data-target="#verifyWiki"><i class="fa-solid fa-link"></i>&nbsp;链接WHITEVERSE账户
                    </div>
                <? } ?>
            </div>
        </div>
        <div class="modal fade" id="verifyWiki">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content verify-content">
                    <div class="modal-body">
                        <div class="verify-head mb-2"><img src="view/images/FANDAOxWHITEVERSE.svg" /></div>
                        <hr>
                        <form role="form">
                            <div class="form-group text-left">
                                <label class="indexModalText" for="verifyNameInput">
                                    <i class="fa-solid fa-user"></i>&nbsp;whiteverse 用户名</label>
                                <input type="text" class="form-control indexModalInput" id="verifyNameInput" name="verifyName" />
                            </div>
                            <div class="form-group text-left">
                                <label class="indexModalText" for="verifyPasswordInput"><i class="fa-solid fa-key"></i>
                                    whiteverse 密码</label>
                                <input type="password" class="form-control indexModalInput" id="verifyPasswordInput" name="verifyPassword" />
                                <small id="passwordDescription" class="indexModalText form-text text-left"></small>
                            </div>
                            <div class="d-flex flex-column align-items-center mb-2">
                                <button type="button" class="btn btn-block wiki-verify-btn mb-2" id="verifyWikiBtn">
                                    <i class="fa-solid fa-user-shield"></i>&nbsp;验证
                                </button>
                                <button type="button" class="btn btn-block wiki-verify-btn" id="joinWiKi" onclick="location.href='https://wiki.whiteverse.com/index.php?title=%E7%89%B9%E6%AE%8A:%E5%88%9B%E5%BB%BA%E8%B4%A6%E6%88%B7'">
                                    <i class="fa-solid fa-user-plus"></i>&nbsp;加入 whiteverse.com
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="wikiScoreToUCTModal">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content score-content">
                    <div class="modal-body">
                        <div class="mb-3" id="score-head"><img src="view/images/convertUCT.svg" /></div>
                        <div class="score-text"><i class="fa-solid fa-trophy"></i>&nbsp;当前积分：<span id="score-current"></span></div>
                        <div class="score-text"><span id="score-status">比上次增加了</span><span id="score-diff"></span></div>
                        <div class="score-text"><span id="score-status-text">积分成功转化为UCT</span><span id="score-uct"></span></div>
                        <div class="d-flex flex-column align-items-center mb-2">
                            <button type="button" class="btn btn-block wiki-verify-btn mb-2" data-dismiss="modal">&nbsp;好</button>
                            <button type="button" class="btn btn-block wiki-verify-btn" id="joinWiKi" onclick="location.href='https://wiki.whiteverse.com/'">
                                <i class="fa-solid fa-pen-nib"></i>&nbsp;再接再厉！
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-row">
            <div class="col-md-10">
                <label for="aifadianID" class="color-font-default"><a href="https://afdian.com/a/whiteverse"><i class="fa-solid fa-bolt"></i>&nbsp;爱发电ID <i class="fa-solid fa-circle-question" data-toggle="tooltip" data-placement="top" title="前往爱发电"></i></a></label>
                <? if ($_SESSION['aifadian_id']) {
                    if ($_SESSION['exchanged_rmb'] == 0) {
                ?>
                        <div class="d-flex flex-row justify-content-start align-items-center p-1">
                            <div class="color-font-default" data-toggle="tooltip" data-placement="top" title="由于没有贡献记录在案，所以未能查询本用户其他数据"><b>
                                    用户ID:
                                    <? echo $_SESSION['aifadian_id'] ?>
                                </b>
                            </div>
                        </div>
                        <?
                    } else {
                        require('include/model/afdianModel.class.php');
                        $afdian = new Afdian(USERID, TOKEN);
                        $allSponsors = $afdian->getAllSponsors();
                        foreach ($allSponsors['data']['list'] as $o) {
                            if ($o['user']['user_id'] == $_SESSION['aifadian_id']) {
                                $aifadian_avatar = $o['user']['avatar'];
                                $aifadian_name = $o['user']['name'];
                        ?>
                                <div class="d-flex flex-row justify-content-start align-items-center p-1">
                                    <img class="aifadian-avatar mr-3" src="<? echo $aifadian_avatar ?>" title="爱发电用户头像">
                                    <div class="color-font-default"><b>
                                            <? echo $aifadian_name ?>
                                        </b>
                                    </div>
                                </div>
                    <?
                                break;
                            }
                        }
                    }
                } else { ?>
                    <br>
                    <button class="btn color-button-primary" id="verifyAfdian">验证爱发电账号&nbsp;<i class="fa-solid fa-bolt"></i></button>
                <? } ?>
            </div>
        </div>
    </form>
</div>

<div class="main shadow-sm p-3 color-background rounded mb-3">
    <form id="ccpwd" method="POST">
        <h3 class="color-font-default"><b><i class="fa-solid fa-key"></i>&nbsp;修改密码</b></h3>
        <div id="cpwd" class="alert alert-info">密码须6-16位</div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="color-font-default" for="o"><i class="fa-solid fa-asterisk"></i>&nbsp;原密码
                </label>
                <input class="form-control color-background-secondary" id="o" name="oldpass" maxlength="16" type="password">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="color-font-default" for="np"><i class="fa-solid fa-plus"></i>&nbsp;新密码
                </label>
                <input class="form-control color-background-secondary" id="np" name="newpass" maxlength="16" type="password">
            </div>

            <div class="col-md-6 mb-3">
                <label class="color-font-default" for="r"><i class="fa-solid fa-arrow-rotate-right"></i>&nbsp;重复
                </label>
                <input class="form-control color-background-secondary" id="r" maxlength="16" type="password">
            </div>

        </div>
        <div class="form-group">
            <input class="btn  color-button-primary " type="submit" value="确认修改">
        </div>
    </form>
    <div class="modal fade" id="selectAvatarModal" tabindex="-1" aria-labelledby="selectAvatarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectAvatarModalLabel"><i class="fa-solid fa-person-rays"></i>&nbsp;选择化身
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body color-font-default">
                    <div>确定要选择名为<a id="avatarNameDisplay"></a>的化身吗？</div>
                    <div>这将会消耗50x🍟</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="addNewAvatarBtn" data-dismiss="modal">就是这个了( 🍟-50
                        )</button>
                </div>
            </div>
        </div>
    </div>