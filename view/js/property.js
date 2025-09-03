/* start 设定资产交易历史 */
function setPropertyHistory(data) {
  var a = JSON.parse(data),
    template =
      '<tr><th data-toggle="tooltip" data-placement="bottom" title="事件"><i class="fa-solid fa-hand-point-up"></i></th><th data-toggle="tooltip" data-placement="bottom" title="价格"><i class="fa-solid fa-tag"></i></th><th data-toggle="tooltip" data-placement="bottom" title="从"><i class="fa-solid fa-person-walking-arrow-right"></i>&nbsp;从</th><th data-toggle="tooltip" data-placement="bottom" title="到"><i class="fa-solid fa-child-reaching"></i>&nbsp;到</th><th data-toggle="tooltip" data-placement="bottom" title="时间"><i class="fa-regular fa-clock"></i>&nbsp;于</th></tr>';
  for (const k in a) {
    var propFromCont = a[k].fromWallet,
      propToCont = a[k].toWallet;
    function switchPropName(content) {
      switch (content) {
        case avatar_fandao:
          return avatar_fandao_cn;
        case avatar_avatar:
          return avatar_avatar_cn;
        case avatar_ip:
          return avatar_ip_cn;
        case avatar_lottery:
          return avatar_lotterry_cn;
        case avatar_blackhole:
          return avatar_blackhole_cn;
        default:
          return content;
      }
    }
    var propFrom =
        a[k].fromWallet != 'anonymous'
          ? '<i class="fa-solid fa-landmark" data-toggle="tooltip" data-placement="top" title="' +
            switchPropName(propFromCont) +
            '"></i>'
          : '<i class="fa-solid fa-child-reaching" data-toggle="tooltip" data-placement="top" title="匿名"></i>',
      propTo =
        a[k].toWallet != 'anonymous'
          ? '<i class="fa-solid fa-landmark" data-toggle="tooltip" data-placement="top" title="' +
            switchPropName(propToCont) +
            '"></i>'
          : '<i class="fa-solid fa-child-reaching" data-toggle="tooltip" data-placement="top" title="匿名"></i>',
      propEvent = '<i class="fa-solid fa-right-left" data-toggle="tooltip" data-placement="right" title="交易"></i>',
      price = a[k].price;
    if (k == 0) {
      propEvent = '<i class="fa-regular fa-gem" data-toggle="tooltip" data-placement="right" title="创建"></i>';
      price = '';
    }
    template +=
      '<tr><td class="property-his-event">' +
      propEvent +
      '</td><td class="property-his-price">' +
      price +
      '</td><td class="property-his-from">' +
      propFrom +
      '</td><td class="property-his-to">' +
      propTo +
      '</td><td class="property-his-time" data-toggle="tooltip" data-placement="left" title="' +
      moment(a[k].time, 'X').locale('zh-cn').format('dddd, MMMM Do YYYY, h:mm:ss a') +
      '">' +
      moment(a[k].time, 'X').locale('zh-cn').fromNow();
    +'</td></tr>';
  }
  $('#propertyHistoryContent').append(template);
  $(function () {
    //由于是异步加载表格，需要通过回调重新启用tooltip显示
    $('[data-toggle="tooltip"]').tooltip();
  });
}
/* end 设定资产交易历史 */

/* 表格操作 */
function addData(chart, label, data) {
  chart.data.labels.push(label);
  chart.data.datasets.forEach((dataset) => {
    dataset.data.push(data);
  });
  chart.update();
}
function removeData(chart) {
  for (i = 0; i < Object.keys(chart).length; i++) {
    chart.data.labels.pop();
    chart.data.datasets.forEach((dataset) => {
      dataset.data.pop();
    });
  }
  chart.update();
}
/* 异步动态更新表格 */
function updatePriceHistory(chart, openid) {
  priceHistory = '';
  $.ajax({
    url: path_http + 'getPriceHistory.php',
    async: false,
    type: 'POST',
    data: {
      openid: openid,
    },
    success: function (data) {
      priceHistory = JSON.parse(data);
    },
  });
  removeData(chart);
  for (let k = 0; k < Object.keys(priceHistory).length; k++) {
    addData(chart, moment(priceHistory[k].time, 'X').locale('zh-cn').format('YY/M/D'), priceHistory[k].price);
  }
}

$(function () {
  /* 确认上架按钮 */
  $('#sellConfirm').click(function () {
    let price = parseInt($('#property-modal-price').val()),
      balance = parseInt($(this).attr('balance')),
      fee = parseInt($('#property-modal-fee').val()),
      amount = parseInt($('#property-modal-amount').val()),
      originalAmount = parseInt($(this).attr('originalamount')),
      openid = $(this).attr('openid');
    if (price < 0) {
      $('#property-modal-price-label').html(text_negative_amount);
      return;
    } else if (price == 0) {
      $('#property-modal-price-label').html(text_property_zero_price);
      return;
    }
    if (balance < fee) {
      $('#property-modal-price-label').html(text_no_enough_balance);
      $('#modalAccont').addClass('shakeit');
      setTimeout(() => {
        $('#modalAccont').removeClass('shakeit');
      }, 500);
      return;
    }
    if (amount < 0) {
      $('#property-modal-amount-label').html(text_negative_amount);
      return;
    } else if (amount == 0) {
      $('#property-modal-amount-label').html(text_questionmark_pointdown);
      return;
    } else if (amount > originalAmount) {
      $('#property-modal-amount-label').html(text_questionmark_pointdown);
      return;
    } else {
      $.post(path_http + 'newTrade.php', { price: price, openid: openid, amount: amount }, function (data) {
        switch (data) {
          case 'success':
            location.reload();
            break;
          case 'E1':
            location.reload();
            break;
          case 'E2':
            location.reload();
            break;
          case 'E14':
            $('#property-modal-amount-label').html(text_questionmark_pointdown);
            break;
        }
      });
    }
  });
  /* 确认下架按钮 */
  $('#closeConfirm').click(function () {
    let openid = $(this).attr('openid');
    $.post(path_http + 'closeProperty.php', { openid: openid }, function (data) {
      location.reload();
    });
  });

  /* 确认购买按钮 */
  $('#buyConfirm').click(function () {
    let openid = $(this).attr('openid'),
      tdid = $(this).attr('tdid'),
      price = parseInt($('.property-modal-price').html()),
      originAmount = parseInt($('#property-modal-origin-amount').html()),
      amount = $('#property-buy-amount').val(),
      account = parseInt($('.myAmount').html());
    if (amount > originAmount) {
      $('#property-modal-origin-amount').addClass('shakeit');
      setTimeout(() => {
        $('#property-modal-origin-amount').removeClass('shakeit');
      }, 500);
      return;
    }
    finalPrice = price * amount;
    if (account < finalPrice) {
      $('#modalAccont').addClass('shakeit');
      setTimeout(() => {
        $('#modalAccont').removeClass('shakeit');
      }, 500);
      return;
    }
    $.post(path_http + 'buyProperty.php', { openid: openid, amount: amount, tdid: tdid }, function (data) {
      switch (data) {
        case 'success':
          if (amount == originAmount) {
            $('#' + openid).remove();
            $('.myAmount').html(account - price);
            $('#buyModal').modal('hide');
          } else {
            $('#property-modal-origin-amount').html(originAmount - amount);
            $('.myAmount').html(account - price);
            $('#buyModal').modal('hide');
          }
          break;
        case 'E1':
          break;
        case 'E2':
          $('#modalAccont').addClass('shakeit');
          setTimeout(() => {
            $('#modalAccont').removeClass('shakeit');
          }, 500);
          return;
        case 'E14':
          $('#modalAccont').addClass('shakeit');
          setTimeout(() => {
            $('#modalAccont').removeClass('shakeit');
          }, 500);
          return;
      }
    });
  });

  $('#wikiScoreToUCT').click(function () {
    $.post(path_http + 'wikiScoreUpdate.php', function (data) {
      const content = JSON.parse(data);
      console.table(content);
      $('#score-current').html(content.oldscore);
      $('#score-diff').html(content.diff);
      $('#score-uct').html(Math.abs(content.uct));
      switch (content.msg) {
        case 'success':
          $('#score-status').html(text_wikiscore_add);
          $('#score-diff,#score-uct').addClass('color-font-important');
          $('#score-status-text').html(text_wikiscore_score2uct);
          break;
        case 'forfeit':
          $('#score-status').html(text_wikiscore_minus);
          $('#score-diff,#score-uct').addClass('color-font-danger');
          $('#score-status-text').html(text_pension_charging);
          break;
        case 'none':
          $('#score-status').html(text_wikiscore_nochange);
          $('#score-status-text').html(text_wikiscore_come_next_day);
          $('#score-diff').html('');
          $('#score-uct').html('');
          break;
      }
    });
    $('#wikiScoreToUCTModal').modal('show');
  });
});

window.onload = function () {
  /* start 初始化价格变化表格 */
  const chart_ctx = $('#trend');
  if (typeof chart_ctx[0] == 'undefined') {
    return;
  }
  var chart_trend = new Chart(chart_ctx, {
    type: 'line',
    data: {
      labels: [],
      datasets: [
        {
          label: text_chart_historical_price,
          data: [],
          fill: false,
          borderColor: ['#e83e8c'],
          tension: 0.23,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
      },
      scales: {
        x: {
          display: true,
          title: {
            display: true,
          },
        },
        y: {
          display: true,
          title: {
            display: false,
            text: 'Value',
          },
          ticks: {
            precision: 0,
          },
        },
      },
    },
  });
  /* end 初始化价格变化表格 */

  /*sellModal 初始化 */
  $('.sellThisProperty').each(function () {
    $(this).on('click', function () {
      $('#propertyModalTitle').html(text_property_sell);
      $('#propertyAmountInputContainer,#propertyLink').addClass('d-none');
      $('#propertySellModalFooter,#propertyInputContainer,#modalAccont,#propertyDecompose,#propertyHistory-card').removeClass(
        'd-none'
      );
      const prop_sellable = parseInt($(this).attr('sellable')),
        prop_openid = $(this).attr('openid'),
        prop_desc = $(this).attr('desc') ? '<div class="m-1">' + $(this).attr('desc') + '</div>' : '',
        prop_type = parseInt($(this).attr('item-type')),
        prop_amount = parseInt($(this).attr('amount')),
        prop_subtitle = $(this).parent().siblings('.card-header').find('.property-subtitle').html(),
        prop_content = $(this).parent().siblings('.property-content').html() + prop_desc,
        prop_title = $(this).parent().siblings('.card-header').find('.property-title').html();
      $('#propertyLink').attr({ href: path_explorer + prop_title + '&faction=1' });
      if (!prop_sellable) {
        $('#propertyModalTitle').html(text_property_inspect);
        $('#propertySellModalFooter,#propertyInputContainer,#modalAccont,#propertyDecompose').addClass('d-none');
      }
      if (prop_type == 1 || prop_type == 2 || prop_type == 5) {
        $('#propertyHistory-card').addClass('d-none');
        $('#propertyAmountInputContainer').removeClass('d-none');
      } else {
        $('#propertyHistoryContent').html('');
        $.post(path_http + 'getPropertyHistory.php', { openid: prop_openid }, function (data) {
          setPropertyHistory(data);
        });
        if (prop_type == 8) $('#propertyLink').removeClass('d-none');
      }
      $('.property-modal-title').html(prop_title);
      $('.property-modal-subtitle').html(prop_subtitle);
      $('.property-modal-content').html(prop_content);
      $('.property-modal-openid').html(prop_openid);
      $('#property-modal-origin-amount').html(prop_amount);
      $('#sellConfirm,.accordion,#decomposeConfirmBtn').attr({
        openid: prop_openid,
        originalAmount: prop_amount,
      });
      $('#property-modal-price,#property-modal-amount').val(1);
      $('#property-modal-price-label,#property-modal-amount-label').html('');
      updatePriceHistory(chart_trend, prop_openid);
    });
  });
  /*buyModal 初始化 */
  $('.buyThisProperty').each(function () {
    $(this).on('click', function () {
      $('#propertyModalTitle').html(text_property_buy);
      $('.property-ft-amount,#propertyLink').addClass('d-none');
      $('#propertyBuyModalFooter,#modalAccont').removeClass('d-none');
      const prop_buyable = parseInt($(this).attr('buyable')),
        prop_openid = $(this).attr('openid'),
        prop_tdid = $(this).attr('tdid'),
        prop_desc = $(this).attr('desc') ? '<div class="m-1">' + $(this).attr('desc') + '</div>' : '',
        prop_type = parseInt($(this).attr('item-type')),
        prop_amount = parseInt($(this).attr('amount')),
        prop_price = parseInt($(this).prev().find('.price').html()),
        prop_title = $(this).parent().siblings('.card-header').find('.property-title').html(),
        prop_subtitle = $(this).parent().siblings('.card-header').find('.property-subtitle').html(),
        prop_content = $(this).parent().siblings('.property-content').html() + prop_desc;
      $('#propertyLink').attr({ href: path_explorer + prop_title + '&faction=1' });
      if (!prop_buyable) {
        $('#propertyModalTitle').html(text_property_inspect);
        $('#propertyBuyModalFooter,#modalAccont').addClass('d-none');
      }
      if (prop_type == 1 || prop_type == 2 || prop_type == 5) {
        $('#propertyHistory-card').addClass('d-none');
        $('.property-ft-amount').removeClass('d-none');
        $('#property-modal-final-price').html(prop_price);
      } else {
        $('#propertyHistoryContent').html('');
        $.post(path_http + 'getPropertyHistory.php', { openid: prop_openid }, function (data) {
          setPropertyHistory(data);
        });
        if (prop_type == 8) $('#propertyLink').removeClass('d-none');
      }
      $('.property-modal-price').html(prop_price);
      $('.property-modal-title').html(prop_title);
      $('.property-modal-subtitle').html(prop_subtitle);
      $('.property-modal-content').html(prop_content);
      $('.property-modal-openid').html(prop_openid);
      $('#property-modal-origin-amount').html(prop_amount);
      $('#buyConfirm,.accordion').attr({
        openid: prop_openid,
        tdid: prop_tdid,
      });
      $('#property-buy-amount').val(1);
      updatePriceHistory(chart_trend, prop_openid);
    });
  });
  /* 下架资产 */
  $('.closeThisProperty').each(function () {
    $(this).on('click', function () {
      var prop_openid = $(this).attr('openid');
      $('#closeConfirm').attr({ openid: prop_openid });
    });
  });
  /* 监听计算价格手续费 */
  $('#property-modal-price,#property-modal-amount').bind('input propertychange', function () {
    var fee = Math.floor($('#property-modal-price').val() * 0.05 * $('#property-modal-amount').val());
    $('#property-modal-fee').val(fee);
  });
  $('#property-buy-amount').bind('input propertychange', function () {
    var sum = parseInt($('.property-modal-price').html()) * $('#property-buy-amount').val();
    console.log(sum);
    $('#property-modal-final-price').html(sum);
  });
  $('.date-to').each(function () {
    let target = $(this).attr('date');
    target = moment(target, 'YYYYMMDDHHmmss');
    display = moment().locale('zh-cn').to(target);
    $(this).html(display);
  });
  $('#propertyDecompose').click(function () {
    const openid = $('#property-modal-info').attr('openid');
    $.post(path_http + 'estimate.php', { openid: openid }, function (data) {
      switch (data) {
        case 'E1':
        case 'E2':
        default:
          $('#estimationPrice').html(data);
      }
    });
    $('#decomposePanel').modal('show');
  });
  $('#decomposeConfirmBtn').on('click', function () {
    const openid = $(this).attr('openid');
    $.post(path_http + 'decompose.php', { openid: openid }, function (data) {
      switch (data) {
        case 'E1':
        case 'E2':
        default:
          location.reload();
      }
    });
  });
};
