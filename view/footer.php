<div class="modal fade" id="painter" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="painter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content color-background shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title color-font-default"><i class="fa-solid fa-paintbrush"></i>&nbsp;绘图板</h5><small class="color-font-secondary"><i class="fa-brands fa-chrome"></i>&nbsp;目前仅支持chrome内核浏览器</small>
            </div>
            <div class="modal-body">
                <div id="bcPaint">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn color-button-secondary" id="UNDO"><i class="fa-solid fa-rotate-left"></i>&nbsp;撤销</button>
                <button type="button" class="btn color-button-danger" id="bcPaint-reset"><i class="fa-solid fa-eraser"></i>&nbsp;清空</button>
                <button type="button" class="btn color-button-secondary" data-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i>&nbsp;取消</button>
                <button type="button" class="btn color-button-primary" id="bcPaint-export" data-dismiss="modal"><i class="fa-solid fa-upload"></i>&nbsp;上传</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addLinkModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="link" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content color-background shadow-sm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="addLinkLink">指向链接</label>
                    <input type="url" class="form-control" id="addLinkLink" placeholder="http://...">
                </div>
                <div class="form-group">
                    <label for="addLinkDesc">显示文本</label>
                    <input type="text" class="form-control" id="addLinkDesc" placeholder="链接...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn color-button-secondary" data-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i>&nbsp;取消</button>
                <button type="button" class="btn color-button-primary" id="addLinkConfirm" data-dismiss="modal"><i class="fa-solid fa-link"></i>&nbsp;插入链接</button>
            </div>
        </div>
    </div>
</div>



<?php if (!$isLogin) { ?>
    <div class="modal fade" id="loginModal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content color-background">
                <div class="modal-header">
                    <h4 class="modal-title color-font-default"><i class="fa-solid fa-right-to-bracket"></i>&nbsp;请登录</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" class="login-form">
                        <div class="form-group text-left">
                            <label class="color-font-default" for="userNameInput">用户名</label>
                            <input type="text" id="userNameInput" class="color-background-secondary color-font-default form-control username" name="username" placeholder="输入用户名" maxlength="16" />
                        </div>
                        <div class="form-group text-left">
                            <label class="color-font-default" for="userPasswordInput">密码</label>
                            <small class="text-right color-font-default" data-toggle="tooltip" data-placement="right" title="那就给codd@whiteverse.com发一封邮件吧">
                                忘记了？<i class="fa-solid fa-face-dizzy"></i></small>
                            <input type="password" class="form-control password" name="password" placeholder="输入密码" maxlength="16" />
                            <small id="passwordDescription" class="form-text color-font-default text-right">看不见</small>
                        </div>
                        <input type="button" class="btn color-button-primary btn-block" value="登陆" id="loginBtnConfirm">
                        <div class="text-center chooseThisOrThat color-font-secondary"><i class="fa-solid fa-arrow-down-up-across-line"></i></div>
                        <input type="button" class="btn color-button-primary btn-block" value="注册" data-dismiss="modal" data-toggle="modal" data-target="#signupModal">
                    </form>
                </div>
                <div class="modal-footer">
                    <small>
                        <p class="footNote color-font-default">
                            ©
                            <a class="footNote" href="https://fandao.fun">fandao.fun</a>
                            All Rights Reserved
                        </p>
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="signupModal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content color-background">
                <div class="modal-header">
                    <h4 class="modal-title color-font-default"><i class="fa-solid fa-user-plus"></i>&nbsp;请注册</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" class="reg-form">
                        <div class="form-group text-left user">
                            <label class="color-font-default" for="userNameInputReg">用户名</label>
                            <input type="text" id="userNameInputReg" class="color-background-secondary color-font-default form-control usernameReg" name="username" placeholder="长度2-15个字符，允许英文,数字,-和_" maxlength="100" />
                        </div>
                        <div class="form-group text-left email">
                            <label class="color-font-default" for="emailInput">邮箱</label>
                            <input type="text" id="emailInput" class="color-background-secondary color-font-default form-control emailReg" name="email" placeholder="me@whiteverse.city" maxlength="100" aria-describedby="emailHelp" />
                            <small id="emailHelp" class="form-text color-font-grey text-right">邮箱仅用于验证注册，我们不会与其他人分享。</small>
                        </div>
                        <div class="form-group text-left">
                            <div class="color-font-default mb-2">选择你的化身 <a type="button" calss="btn color-font-default" id="refreshBtn"><i class="fa-solid fa-rotate"></i></a></div>
                            <div id="avatarContainer">
                                <?php
                                for ($i = 0; $i < 3; $i++) {
                                ?>
                                    <label class="card-radio-btn">
                                        <input type="radio" name="avatar" class="card-input-element d-none" value="<?php echo $i ?>" checked="">
                                        <div class="card card-body color-background avatarSelector">
                                            <div class="avatarSVG-gen"></div>
                                            <div>
                                                <div class="content_head color-font-default" id="avatar_chinese_<?php echo $i ?>"></div>
                                                <div class="content_sub color-font-grey" id="avatar_english_<?php echo $i ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group text-left pwd">
                            <label class="color-font-default" for="userPasswordInput">密码</label>
                            <input type="password" class="form-control passwordReg color-font-default color-background-secondary" name="password" placeholder="输入密码" maxlength="16" />
                        </div>
                        <div class="form-group text-left">
                            <input type="password" class="form-control repeatReg color-font-default color-background-secondary" placeholder="重复密码" maxlength="16" />
                            <small id="passwordDescriptionReg" class="form-text color-font-grey text-right">设置密码，6-16位</small>
                        </div>
                        <input type="button" class="btn  color-button-primary  btn-block" value="注册" id="signupBtnConfirm">
                        <small id="signupBtnConfirmDescriptionReg" class="form-text color-font-grey text-right">点击注册即表示您认可饭岛的<a class="text-decoration-underline color-theme" href="terms.php">用户协议</a>和<a class="text-decoration-underline color-theme" href="privacy.php#privacy">隐私政策</a>。</small>
                    </form>
                </div>
                <div class="modal-footer">
                    <small>
                        <p class="footNote color-font-default">
                            ©
                            <a class="footNote" href="https://fandao.fun">fandao.fun</a>
                            All Rights Reserved
                        </p>
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content color-background">
                <div class="modal-header">
                    <h5 class="modal-title color-font-default" id="alertLabel">错误</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="color-font-default alertModalMsg">在注册新用户前，请先退出登录。</p>
                </div>
                <div class="modal-footer alertModalFooter">
                    <button type="button" class="btn color-button-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn color-button-danger  logoutBtn">退出登录</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="signupCompleteModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="signupComplete" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content color-background">
                <div class="modal-header">
                    <h5 class="modal-title color-font-default" id="signupComplete">注册成功！</h5>
                </div>
                <div class="modal-body mb-3">
                    <div id="loadingIcons" class="color-font-default">
                        <i class="fa-solid fa-address-card fa-bounce" style=" --fa-bounce-start-scale-x: 1; --fa-bounce-start-scale-y: 1; --fa-bounce-jump-scale-x: 1; --fa-bounce-jump-scale-y: 1; --fa-bounce-land-scale-x: 1; --fa-bounce-land-scale-y: 1; "></i>
                    </div>
                    <div class="progress">
                        <div id="preparingLoadingBar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small id="loadingDesc" class="form-text color-font-default color-font-secondary">正在帮你填写申请表</small>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
<div class="modal fade" id="eventAlertModal" tabindex="-1" aria-labelledby="alertLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content color-background">
            <div class="modal-header">
                <h5 class="modal-title color-font-default" id="alertLabel">哎呀</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="color-font-default" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <p class="color-font-default eventAlertModalMsg"></p>
            </div>
            <div class="modal-footer alertModalFooter">
                <button type="button" class="btn color-button-secondary eventAlertCancelBtn" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
<script type="text/javascript" src="view/js/globalDefines.js"></script>
<script type="text/javascript" src="view/js/functions.js"></script>
<script type="text/javascript" src="view/js/control.js"></script>
<!--

          █████╗ ██████╗ ████████╗
          ██╔══██╗██╔══██╗╚══██╔══╝
          ███████║██████╔╝   ██║   
          ██╔══██║██╔══██╗   ██║   
          ██║  ██║██║  ██║   ██║   
          ╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   
                                                                    
              ██████╗ ██╗   ██╗                          
              ██╔══██╗╚██╗ ██╔╝                          
              ██████╔╝ ╚████╔╝                           
              ██╔══██╗  ╚██╔╝                            
              ██████╔╝   ██║                             
              ╚═════╝    ╚═╝                             
                                           
  ██╗     ██╗   ██╗███╗   ██╗ ██████╗██╗  ██╗
  ██║     ██║   ██║████╗  ██║██╔════╝██║  ██║
  ██║     ██║   ██║██╔██╗ ██║██║     ███████║
  ██║     ██║   ██║██║╚██╗██║██║     ██╔══██║
  ███████╗╚██████╔╝██║ ╚████║╚██████╗██║  ██║
  ╚══════╝ ╚═════╝ ╚═╝  ╚═══╝ ╚═════╝╚═╝  ╚═╝
Lunch© & Whiteverse™ All Rights Reserved <?php echo date("Y", time()); ?>
-->

</body>

</html>