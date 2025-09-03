$(document).ready(function () {
  $('#menuBtn').remove();
});
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
      console.table(priceHistory);
    },
  });
  removeData(chart);
  for (let k = 0; k < Object.keys(priceHistory).length; k++) {
    addData(chart, moment(priceHistory[k].time, 'X').locale('zh-cn').format('YY/M/D'), priceHistory[k].price);
  }
}

function chart() {
  $('#chartHistory').remove();
  $('#chartContainer').append('<canvas id="chartHistory"></canvas>');
  const chart_ctx = $('#chartHistory');
  if (typeof chart_ctx[0] == 'undefined') {
    return;
  }
  const chart = new Chart(chart_ctx, {
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
}
