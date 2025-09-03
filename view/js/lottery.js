function getCountdown() {
  // find the amount of "seconds" between now and target
  var current_date = new Date().getTime();
  var seconds_left = (target_date - current_date) / 1000;
  days = fontawesomeNumber(pad(parseInt(seconds_left / 86400)));
  seconds_left = seconds_left % 86400;
  hours = fontawesomeNumber(pad(parseInt(seconds_left / 3600)));
  seconds_left = seconds_left % 3600;
  minutes = fontawesomeNumber(pad(parseInt(seconds_left / 60)));
  seconds = fontawesomeNumber(pad(parseInt(seconds_left % 60)));
  // format countdown string + set tag value
  countdown.innerHTML = '<span>' + hours + '</span><a>:</a><span>' + minutes + '</span><a>:</a><span>' + seconds + '</span>';
}

function pad(n) {
  return (n < 10 ? '0' : '') + n;
}
var date = new Date(),
  target_date = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 21, 00, 00), // set the countdown date
  days,
  hours,
  minutes,
  seconds, // variables for time units
  countdown = document.getElementById('tiles'), // get tag element
  scale = 50, // Font size and overall scale
  breaks = 0.003, //0.003 Speed loss per frame
  endSpeed = 0.01, // Speed at which the letter stops
  firstLetter = 240, //220 Number of frames untill the first letter stopps (60 frames per second)
  delay = 40, // 40 Number of frames between letters stopping
  canvas = document.querySelector('canvas'),
  ctx = canvas.getContext('2d');

var chars = ['🍟', '🍕', '🥑', '🍭', '🍺', '🍉', '🥐', '🍙', '🍦'];
countdownTimer = setInterval(function () {
  var refreshHours = parseInt(new Date().getHours());
  var refreshMin = parseInt(new Date().getMinutes());
  var refreshSec = parseInt(new Date().getSeconds());
  if (refreshHours >= 21 && refreshHours < 22) {
    $('#buyaticket')
      .html('<i class="fa-solid fa-calculator"></i>&nbsp;结算中，22:00开放彩票购买')
      .css({ 'pointer-events': 'none' })
      .removeClass('color-button-primary')
      .addClass('color-button-secondary');
    $('#countdownMSG').html('<i class="fa-solid fa-clock-rotate-left"></i>&nbsp;结算中！22：00重置彩票数据');
    $('#countdown').html('');
    $.ajax({
      url: 'include/http/getLottery.php',
      async: false,
      type: 'POST',
      success: function (data) {
        a = JSON.parse(data);
        console.log(a);
        text = [a['num1'], a['num2'], a['num3'], a['num4']];
      },
    });
    var charMap = [],
      offset = [],
      offsetV = [];
    for (var i = 0; i < chars.length; i++) {
      charMap[chars[i]] = i;
    }
    for (var i = 0; i < text.length; i++) {
      var f = firstLetter + delay * i;
      offsetV[i] = endSpeed + breaks * f;
      offset[i] = (-(1 + f) * (breaks * f + 2 * endSpeed)) / 2;
    } //animations
    requestAnimationFrame(
      (loop = function () {
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (var i = 0; i < text.length; i++) {
          ctx.fillStyle = '';
          ctx.textBaseline = 'middle';
          ctx.textAlign = 'center';
          ctx.setTransform(1, 0, 0, 1, Math.floor((canvas.width - scale * (text.length - 1)) / 2), Math.floor(canvas.height / 2));
          var o = offset[i];
          while (o < 0) o++;
          o %= 1;
          var h = Math.ceil(canvas.height / 2 / scale);
          for (var j = -h; j < h; j++) {
            var c = charMap[text[i]] + j - Math.floor(offset[i]);
            while (c < 0) c += chars.length;
            c %= chars.length;
            var s = 0.8 - Math.abs(j + o) / (canvas.height / 2 / scale + 1);
            ctx.globalAlpha = 1 - Math.abs(j + o) / (canvas.height / 2 / scale + 1);
            ctx.font = scale * s + 'px Helvetica';
            ctx.fillText(chars[c], scale * i, (j + o) * scale);
          }
          offset[i] += offsetV[i];
          offsetV[i] -= breaks;
          if (offsetV[i] < endSpeed) {
            offset[i] = 0;
            offsetV[i] = 0;
          }
        }
        requestAnimationFrame(loop);
      })
    );
    setTimeout(function () {
      $.post('include/http/checkTicket.php ', function (data) {
        let prize = JSON.parse(data),
          key;
        for (key in prize) {
          $('.lottery-ticket').each(function () {
            if ($(this).html() == key) {
              $(this).addClass('prize');
            }
          });
        }
      });
    }, 6000);
    clearInterval(countdownTimer);
  } else {
    $('#countdownMSG').html('<i class="fa-regular fa-clock"></i>&nbsp;距离开奖还有');
    $('#buyaticket')
      .html('<i class="fa-solid fa-ticket"></i>&nbsp;购买彩票!')
      .css({ 'pointer-events': '' })
      .removeClass('color-button-secondary')
      .addClass('color-button-primary');
    getCountdown();
  }
}, 1000);

$(document).ready(function () {
  getCountdown();
  $('#menuBtn').remove();
  var wordClicked = 0;
  $('.seagull,.question').click(function () {
    if (!wordClicked) {
      $('.seagullsword').removeClass('invisible').addClass('visible');
      wordClicked = 1;
    } else {
      $('.seagullsword').removeClass('visible').addClass('invisible');
      wordClicked = 0;
    }
  });
  $('.emoji-btn').on('click', function () {
    var emoji = $(this).html();
    if ($('#emoji-input').val().length < 8) {
      var content = $('#emoji-input').val() + emoji;
      $('#emoji-input').val(content);
    } else {
      $('.input-group').addClass('shakeit');
      setTimeout(() => {
        $('.input-group').removeClass('shakeit');
      }, 500);
      return;
    }
  });
  $('.emoji-random').click(function () {
    var result = '';
    for (x = 0; x < 4; x++) {
      let index = Math.floor(Math.random() * 9);
      result += chars[index];
    }
    $('#emoji-input').val(result);
  });
  $('.emoji-delete').click(function () {
    $('#emoji-input').val('');
  });
  $('#buyaticket').click(function () {
    let content = $('#emoji-input').val();
    if (content.length != 8) {
      $('.input-group').addClass('shakeit');
      setTimeout(() => {
        $('.input-group').removeClass('shakeit');
      }, 500);
      return;
    }
    $.post(
      'include/http/buyTicket.php ',
      {
        content: content,
      },
      function (data) {
        switch (data) {
          case 'E1':
            return;
          case 'E3':
            $('#account').addClass('color-font-danger');
            setTimeout(() => {
              $('#account').removeClass('color-font-danger');
            }, 500);
            return;
          case 'success':
            let a = parseInt($('.myAmount').html()) - 3;
            let b = parseInt($('.pool').html()) + 3;
            $('.myAmount').html(a);
            $('.pool').html(b);
            $('.lottery-mine').append('<div class="lottery-ticket color-background-secondary">' + content + '</div>');
            break;
        }
      }
    );
  });
});
