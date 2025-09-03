var menuBtnClicked = 0;
jQuery.extend({
  // 手动添加在 jQuery 1.4.2 之前的版本才有的 handlerError 方法
  handleError: function (s, xhr, status, e) {
    // If a local callback was specified, fire it
    if (s.error) s.error.call(s.context || s, xhr, status, e);
    // Fire the global callback
    if (s.global) (s.context ? jQuery(s.context) : jQuery.event).trigger('ajaxError', [xhr, s, e]);
  },

  createUploadIframe: function (id, uri) {
    // create frame
    var frameId = 'jUploadFrame' + id;

    if (window.ActiveXObject) {
      var io = document.createElement('iframe');
      io.id = frameId;
      io.name = frameId;
      if (typeof uri == 'boolean') {
        io.src = 'javascript:false';
      } else if (typeof uri == 'string') {
        io.src = uri;
      }
    } else {
      var io = document.createElement('iframe');
      io.id = frameId;
      io.name = frameId;
    }
    io.style.position = 'absolute';
    io.style.top = '-1000px';
    io.style.left = '-1000px';

    document.body.appendChild(io);

    return io;
  },

  createUploadForm: function (id, fileElementId, data) {
    // create form
    var formId = 'jUploadForm' + id;
    var fileId = 'jUploadFile' + id;
    var form = $(
      '<form  action="" method="POST" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>',
    );
    var oldElement = $('#' + fileElementId);
    var newElement = $(oldElement).clone(true); // true：复制元素的同时复制事件
    $(oldElement).attr('id', fileId);
    $(oldElement).before(newElement);
    $(oldElement).appendTo(form);

    // 增加参数的支持
    if (data) {
      for (var i in data) $('<input type="hidden" name="' + i + '" value="' + data[i] + '" />').appendTo(form);
    }

    // set attributes
    $(form).css('position', 'absolute');
    $(form).css('top', '-1200px');
    $(form).css('left', '-1200px');
    $(form).appendTo('body');
    return form;
  },

  addOtherRequestsToForm: function (form, data) {
    // add extra parameter
    var originalElement = $('<input type="hidden" name="" value="">');
    for (var key in data) {
      name = key;
      value = data[key];
      var cloneElement = originalElement.clone();
      cloneElement.attr({ name: name, value: value });
      $(cloneElement).appendTo(form);
    }
    return form;
  },

  ajaxFileUpload: function (s) {
    s = jQuery.extend({}, jQuery.ajaxSettings, s);
    var id = new Date().getTime();
    var form = jQuery.createUploadForm(id, s.fileElementId, s.data); // 添加传入参数 s.data
    if (s.data) form = jQuery.addOtherRequestsToForm(form, s.data);
    var io = jQuery.createUploadIframe(id, s.secureuri);
    var frameId = 'jUploadFrame' + id;
    var formId = 'jUploadForm' + id;
    // Watch for a new set of requests
    if (s.global && !jQuery.active++) jQuery.event.trigger('ajaxStart');
    var requestDone = false;
    // Create the request object
    var xml = {};
    if (s.global) jQuery.event.trigger('ajaxSend', [xml, s]);
    // Wait for a response to come back
    var uploadCallback = function (isTimeout) {
      var io = document.getElementById(frameId);
      try {
        if (io.contentWindow) {
          xml.responseText = io.contentWindow.document.body ? io.contentWindow.document.body.innerHTML : null;
          xml.responseXML = io.contentWindow.document.XMLDocument
            ? io.contentWindow.document.XMLDocument
            : io.contentWindow.document;
        } else if (io.contentDocument) {
          xml.responseText = io.contentDocument.document.body ? io.contentDocument.document.body.innerHTML : null;
          xml.responseXML = io.contentDocument.document.XMLDocument
            ? io.contentDocument.document.XMLDocument
            : io.contentDocument.document;
        }
      } catch (e) {
        jQuery.handleError(s, xml, null, e);
      }
      if (xml || isTimeout == 'timeout') {
        requestDone = true;
        var status;
        try {
          status = isTimeout != 'timeout' ? 'success' : 'error';
          // Make sure that the request was successful or notmodified
          if (status != 'error') {
            // process the data (runs the xml through httpData regardless of callback)
            var data = jQuery.uploadHttpData(xml, s.dataType);
            // If a local callback was specified, fire it and pass it the data
            if (s.success) s.success(data, status);
            // Fire the global callback
            if (s.global) jQuery.event.trigger('ajaxSuccess', [xml, s]);
          } else jQuery.handleError(s, xml, status);
        } catch (e) {
          status = 'error';
          jQuery.handleError(s, xml, status, e);
        }

        // The request was completed
        if (s.global) jQuery.event.trigger('ajaxComplete', [xml, s]);

        // Handle the global AJAX counter
        if (s.global && !--jQuery.active) jQuery.event.trigger('ajaxStop');

        // Process result
        if (s.complete) s.complete(xml, status);

        jQuery(io).unbind();

        setTimeout(function () {
          try {
            $(io).remove();
            $(form).remove();
          } catch (e) {
            jQuery.handleError(s, xml, null, e);
          }
        }, 100);

        xml = null;
      }
    };
    // Timeout checker
    if (s.timeout > 0) {
      setTimeout(function () {
        // Check to see if the request is still happening
        if (!requestDone) uploadCallback('timeout');
      }, s.timeout);
    }
    try {
      // var io = $('#' + frameId);
      var form = $('#' + formId);
      $(form).attr('action', s.url);
      $(form).attr('method', 'POST');
      $(form).attr('target', frameId);
      if (form.encoding) form.encoding = 'multipart/form-data';
      else form.enctype = 'multipart/form-data';
      $(form).submit();
    } catch (e) {
      jQuery.handleError(s, xml, null, e);
    }
    if (window.attachEvent) document.getElementById(frameId).attachEvent('onload', uploadCallback);
    else {
      document.getElementById(frameId).addEventListener('load', uploadCallback, false);
    }
    return { abort: function () {} };
  },

  uploadHttpData: function (r, type) {
    var data = !type;
    data = type == 'xml' || data ? r.responseXML : r.responseText;
    // If the type is "script", eval it in global context
    if (type == 'script') jQuery.globalEval(data);
    // Get the JavaScript object, if JSON is used.
    if (type == 'json') {
      // If you add mimetype in your response,
      // you have to delete the '<pre></pre>' tag.
      // The pre tag in Chrome has attribute, so have to use regex to remove
      data = r.responseText;
      var rx = new RegExp('<pre.*?>(.*?)</pre>', 'i');
      var am = rx.exec(data);
      // this is the desired data extracted
      var data = am ? am[1] : ''; // the only submatch or empty
      eval('data = ' + data); // 返回 json 对象，注释掉可返回 json 格式的字符串
    }
    // evaluate scripts within html
    if (type == 'html') jQuery('<div>').html(data).evalScripts();
    // alert($('param', data).each(function(){alert($(this).attr('value'));}));

    return data;
  },
});
//获取Cookie
function getCookie(cname) {
  var name = cname + '=';
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i].trim();
    if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
  }
  return '';
}
/* PWA start */
/* const divInstall = document.getElementById('installContainer');
const buttonInstall = document.getElementById('butInstall');
const cancelInstall = document.getElementById('butInstallCancel');
if (getCookie("INSTALLATIONDISMISSED")) {
  window.deferredPrompt = null;
  divInstall.style.display = "none"; 0
} else {
  window.addEventListener('appinstalled', () => {
    window.deferredPrompt = null;
    divInstall.style.display = "none"; 0
  });
  window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();  // 防止迷你信息栏出现在移动设备上。
    window.deferredPrompt = event; // 隐藏事件，以便以后再触发。
    divInstall.style.display = "flex";
  });

  buttonInstall.addEventListener('click', async () => {
    const promptEvent = window.deferredPrompt;
    if (!promptEvent) {
      return; // 延迟提示不存在。
    }
    promptEvent.prompt();  // 显示安装提示。
    const result = await promptEvent.userChoice;  // Log the result
    window.deferredPrompt = null; // 重置延迟提示变量，因为prompt() 只能调用一次。
    divInstall.style.display = "none";
  });
  cancelInstall.addEventListener('click', async () => {
    window.deferredPrompt = null;
    divInstall.style.display = "none";
    document.cookie = "INSTALLATIONDISMISSED=1";
  })
} */
/* PWA end */
//转换fontawesome
function fontawesomeNumber(number) {
  number = number + '';
  n = number.split('');
  var fNumber = '';
  for (i = 0; i < n.length; i++) {
    fNumber += '<i class="fa-solid fa-' + n[i] + '"></i>';
  }
  return fNumber;
}
//有关PO和RE的提示
function newREPOAlert(repo, data) {
  switch (data) {
    case 'E1':
      $('.eventAlertModalMsg').html(text_login_please);
      $('#eventAlertModal').modal('show');
      $('.eventAlertCancelBtn').click(() => {
        location.reload();
      });
      break;
    case 'E6':
      $('.eventAlertModalMsg').html(repo + text_post_too_fast);
      $('#eventAlertModal').modal('show');
      break;
    case 'E7':
      $('.eventAlertModalMsg').html(text_wrong_category);
      $('#eventAlertModal')
        .attr({
          'data-backdrop': 'static',
          'data-keyboard': 'false',
        })
        .modal('show');
      $('.eventAlertCancelBtn').click(() => {
        window.location.href = 'index.php';
      });
      break;
    case 'E4':
      $('.eventAlertModalMsg').html(text_empty_content);
      $('#eventAlertModal').modal('show');
      break;
    case 'success':
      $(window).scrollTop(0);
      location.reload();
      break;
    case 'E2':
      $('.eventAlertModalMsg').html(text_error_fatal);
      $('#eventAlertModal').modal('show');
      $('.eventAlertCancelBtn').click(() => {
        window.location.href = 'index.php';
      });
      break;
    case 'E3':
      $('.eventAlertModalMsg').html(text_no_enough_balance);
      $('#eventAlertModal').modal('show');
      $('.eventAlertCancelBtn').click(() => {
        location.reload();
      });
      break;
  }
}
//获取新的化身名称
function getNewNames(count = 3) {
  var resultData;
  $.ajax({
    url: path_http + 'generateNameAndAvatar.php',
    async: false,
    type: 'POST',
    data: {
      count: count,
    },
    success: function (data) {
      resultData = JSON.parse(data);
    },
  });
  for (var x = 0; x < count; x++) {
    var zhID = '#avatar_chinese_' + x,
      enID = '#avatar_english_' + x,
      md5 = resultData[x].md5;
    $(zhID).html(resultData[x].zh);
    $(enID).html(resultData[x].en);
    $(zhID).parent().prev().attr({ md5: md5 }).html(resultData[x].svg);
  }
}
function getModal(discussion, floor) {
  var theFloor = floor || 0;
  if (theFloor != 0) {
    var a = '>>' + discussion + '>' + floor + '\n';
    $('#content').val(a);
    document.getElementById('contentModal').value = a;
  }
  $('#replyBox').modal();
}
function view(tid, floor) {
  var result = $.ajax({
    url: './getReply.php?tid=' + tid + '&f=' + floor,
    async: false,
  });
  return result.responseText;
}

function changeUserInfo(nickname, email) {
  var result = $.ajax({
    url: path_http + 'settingAct.php',
    method: 'POST',
    data: 'nickname=' + nickname + '&email=' + email,
    async: false,
  });
  if (result.responseText == 'success') {
    $('#uinfo').attr('class', 'alert alert-success').html(text_change_done);
  } else {
    $('#uinfo').attr('class', 'alert alert-danger').html(text_chage_failed);
  }
}
function changePassword(oldpass, newpass) {
  var result = $.ajax({
    url: path_http + 'changePwdAct.php',
    method: 'POST',
    data: 'oldpass=' + oldpass + '&newpass=' + newpass,
    async: false,
  });
  if (result.responseText == 'success') {
    $('#cpwd').attr('class', 'alert alert-success').html(text_change_done);
  } else {
    $('#cpwd').attr('class', 'alert alert-danger').html(result.responseText);
  }
}
(function ($) {
  jQuery.fn.getCursorPosition = function () {
    if (this.lengh == 0) return -1;
    return $(this).getSelectionStart();
  };
  jQuery.fn.setCursorPosition = function (position) {
    if (this.lengh == 0) return this;
    return $(this).setSelection(position, position);
  };
  jQuery.fn.getSelection = function () {
    if (this.lengh == 0) return -1;
    var s = $(this).getSelectionStart();
    var e = $(this).getSelectionEnd();
    return this[0].value.substring(s, e);
  };
  jQuery.fn.getSelectionStart = function () {
    if (this.lengh == 0) return -1;
    input = this[0];

    var pos = input.value.length;

    if (input.createTextRange) {
      var r = document.selection.createRange().duplicate();
      r.moveEnd('character', input.value.length);
      if (r.text == '') pos = input.value.length;
      pos = input.value.lastIndexOf(r.text);
    } else if (typeof input.selectionStart != 'undefined') pos = input.selectionStart;

    return pos;
  };
  jQuery.fn.getSelectionEnd = function () {
    if (this.lengh == 0) return -1;
    input = this[0];

    var pos = input.value.length;

    if (input.createTextRange) {
      var r = document.selection.createRange().duplicate();
      r.moveStart('character', -input.value.length);
      if (r.text == '') pos = input.value.length;
      pos = input.value.lastIndexOf(r.text);
    } else if (typeof input.selectionEnd != 'undefined') pos = input.selectionEnd;

    return pos;
  };
  jQuery.fn.setSelection = function (selectionStart, selectionEnd) {
    if (this.lengh == 0) return this;
    input = this[0];

    if (input.createTextRange) {
      var range = input.createTextRange();
      range.collapse(true);
      range.moveEnd('character', selectionEnd);
      range.moveStart('character', selectionStart);
      range.select();
    } else if (input.setSelectionRange) {
      input.focus();
      input.setSelectionRange(selectionStart, selectionEnd);
    }

    return this;
  };
  jQuery.fn.insertAtCursor = function (myValue) {
    var $t = $(this)[0];
    if (document.selection) {
      this.focus();
      sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    } else if ($t.selectionStart || $t.selectionStart == '0') {
      var startPos = $t.selectionStart;
      var endPos = $t.selectionEnd;
      var scrollTop = $t.scrollTop;
      $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
      this.focus();
      $t.selectionStart = startPos + myValue.length;
      $t.selectionEnd = startPos + myValue.length;
      $t.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  };
  /**
   * Private variables
   **/
  var isDragged = false,
    startPoint = { x: 0, y: 0 },
    templates = {
      container: $('<div id="bcPaint-container"></div>'),
      palette: $('<div class="col-12 text-center p-1" id="bcPaint-palette"></div>'),
      stroke: $(
        '<div class="bcPaint-stroke"><input type="range" name="strokeRange" class="custom-range" min="1" max="50" id="strokeRange" value = "4" ><a id="strokeValue"></a></div> ',
      ),
      color: $('<div class="bcPaint-palette-color"></div>'),
      canvasContainer: $('<div class="col-12 text-center" id="bcPaint-canvas-container"></div>'),
      canvasPane: $('<canvas id="bcPaintCanvas" class="border border-dark rounded"></canvas>'),
      bottom: $('<div class="col-sm-12 col-md-12 text-center m-2" id="bcPaint-bottom"></div>'),
      buttonReset: $(
        '<button type="button" class="btn btn-secondary btn-sm mr-1" id="bcPaint-reset"><i class="fas fa-eraser"></i>&nbsp;Clear</button>',
      ),
      buttonSave: $(
        '<button type="button" class="btn btn-primary btn-sm ml-1" id="bcPaint-export"><i class="fas fa-download"></i>&nbsp;Export</button>',
      ),
    },
    paintCanvas,
    paintContext;

  /**
   * Assembly and initialize plugin
   **/
  $.fn.bcPaint = function (options) {
    return this.each(function () {
      var rootElement = $(this),
        colorSet = $.extend({}, $.fn.bcPaint.defaults, options),
        strokeSet = $.extend({}, $.fn.bcPaint.defaults, options),
        defaultColor = rootElement.val().length > 0 ? rootElement.val() : colorSet.defaultColor,
        defaultStroke = strokeSet.defaultStroke,
        container = templates.container.clone(),
        palette = templates.palette.clone(),
        canvasContainer = templates.canvasContainer.clone(),
        canvasPane = templates.canvasPane.clone(),
        stroke = templates.stroke.clone(),
        color;

      // assembly pane
      rootElement.append(container);
      container.append(canvasContainer);
      canvasContainer.append(canvasPane);
      container.append(palette);
      palette.append(stroke);

      // assembly color palette
      $.each(colorSet.colors, function (i) {
        color = templates.color.clone();
        color.css('background-color', $.fn.bcPaint.toHex(colorSet.colors[i]));
        color.attr('data-color', colorSet.colors[i]);
        palette.append(color);
      });

      // set canvas pane width and height
      var bcCanvas = rootElement.find('canvas');
      let width_height = 300;
      bcCanvas.attr('width', width_height);
      bcCanvas.attr('height', width_height);

      // get canvas pane context
      paintCanvas = document.getElementById('bcPaintCanvas');
      paintContext = paintCanvas.getContext('2d');
      paintContext.fillStyle = '#fff';
      paintContext.fillRect(0, 0, 300, 300);
      imgStack = [];
      imgStackIndex = -1;

      // set color & stroke
      $.fn.bcPaint.setColor(defaultColor);
      $.fn.bcPaint.setStroke(defaultStroke);

      // bind mouse actions
      paintCanvas.onmousedown = $.fn.bcPaint.onMouseDown;
      paintCanvas.onmouseup = $.fn.bcPaint.onMouseUp;
      paintCanvas.onmousemove = $.fn.bcPaint.onMouseMove;

      // bind touch actions
      paintCanvas.addEventListener('touchstart', function (e) {
        $.fn.bcPaint.dispatchMouseEvent(e, 'mousedown');
      });
      paintCanvas.addEventListener('touchend', function (e) {
        $.fn.bcPaint.dispatchMouseEvent(e, 'mouseup');
      });
      paintCanvas.addEventListener('touchmove', function (e) {
        $.fn.bcPaint.dispatchMouseEvent(e, 'mousemove');
      });

      // Prevent scrolling on touch event
      document.body.addEventListener(
        'touchstart',
        function (e) {
          if (e.target == 'paintCanvas') {
            e.preventDefault();
            e.stopPropagation();
          }
        },
        { passive: false },
      );
      document.body.addEventListener(
        'touchend',
        function (e) {
          if (e.target == 'paintCanvas') {
            e.preventDefault();
            e.stopPropagation();
          }
        },
        { passive: false },
      );
      document.body.addEventListener(
        'touchmove',
        function (e) {
          if (e.target == 'paintCanvas') {
            e.preventDefault();
            e.stopPropagation();
          }
        },
        { passive: false },
      );
    });
  };

  /**
   * Extend plugin
   **/
  $.extend(true, $.fn.bcPaint, {
    /**
     * Dispatch mouse event
     */
    dispatchMouseEvent: function (e, mouseAction) {
      var touch = e.touches[0];
      if (touch == undefined) {
        touch = { clientX: 0, clientY: 0 };
      }
      var mouseEvent = new MouseEvent(mouseAction, {
        clientX: touch.clientX,
        clientY: touch.clientY,
      });
      paintCanvas.dispatchEvent(mouseEvent);
    },

    /**
     * Remove pane
     */
    clearCanvas: function () {
      paintCanvas.width = paintCanvas.width;
      paintContext.fillStyle = '#fff';
      paintContext.fillRect(0, 0, 300, 300);
      $('.selected').removeClass('selected');
      $('.bcPaint-palette-color[data-color="000000"]').addClass('selected');
      $('input[name=strokeRange]').val(2);
      imgStackIndex = -1;
      imgStack = [];
    },

    /**
     * On mouse down
     **/
    onMouseDown: function (e) {
      isDragged = true;
      // get mouse x and y coordinates
      const rect = document.getElementById('bcPaintCanvas').getBoundingClientRect();
      startPoint.x = e.clientX - rect.left;
      startPoint.y = e.clientY - rect.top;
      // begin context path
      paintContext.beginPath();
      paintContext.moveTo(startPoint.x, startPoint.y);
      paintContext.lineTo(startPoint.x, startPoint.y);
      paintContext.stroke();
      paintContext.beginPath();
      paintContext.moveTo(startPoint.x, startPoint.y);
    },

    /**
     * On mouse up
     **/
    onMouseUp: function () {
      imgStack.push(paintContext.getImageData(0, 0, 300, 300));
      imgStackIndex += 1;
      if (imgStack.length > 20) {
        imgStack.shift();
        imgStackIndex -= 1;
      }
      isDragged = false;
    },

    /**
     * On mouse move
     **/
    onMouseMove: function (e) {
      if (isDragged) {
        const rect = document.getElementById('bcPaintCanvas').getBoundingClientRect();
        paintContext.lineTo(e.clientX - rect.left, e.clientY - rect.top);
        paintContext.lineCap = 'round';
        paintContext.lineJoin = 'round';
        paintContext.stroke();
      }
    },

    undo: function () {
      if (imgStackIndex <= 0) {
        this.clearCanvas();
      } else {
        imgStackIndex -= 1;
        imgStack.pop();
        paintContext.putImageData(imgStack[imgStackIndex], 0, 0);
      }
    },
    /**
     * Set selected color
     **/
    setColor: function (color) {
      paintContext.strokeStyle = $.fn.bcPaint.toHex(color);
    },

    /**
     * Set selected stroke
     **/
    setStroke: function (stroke) {
      paintContext.lineWidth = stroke;
    },
    /**
     *
     */
    export: function () {
      var imgData = paintCanvas.toDataURL('image/png');
      return imgData;
    },

    /**
     * Convert color to HEX value
     **/
    toHex: function (color) {
      // check if color is standard hex value
      if (color.match(/[0-9A-F]{6}|[0-9A-F]{3}$/i)) {
        return color.charAt(0) === '#' ? color : '#' + color;
        // check if color is RGB value -> convert to hex
      } else if (color.match(/^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/)) {
        var c = [parseInt(RegExp.$1, 10), parseInt(RegExp.$2, 10), parseInt(RegExp.$3, 10)],
          pad = function (str) {
            if (str.length < 2) {
              for (var i = 0, len = 2 - str.length; i < len; i++) {
                str = '0' + str;
              }
            }
            return str;
          };
        if (c.length === 3) {
          var r = pad(c[0].toString(16)),
            g = pad(c[1].toString(16)),
            b = pad(c[2].toString(16));
          return '#' + r + g + b;
        }
        // else do nothing
      } else {
        return false;
      }
    },
  });

  /**
   * Default color set
   **/
  $.fn.bcPaint.defaults = {
    // default color
    defaultColor: '000000',
    defaultStroke: 4,

    // default color set
    colors: ['000000', '#ffffff', '#dc3545', '#fd7e14', '#ffc107', '#28a745', '#20c997', '#6f42c1', '#007bff'],

    // extend default set
    addColors: [],
  };
})(jQuery);

window.onload = function () {
  let currentUrl = window.location.href;
  //初始化用户界面自动展开
  if (currentUrl == 'https://fandao.fun/me.php' && $(window).width() <= 768) {
    $('#menuBtn').css('transform', 'rotate(90deg)');
    menuBtnClicked = 1;
    $('#lmenu').collapse('show');
  } else {
    menuBtnClicked = 0;
  }
  /* 图片自动缩放 */
  $('.fandaoImg').each(function () {
    let maxHeight = 600,
      maxWidth = 450,
      width = $(this).width(),
      height = $(this).height(),
      containerWidth = $(window).width();
    if (containerWidth <= 576) {
      maxWidth = containerWidth - 128;
    } else {
      maxWidth = 450;
    }
    var scale = maxHeight / height <= maxWidth / width ? maxHeight / height : maxWidth / width;
    $(this).css('width', width);
    if (width > maxWidth || height > maxHeight) {
      $(this).css('width', scale * width);
      $(this).addClass('fandaoImg-enlarge');
      $(this).on('click', function () {
        window.open($(this).attr('src'));
      });
    }
  });
  $('.fandaoDoodle').each(function () {
    $(this).append(text_doodle_solitaire);
  });
  $('.fandaoDoodleSolitaire').each(function () {
    $(this).on('click', function () {
      const imgSrc = $(this).prev().attr('src'),
        discussion = $(this).parents('.discussion').attr('tid'),
        floor = $(this).parents('.reply').attr('floor');
      let img = new Image();
      img.src = imgSrc;
      let ctx = document.getElementById('bcPaintCanvas').getContext('2d');
      ctx.drawImage(img, 0, 0);
      getModal(discussion, floor);
      $('#painter').modal();
    });
  });
  $('.date').each(function () {
    let target = $(this).attr('date');
    display = moment(target, 'YYYYMMDDHHmmss').locale('zh-cn').fromNow();
    $(this).html(display);
  });
};
