/* start of control */
$(function () {
  $('#menuBtn').click(() => {
    if (menuBtnClicked == 0) {
      $('#menuBtn').css('transform', 'rotate(90deg)');
      menuBtnClicked = 1;
      $('#lmenu').collapse('show');
    } else {
      $('#menuBtn').css('transform', 'rotate(0deg)');
      menuBtnClicked = 0;
      $('#lmenu').collapse('hide');
    }
  });
  $('[data-toggle="popover"]').popover();
  $('.btnVote').popover('show');
  /* quoteview start */
  $('.quoteview').mouseleave(function () {
    $(this).stop().fadeOut(250);
  });
  $('.quote').mouseenter(function (ev) {
    var e = ev;
    window.ctop = e.pageY;
    window.cleft = e.pageX;
    var re = />>(\d+)(?:>(\d+))?/,
      res = re.exec(this.innerText),
      tid = res[1],
      floor = typeof res[2] == 'undefined' ? 0 : res[2],
      newdiv = document.createElement('div');

    var cur_discus = $('.discussion[tid=' + tid + ']');
    if (cur_discus.length > 0) {
      var tmp_html = cur_discus.find('[floor=' + floor + ']').html();
    }
    if (typeof tmp_html == 'undefined') {
      var tmp_html = view(tid, floor);
    }
    newdiv.setAttribute('class', 'reply');
    newdiv.setAttribute('style', 'margin:0');
    newdiv.innerHTML = tmp_html;
    $('.quoteview').html('');
    $('.quoteview').append(newdiv);
    $('.quoteview').css('top', ctop).css('left', cleft).stop().fadeIn(250);
  });
  $('.quote').mouseleave(function (ev) {
    var e = ev;
    top = e.pageY;
    left = e.pageX;
    if (top < window.ctop || left < window.cleft) {
      $('.quoteview').mouseleave();
    }
  });
  /* quoteview end */

  /* profileview start */
  $('.profileview').mouseleave(function () {
    $(this).stop().fadeOut(250);
  });
  $('.avatarSVG').mouseenter(function (ev) {
    var e = ev;
    window.ctop = e.pageY;
    window.cleft = e.pageX;
    svg = $(this).html();
    profileName = $(this).next().html();
    $('.profileview').children('.profileName').html('');
    $('.profileview').children('.avatarSVG-show').html('');
    $('.profileview').children('.avatarSVG-show').html(svg);
    $('.profileview').children('.profileName').html(profileName);
    $('.profileview').css('top', ctop).css('left', cleft).stop().fadeIn(250);
  });
  $('.avatarSVG').mouseleave(function (ev) {
    var e = ev;
    top = e.pageY;
    left = e.pageX;
    if (top < window.ctop || left < window.cleft) {
      $('.profileview').mouseleave();
    }
  });
  /* profileview end */

  $('#fileUploader,#fileUploaderModal').on('change', function () {
    var fileName = $(this).val();
    fileName = fileName.replace('C:\\fakepath\\', '');
    $(this).next('.custom-file-label').html(fileName);
  });
  $('#uploadIMG').click(function () {
    // 开始上传
    $.ajaxFileUpload({
      secureuri: false, // 是否启用安全提交，默认为 false
      type: 'POST',
      url: path_http + 'upload.php',
      fileElementId: 'fileUploader', // input[type=file] 的 id
      dataType: 'text', // 返回值类型，一般位 `json` 或者 `text`
      async: true, //是否是异步
      success: function (data) {
        var a = JSON.parse(data);
        $('#fileUploader').next('.custom-file-label').html(text_upload_success);
        var fileNameBlock = '[[img:' + a.file_name + ']]';
        $('#content').insertAtCursor(fileNameBlock);
      },
      error: function (data) {
        $('#fileUploader').next('.custom-file-label').html(text_upload_fail);
        console.log(data);
      },
    });
  });
  $('#uploadIMGModal').click(function () {
    //Modal
    $.ajaxFileUpload({
      secureuri: false,
      type: 'POST',
      url: path_http + 'upload.php',
      fileElementId: 'fileUploaderModal',
      dataType: 'text',
      async: true,
      success: function (data) {
        let a = JSON.parse(data);
        $('#fileUploaderModal').next('.custom-file-label').html(text_upload_success);
        var fileNameBlock = '[[img:' + a.file_name + ']]';
        $('#contentModal').insertAtCursor(fileNameBlock);
      },
      error: function (data, e) {
        console.log(e + data);
      },
    });
  });

  /* 绘图板 */
  $('#bcPaint').bcPaint();
  $('#painter').on('hidden.bs.modal', function () {
    $('#body').attr('class', 'modal-open');
  });
  $('body').on('click', '.bcPaint-palette-color', function () {
    $(this).parent().find('.selected').removeClass('selected');
    $(this).addClass('selected');
    $.fn.bcPaint.setColor($(this).css('background-color'));
  });
  $('body').on('click', '#bcPaint-reset', function () {
    $.fn.bcPaint.clearCanvas();
  });
  $('input[name=strokeRange]').change(function () {
    $.fn.bcPaint.setStroke($(this).val());
  });
  $('#bcPaint-export').click(function () {
    var baseImg = $.fn.bcPaint.export();
    $.post(path_http + 'upload_base64.php', { imgBase64: baseImg }, function (data) {
      let a = JSON.parse(data);
      var fileNameBlock = '[[doodle:' + a.file_name + ']]';
      $('#content').insertAtCursor(fileNameBlock);
      $('#contentModal').insertAtCursor(fileNameBlock);
    });
  });
  $('#UNDO').click(function () {
    $.fn.bcPaint.undo();
  });

  /* dice控制 */
  $('#addDice').click(() => {
    $('#content').insertAtCursor(text_dice);
  });
  $('#addDiceModal').click(() => {
    $('#contentModal').insertAtCursor(text_dice);
  });
  /* link控制 */
  $('#addLinkBtn,#addLinkBtnModal').click(() => {
    $('#addLinkModal').modal('show');
  });

  $('#addLinkConfirm').click(() => {
    let url = $('#addLinkLink').val(),
      desc = $('#addLinkDesc').val();
    if (desc) {
      desc = '|' + desc;
    }
    content = '[[' + url + desc + ']]';
    $('#content').insertAtCursor(content);
    $('#contentModal').insertAtCursor(content);
  });

  /* 颜文字双层替换 */
  $('#textface').change(() => {
    let face = $('#textface').val();
    $('#content').insertAtCursor(face);
  });
  $('#textfaceModal').change(() => {
    let face = $('#textfaceModal').val();
    $('#contentModal').insertAtCursor(face);
  });
  /* popper.js统一控制 */
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  /* 切换主题 */
  switch (getCookie('THEME')) {
    case 'DARKMODE':
      $('.themeModeDescription').html(text_theme_darkmode);
      $('.switchLight').removeAttr('checked');
      break;
    case 'LIGHTMODE':
      $('.themeModeDescription').html(text_theme_lightmode);
      break;
    default:
      $('.themeModeDescription').html(text_theme_lightmode);
  }
  $('.switchLight').click(() => {
    var ifChecked = $('.switchLight').is(':checked');
    if (!ifChecked) {
      $('#theme-link').attr('href', path_css + 'dark-theme.css');
      $('.themeModeDescription').html(text_theme_darkmode);
      document.cookie = 'THEME=DARKMODE';
    } else {
      $('#theme-link').attr('href', path_css + 'light-theme.css');
      $('.themeModeDescription').html(text_theme_lightmode);
      document.cookie = 'THEME=LIGHTMODE';
    }
  });

  /* 登出按钮 */
  $('.logoutBtn').click(() => {
    console.log('logout');
    $.get('logout.php', () => {
      window.location.href = 'index.php';
    });
  });
  $('.refreshBtn').click(() => {
    location.reload();
  });
  $('#hall').click(() => {
    $('.col-md-9').load(path_view + 'hall.php');
  });

  /* 订阅按钮📫 */
  $('.subscribeBtn').click(() => {
    var clicked = $('.subscribeBtn').attr('clicked'),
      tid = $('.discussion').attr('tid');
    $.post(path_http + 'subscribe.php', { tid: tid }, () => {
      if (clicked == 0) {
        $('.subscribeBtn').html(text_unsubscribe);
        $('.subscribeBtn').attr('clicked', 1).removeClass('color-badge-default').addClass('color-badge-active');
      } else {
        $('.subscribeBtn').html(text_subscribe);
        $('.subscribeBtn').attr('clicked', 0).removeClass('color-badge-active').addClass('color-badge-default');
      }
    });
  });
  /* 喜欢PO按钮👍 */
  var currentLikeNum = $('.likeBtn').html() || text_thumbsUpIcon;
  currentLikeNum = parseInt(currentLikeNum.replace(text_thumbsUpIcon, ''));
  $('.likeBtn').click(() => {
    var clicked = $('.likeBtn').attr('clicked'),
      tid = $('.discussion').attr('tid');
    $.post(path_http + 'likeThread.php', { tid: tid }, () => {
      if (clicked == 0) {
        currentLikeNum += 1;
        $('.likeBtn').html(text_thumbsUpIcon + currentLikeNum);
        $('.likeBtn').attr('clicked', 1).removeClass('color-badge-default').addClass('color-badge-active');
      } else {
        currentLikeNum -= 1;
        $('.likeBtn').html(text_thumbsUpIcon + currentLikeNum);
        $('.likeBtn').attr('clicked', 0).removeClass('color-badge-active').addClass('color-badge-default');
      }
    });
  });
  /* NEW PO */
  $('.newDiscussBtn').click(() => {
    repo = text_post;
    var catagory = $('.newDiscussCatagory').val(),
      title = $('.newDiscussTitle').val(),
      content = $('.newDiscussContent').val();
    $.post(
      path_http + 'newdiscus.php',
      {
        title: title,
        content: content,
        cat: catagory,
      },
      (data) => {
        newREPOAlert(repo, data);
      }
    );
  });
  $('.newDiscussBtnModal').click(() => {
    repo = text_post;
    var catagory = $('.newDiscussCatagoryModal').val(),
      title = $('.newDiscussTitleModal').val(),
      content = $('.newDiscussContentModal').val();
    $.post(
      path_http + 'newdiscus.php',
      {
        title: title,
        content: content,
        cat: catagory,
      },
      (data) => {
        newREPOAlert(repo, data);
      }
    );
  });
  /* NEW RE */
  $('.newReplyBtn').click(() => {
    repo = text_reply;
    var tid = $('.newReplyTid').val(),
      content = $('.newReplyContent').val();
    $.post(
      path_http + 'newreply.php',
      {
        tid: tid,
        content: content,
      },
      (data) => {
        newREPOAlert(repo, data);
      }
    );
  });
  $('.newReplyBtnModal').click(() => {
    repo = text_reply;
    var tid = $('.newReplyTidModal').val(),
      content = $('.newReplyContentModal').val();
    $.post(
      path_http + 'newreply.php',
      {
        tid: tid,
        content: content,
      },
      (data) => {
        newREPOAlert(repo, data);
      }
    );
  });

  /* NEW PROPOSAL */
  $('.newProposalBtn').click(() => {
    var title = $('#newProposalTitle').val(),
      content = $('#newProposalContent').val(),
      repo = text_reply;
    if (!title) {
      $('.eventAlertModalMsg').html(text_empty_title);
      $('#eventAlertModal').modal('show');
    }
    if (!content) {
      $('.eventAlertModalMsg').html(text_empty_content);
      $('#eventAlertModal').modal('show');
    }
    $.post(
      path_http + 'newproposal.php',
      {
        title: title,
        content: content,
      },
      function (data) {
        newREPOAlert(repo, data);
      }
    );
  });
  /* PROPOSAL PRO&CON */
  $('.proposalVoteBtn').on('click', function () {
    /* 与常规函数相比，箭头函数对 this 的处理也有所不同。
  简而言之，使用箭头函数没有对 this 的绑定。 
  所以不能使用()=>{}。*/
    var pid = $(this).parent().parent().parent().attr('pid'),
      clicked = parseInt($(this).attr('clicked')),
      clicked_theOther = parseInt($(this).parent().siblings().children('.btnButton').attr('clicked')),
      position = parseInt($(this).attr('voteFor'));
    currentVoteNum = parseInt($(this).siblings().html());
    currentVoteNum_theOther = parseInt($(this).parent().siblings().children('.btnNumber').html());
    var situation = '' + [clicked, clicked_theOther, position];

    switch (situation) {
      case '0,0,0': //未投，投con
        currentVoteNum += 1;
        $(this).siblings().html(currentVoteNum).removeClass('color-button-secondary').addClass('color-button-danger');
        $(this).attr('clicked', 1).removeClass('color-button-secondary').addClass('color-button-danger');
        break;
      case '0,0,1': //未投，投pro
        currentVoteNum += 1;
        $(this).siblings().html(currentVoteNum).removeClass('color-button-secondary').addClass('color-button-primary');
        $(this).attr('clicked', 1).removeClass('color-button-secondary').addClass('color-button-primary');
        break;
      case '0,1,0': //投过pro，改投con
        currentVoteNum += 1;
        $(this).siblings().html(currentVoteNum).removeClass('color-button-secondary').addClass('color-button-danger');
        $(this).attr('clicked', 1).removeClass('color-button-secondary').addClass('color-button-danger');
        currentVoteNum_theOther -= 1;
        $(this)
          .parent()
          .siblings()
          .children('.btnNumber')
          .html(currentVoteNum_theOther)
          .removeClass('color-button-primary')
          .addClass('color-button-secondary');
        $(this)
          .parent()
          .siblings()
          .children('.btnButton')
          .attr('clicked', 0)
          .removeClass('color-button-primary')
          .addClass('color-button-secondary');
        break;
      case '0,1,1': //投过con，改投pro
        currentVoteNum += 1;
        $(this).siblings().html(currentVoteNum).removeClass('color-button-secondary').addClass('color-button-primary');
        $(this).attr('clicked', 1).removeClass('color-button-secondary').addClass('color-button-primary');
        currentVoteNum_theOther -= 1;
        $(this)
          .parent()
          .siblings()
          .children('.btnNumber')
          .html(currentVoteNum_theOther)
          .removeClass('color-button-danger')
          .addClass('color-button-secondary');
        $(this)
          .parent()
          .siblings()
          .children('.btnButton')
          .attr('clicked', 0)
          .removeClass('color-button-danger')
          .addClass('color-button-secondary');
        break;
      case '1,0,0': //投过con，取消con
        currentVoteNum -= 1;
        $(this).siblings().html(currentVoteNum).removeClass('color-button-danger').addClass('color-button-secondary');
        $(this).attr('clicked', 0).removeClass('color-button-danger').addClass('color-button-secondary');
        break;
      case '1,0,1': //投过pro，取消pro
        currentVoteNum -= 1;
        $(this).siblings().html(currentVoteNum).removeClass('color-button-primary').addClass('color-button-secondary');
        $(this).attr('clicked', 0).removeClass('color-button-primary').addClass('color-button-secondary');
        break;
      default:
        console.error('fatal error.');
        break;
    }
    $.post(
      path_http + 'proposalVote.php',
      {
        pid: pid,
        position: position,
      },
      () => {
        if (data == 'E3') {
          $('.eventAlertModalMsg').html(text_no_enough_balance);
          $('#eventAlertModal').modal('show');
          $('.eventAlertCancelBtn').click(() => {
            location.reload();
          });
        }
      }
    );
  });

  /* PROPOSAL删除 */
  $('.deleteProposalBtn').on('click', function () {
    /* 与常规函数相比，箭头函数对 this 的处理也有所不同。
  简而言之，使用箭头函数没有对 this 的绑定。 
  所以不能使用()=>{}。*/
    var pid = $(this).parent().attr('pid');
    $.post(path_http + 'deleteProposal.php', { pid: pid }, () => {
      location.reload();
    });
  });
  //注册时获取新的化身名字
  $('#signupModal').on('show.bs.modal', function () {
    getNewNames(3);
  });
  $('#refreshBtn').click(() => {
    $('#refreshBtn').addClass('rotateit');
    setTimeout(() => {
      $('#refreshBtn').removeClass('rotateit');
    }, 500);
    getNewNames(3);
  });

  //转账
  $('#confirmTransfer').click(function () {
    const toWallet = $('#toWallet').val(),
      amount = $('#amount').val(),
      openid = $('#selectedItem').val(),
      note = $('#note').val();

    if (!toWallet) {
      $('#uinfo').html(text_error_transfer_address).attr('class', 'alert alert-danger');
      return;
    }
    if (!amount) {
      $('#uinfo').html(text_error_transfer_amount).attr('class', 'alert alert-danger');
      return;
    }
    $(this).children().attr('class', 'fa-solid fa-circle-notch fa-spin');
    $.post(
      path_http + 'transfer.php',
      {
        toWallet: toWallet,
        openid: openid,
        amount: amount,
        note: note,
      },
      function (data) {
        switch (data) {
          case 'E1':
            $('.eventAlertModalMsg').html(text_login_please);
            $('#eventAlertModal').modal('show');
            $('.eventAlertCancelBtn').click(() => {
              location.reload();
            });
          case 'E2':
            setTimeout(
              `$("#confirmTransfer").children().attr("class", "fa-solid fa-xmark");$("#uinfo").html(${text_operation_failed}).attr("class","alert alert-danger");`,
              1000
            );
            break;
          case 'E3':
            setTimeout(
              `$("#confirmTransfer").children().attr("class", "fa-solid fa-xmark");$("#uinfo").html(${text_no_enough_balance}).attr("class","alert alert-danger");`,
              1000
            );
            break;
          case 'E5':
            setTimeout(
              `$("#confirmTransfer").children().attr("class", "fa-solid fa-xmark");$("#uinfo").html(${text_error_transfer_address}).attr("class","alert alert-danger");`,
              1000
            );
            break;
          default:
            $('#confirmTransfer').children().attr('class', 'fa-solid fa-check');
            if (openid == 'cd7cbfea7e382773a22bc01872e3d3ff') {
              $('.balanceValue').html('🍟' + parseInt(data));
            }
            $('#uinfo').html(text_transfer_success).attr('class', 'alert alert-success');
            location.reload();
        }
      }
    );
  });

  //切换化身
  $('.switchAvatar').on('click', function () {
    var thisAid = $(this).parent().parent().attr('aid');
    $.post(
      path_http + 'switchToAvatar.php',
      {
        aid: thisAid,
      },
      (data) => {
        switch (data) {
          case 'E1':
            $('.eventAlertModalMsg').html(text_login_please);
            $('#eventAlertModal').modal('show');
            $('.eventAlertCancelBtn').click(() => {
              location.reload();
            });
            break;
          case 'E2':
            $('.eventAlertModalMsg').html(text_operation_failed);
            $('#eventAlertModal').modal('show');
            $('.eventAlertCancelBtn').click(() => {
              location.reload();
            });
            break;
          default:
            $('.eventAlertModalMsg').html(text_switch_avatar_success);
            $('#eventAlertModal').modal('show');
        }
      }
    );
  });
  //增加化身按钮
  $('.addAvatarBtn').click(function () {
    if ($('.addAvatarBtn').attr('clicked') == '0') {
      $('.addAvatarBtn').css('transform', 'rotate(45deg)');
      $('.addAvatarBtn').attr('clicked', '1');
      getNewNames(3);
    } else {
      $('.addAvatarBtn').css('transform', 'rotate(0deg)');
      $('.addAvatarBtn').attr('clicked', '0');
    }
  });
  //开启Modal
  $('#selectAvatarBtn').click(() => {
    $('#avatarNameDisplay').html($('#avatar_chinese_' + $("input[name='avatar']:checked").val()).html());
  });
  //增加化身！
  $('#addNewAvatarBtn').click(function () {
    var avatarCN = '#avatar_chinese_' + $("input[name='avatar']:checked").val(),
      avatarEN = '#avatar_english_' + $("input[name='avatar']:checked").val(),
      md5 = $(avatarEN).parent().prev().attr('md5'),
      avatarNameCN = $(avatarCN).html(),
      avatarNameEN = $(avatarEN).html();
    $.post(
      path_http + 'addNewAvatar.php',
      {
        avatar_cn: avatarNameCN,
        avatar_en: avatarNameEN,
        md5: md5,
      },
      function (data) {
        switch (data) {
          case 'E1':
            $('.eventAlertModalMsg').html(text_login_please);
            $('#eventAlertModal').modal('show');
            $('.eventAlertCancelBtn').click(() => {
              location.reload();
            });
            break;
          case 'E2':
            $('.eventAlertModalMsg').html(text_operation_failed);
            $('#eventAlertModal').modal('show');
            $('.eventAlertCancelBtn').click(() => {
              location.reload();
            });
            break;
          case 'E3':
            $('.eventAlertModalMsg').html(text_no_enough_balance);
            $('#eventAlertModal').modal('show');
            $('.eventAlertCancelBtn').click(() => {
              location.reload();
            });
            break;
          default:
            location.reload();
        }
      }
    );
  });
  //赞赏
  $('#contributionBtn').click(function () {
    var amount = parseInt($("input[name='contribution']:checked").val()),
      toUid = $('#contributeModal').attr('pouid'),
      recieverAvatar = $('#contributeModal').attr('poavatar'),
      tid = $('#contributeModal').attr('tid'),
      threadTitle = $('.text-title').html();
    $.post(
      path_http + 'contribute.php',
      {
        tid: tid,
        amount: amount,
        reciever: toUid,
        recieverAvatar: recieverAvatar,
        threadTitle: threadTitle,
      },
      function (data) {
        switch (data) {
          case 'E1':
            $('.eventAlertModalMsg').html(text_login_please);
            $('#eventAlertModal').modal('show');
            $('.eventAlertCancelBtn').click(() => {
              location.reload();
            });
            break;
          case 'E3':
            $('.eventAlertModalMsg').html(text_no_enough_balance);
            $('#eventAlertModal').modal('show');
            $('.eventAlertCancelBtn').click(() => {
              location.reload();
            });
            break;
          default:
            location.reload();
        }
      }
    );
  });
  //发送验证邮件
  $('#verifyEmailBtn').click(function () {
    var avatar = $(this).attr('avatar'),
      email = $('#e').val(),
      token = $(this).attr('token'),
      username = $(this).attr('username');
    $('#veriftEmailBtnSpinner').css({ display: 'inherit' });
    setTimeout(() => {
      $('#veriftEmailBtnSpinner').css({ display: 'none' });
    }, 500);
    $.post(path_http + 'mailVerifyOnce.php', {
      avatar: avatar,
      email: email,
      token: token,
      username: username,
    });
    $('#emailHelp').html(text_sent_verify_mail);
  });

  $('#ccpwd').submit(() => {
    var oldpass = $('#o').val(),
      newpass = $('#np').val(),
      repeat = $('#r').val();
    if (newpass.length < 5 || newpass.length > 16) {
      $('#cpwd').attr('class', 'alert alert-danger').html(text_error_password_length);
      return false;
    }
    if (newpass != repeat) {
      $('#cpwd').attr('class', 'alert alert-danger').html(text_error_different_password);
      return false;
    }
    if (oldpass == newpass) {
      $('#cpwd').attr('class', 'alert alert-danger').html(text_error_same_password);
      return false;
    }
    changePassword(oldpass, newpass);
    return false;
  });

  $('#loginBtnConfirm').click(() => {
    var user = $('.username').val(),
      pass = $('.password').val();
    if (pass.length < 5 || pass.length > 16) {
      $('#passwordDescription').addClass('shakeit').html(text_error_password_length);
      setTimeout(() => {
        $('#emailHelp').removeClass('shakeit');
      }, 500);
      return;
    }
    $.post(
      path_http + 'loginAct.php',
      {
        username: user,
        password: pass,
      },
      function (data) {
        switch (data) {
          case 'E0':
            location.reload();
            break;
          case 'E2':
            $('#passwordDescription').addClass('shakeit').html(text_error_wrong_name_or_password);
            setTimeout(() => {
              $('#emailHelp').removeClass('shakeit');
            }, 500);
            break;
          default:
            location.reload();
            break;
        }
      }
    );
  });

  $('#signupBtnConfirm').click(() => {
    const user = $('.usernameReg').val(),
      user_re = /^[a-zA-Z0-9_-]{1,15}$/,
      email = $('.emailReg').val(),
      email_re = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/,
      avatarCN = '#avatar_chinese_' + $("input[name='avatar']:checked").val(),
      avatarEN = '#avatar_english_' + $("input[name='avatar']:checked").val(),
      avatarNameCN = $(avatarCN).html(),
      avatarNameEN = $(avatarEN).html(),
      md5 = $(avatarEN).parent().prev().attr('md5'),
      pass = $('.passwordReg').val(),
      pass2 = $('.repeatReg').val();

    if (user == '' || email == '') {
      $('#emailHelp').addClass('shakeit').html(text_registering_air);
      setTimeout(() => {
        $('#emailHelp').removeClass('shakeit');
      }, 500);
      return;
    }
    if (!user_re.test(user)) {
      $('#emailHelp').addClass('shakeit').html(text_error_wrong_username);
      setTimeout(() => {
        $('#emailHelp').removeClass('shakeit');
      }, 500);
      return;
    }
    if (!email_re.test(email)) {
      $('#emailHelp').addClass('shakeit').html(text_error_wrong_mail);
      setTimeout(() => {
        $('#emailHelp').removeClass('shakeit');
      }, 500);
      return;
    }
    if (pass.length < 6 || pass.length > 16) {
      $('#passwordDescriptionReg').addClass('shakeit').html(text_error_password_length);
      setTimeout(() => {
        $('#passwordDescriptionReg').removeClass('shakeit');
      }, 500);
      return;
    }
    if (pass !== pass2) {
      $('#passwordDescriptionReg').addClass('shakeit').html(text_error_different_password);
      setTimeout(() => {
        $('#passwordDescriptionReg').removeClass('shakeit');
      }, 500);
      return;
    }
    $.post(
      path_http + 'regAct.php',
      {
        email: email,
        avatar_cn: avatarNameCN,
        avatar_en: avatarNameEN,
        md5: md5,
        username: user,
        password: pass,
      },
      function (data) {
        switch (data) {
          case 'E0':
            console.log(false);
            $('#signupModal').modal('hide');
            $('#alertModal').modal('show');
            break;
          case 'E10':
            $('#emailHelp').addClass('shakeit').html(text_error_taken_email);
            setTimeout(() => {
              $('#emailHelp').removeClass('shakeit');
            }, 500);
            break;
          case 'E11':
            $('#passwordDescriptionReg').addClass('shakeit').html(text_error_password_length);
            setTimeout(() => {
              $('#passwordDescriptionReg').removeClass('shakeit');
            }, 500);
            break;
          case 'success':
            $('#signupModal').modal('hide');
            location.reload();
            break;
          default:
            console.log(data);
        }
      }
    );
  });
  /* ban user */
  $('.banUser').each(function () {
    $(this).on('click', function () {
      let uid = $(this).parent('td').attr('uid'),
        ban = 1;
      console.log(uid);
      $.post(path_http + 'ban.php', { uid: uid, ban: ban }, function (data) {
        console.log(data);
        $('.eventAlertModalMsg').html(text_banned_for_days);
        $('#eventAlertModal').modal('show');
        $('.eventAlertCancelBtn')
          .html(text_confirm)
          .click(() => {
            location.reload();
          });
      });
    });
  });
  $('.unBanUser').each(function () {
    $(this).on('click', function () {
      let uid = $(this).parent('td').attr('uid'),
        ban = 0;
      console.log(uid);
      $.post(path_http + 'ban.php', { uid: uid, ban: ban }, function (data) {
        console.log(data);
        $('.eventAlertModalMsg').html(text_unbanned);
        $('#eventAlertModal').modal('show');
        $('.eventAlertCancelBtn')
          .html(text_confirm)
          .click(() => {
            location.reload();
          });
      });
    });
  });
  $('.levelChange').each(function () {
    $(this).on('click', function () {
      let uid = $(this).parent('td').attr('uid'),
        group = parseInt($(this).parent('td').attr('group')) + parseInt($(this).attr('level'));
      $.post('group.php', { uid: uid, group: group }, function (data) {
        console.log(data);
        $('.eventAlertModalMsg').html(text_changed_user_level);
        $('#eventAlertModal').modal('show');
        $('.eventAlertCancelBtn')
          .html(text_confirm)
          .click(() => {
            location.reload();
          });
      });
    });
  });
  $('#daily-checkin').click(function () {
    $.post(path_http + 'checkin.php', function (data) {
      switch (data) {
        case 'success':
          $('#daily-checkin').parent().remove();
          let balance = parseInt($('#balance-value').attr('money')) + 10;
          $('.balanceValue').html('🍟<b>' + balance + '</b>');
          break;
        case 'E14':
          $('#daily-checkin').parent().remove();
          break;
        default:
      }
    });
  });
  $('#verifyWikiBtn').click(function () {
    const name = $('#verifyNameInput').val(),
      password = $('#verifyPasswordInput').val();
    if (!password) {
      $('#passwordDescription').html(text_password_please);
      return;
    }
    $.post(
      path_http + 'wikiVerify.php',
      {
        userName: name,
        userPassword: password,
      },
      function (data) {
        switch (data) {
          case 'E1':
            text_login_please;
            break;
          case 'E2':
            $('#passwordDescription').html(text_error_wrong_name_or_password);
            break;
          case 'E10':
            $('#passwordDescription').html(text_has_been_bound);
            return;
          case 'success':
            location.reload();
            break;
        }
      }
    );
  });
  $('#newItemBtn').click(function () {
    const title = $('#newItemTitle').val(),
      subtitle = $('#newItemSubtitle').val(),
      content = $('#newItemContent').val(),
      svg = $('#newItemSvg').val(),
      rarity = $('#newItemRarity').val(),
      price = $('#newItemPrice').val(),
      amount = $('#newItemAmount').val();
    $.post(
      path_http + 'addNewItem.php',
      {
        title: title,
        subtitle: subtitle,
        content: content,
        svg: svg,
        rarity: rarity,
        price: price,
        amount: amount,
      },
      function (data) {
        console.log(data);
        switch (data) {
          case 'success':
            break;
          default:
            break;
        }
      }
    );
  });

  $('#verifyAfdian').click(() => {
    window.location.href = path_aifadian_oauth;
  });
});

/* end of control */
