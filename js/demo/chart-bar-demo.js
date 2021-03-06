Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

var histLabels;
var histSize;

function getHistogram(){
  var query = window.location.href;
  query = query.split("query=")[1];
  query = decodeURIComponent(query);
  myAjax(query);
}

function myAjax(userQuery){
  $.ajax({
    type: "POST",
    url: './js/demo/getHistogramData.php',
    data: {action: "update", query: userQuery, try: "try"},
    success: function(data){
      data = data.split(" ").map(Number); // the raw numbers from the database - we need to transform this array 
      var binWidth = 1 + 3.322 * Math.log(data.length) // uses Sturge's Rule
      binWidth = Math.round(binWidth);
      binWidth = Math.round((Math.max.apply(null, data) - Math.min.apply(null, data)) / binWidth);
      var roundedData = [];
      for(var i =0; i < data.length; i++){
        var x = Math.round(data[i] / binWidth ) * binWidth;
        roundedData.push(x);
      }

      data = roundedData;
      var currentHighestBid = document.getElementById("highestBid").innerHTML;
      if (!binWidth) binWidth = 1;
      currentHighestBid = Math.round(currentHighestBid / binWidth) * binWidth;

      histLabels = [];
      histSize = [];
      var prev;
      data.sort((a,b) => (a - b));
      if (data.length == 1){
        histLabels.push(currentHighestBid);
        histSize.push(1);
      } else {
        for(var i = 0; i < data.length; i++){
          if(data[i] !== prev){
            histLabels.push(data[i]);
            histSize.push(1);
          }else{
            histSize[histSize.length - 1]++;
          }
          prev = data[i];
        }
      }
      var backColor = [];
      var length = histSize.length;
      for(var i = 0; i < length; i ++){
       if(histLabels[i] == currentHighestBid){
          backColor.push("#00FF91");
        }else{
          backColor.push("#4e73df");
        }
      }

      var ctx = document.getElementById("myBarChart");
      var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: histLabels,
          datasets: [{
            label: "Auctions with this highest bid: ",
            backgroundColor: backColor,
            hoverBackgroundColor: "#FFDE00",
            borderColor: "#4e73df",
            data: histSize,
          }],
        },
        options: {
          maintainAspectRatio: false,
          layout: {
            padding: {
              left: 10,
              right: 25,
              top: 25,
              bottom: 0
            }
          },
          scales: {
            yAxes: [{
              display: true,
              ticks: {
                min: 0,
                maxTicksLimit: 5,
              }
            }],
          },
          legend: {
            display: false
          },
          tooltips: {
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            callbacks: {
              label: function(tooltipItem, chart) {
                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                return datasetLabel  + number_format(tooltipItem.yLabel);
              }
            }
          },
        }
      });

    },
    error: function(data){
      console.log("An ERROR occured when retrieve histogram data. Check getHistogramData.php");
      console.log(data);
    }
  });
}

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

var ctx = document.getElementById("myBarChart");
getHistogram();
myBarChart.update();

