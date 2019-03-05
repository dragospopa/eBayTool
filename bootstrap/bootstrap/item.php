<?php
  $itemName = $_GET['itemName'];
  $highestBid = $_GET['highestBid'];
  $bidCount = $_GET['bidCount'];
  $thumbnailPhotoURL = $_GET['thumbnailPhotoURL'];
  $sellerUsername = $_GET['sellerUsername'];
  $currency = $_GET['currency'];
  $itemCondition = $_GET['itemCondition'];
  $auctionEndTime = $_GET['auctionEndTime'];
  $sellerFeedbackPercentage = $_GET['sellerFeedbackPercentage'];
  $originalQuery = $_GET['originalQuery'];
  $query = $_GET['query'];

  $html = '<div id="wrapper"> <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar"> <img src="img/sidebar.png"/> </ul> <div id="content-wrapper" class="d-flex flex-column"> <div id="content"> <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"> <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"> <i class="fa fa-bars"></i> </button> <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html"> <div class="sidebar-brand-icon"> <i class="fas fa-tags"></i> </div><div class="sidebar-brand-text mx-3">eBay Auctioneer</div></a> <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search"> <div class="input-group"> <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2"> <div class="input-group-append"> <button class="btn btn-primary" type="button"> <i class="fas fa-search fa-sm"></i> </button> </div></div></form> </nav> <div class="container-fluid"> <div class="row"> <div class="col-xl-12 col-12"> <div class="item-card shadow mb-2"> <div class="card-body row"> <div class="chart-pie col-5"> <img class="item-img" src="'.$thumbnailPhotoURL.'"/> </div><div class="col"> <div class="card-body"> <h5 class="listing-title font-weight-bold text-dark text-uppercase mb-0">'.$itemName.'</h5> <div class="listing-seller mb-3">sold by '.$sellerUsername.'<span class="listing-seller-score">| '.$sellerFeedbackPercentage.'% Positive feedback </span></div><div class="text-dark"> <span class="listing-price-bid-description">Highest Bid:</span> <span class="listing-price-bid listing-price-bid-currency">'.$currency.'</span><!-- commented to remove whitespace on page --><span id="highestBid", class="listing-price-bid listing-price-bid-whole">'.$highestBid.'</span><!-- commented to remove whitespace on page --><span class="listing-price-bid listing-price-bid-currency"></span> </div><div class="text-dark"> <span><span class="listing-extra-values">'.$bidCount.'</span> users already placed a bid!</span><br></div><div class="listing-price-fixed"> <span class="listing-price-fixed-description">Buy it now: </span> <span class="listing-price-fixed-currency">'.$currency.'</span><!-- commented to remove whitespace on page --><span class="listing-price-fixed-whole">1000</span><!-- commented to remove whitespace on page --><span class="listing-price-fixed-currency">00</span> </div><br/> <div class="text-dark listing-text"> <span>Condition: <span class="listing-extra-values">'.$itemCondition.'</span></span><br><span>Auction ends in: <span class="listing-extra-values" id=\'countdown\'></span></span> </div><br/> <br/> <button id="histButton", class="d-none d-sm-inline-block btn btn-primary shadow-sm col-g-2", onclick="getHistogram()">Update Histogram</button> <br><br><div class="item-bar-chart"> <div class="card-header"> <h6 class="m-0 font-weight-bold text-primary">How does this auction compare with others?</h6> </div><div class="card-body"> <div class="chart-bar"> <div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"> <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"> <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"> <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div><canvas id="myBarChart" style="display: block; height: 320px; width: 729px;" width="1458" height="640" class="chartjs-render-monitor"></canvas> </div></div></div><br/> <br/> <br/> <br/> <span class="text-dark listing-text">Recommended bid:</span> <span class="item-price-bid item-price-bid-currency">$</span><!-- commented to remove whitespace on page --><span class="item-price-bid item-price-bid-whole">900</span><!-- commented to remove whitespace on page --><span class="item-price-bid item-price-bid-currency">00</span> <br/> <div class="input-group"> <div class="input-icon"> <input type="text" class="form-control" placeholder="900.00"> <i>$</i> </div><div class="input-group-append"> <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow-sm col-g-2"><i class="fas fa-gavel"> </i>&nbsp Bid!</a> </div></div></div></div></div></div></div></div></div></div></div><a class="scroll-to-top rounded" href="#page-top"> <i class="fas fa-angle-up"></i> </a> <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"> <div class="modal-dialog" role="document"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5> <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button> </div><div class="modal-body">Select "Logout" below if you are ready to end your current session.</div><div class="modal-footer"> <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button> <a class="btn btn-primary" href="login.html">Logout</a> </div></div></div></div>';

    echo $html;

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">

  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
  <script src="js/demo/chart-bar-demo.js"></script>

  <script>
// Set the date we're counting down to
var countDownDate = new Date("Jun 5, 2019 15:37:25").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get todays date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
  document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);

// function getHistogram(){
//   myAjax();
// }

// var histData;

// function myAjax(){
//   $.ajax({
//     type: "POST",
//     url: 'search.php',
//     success: function(data){
//       histData = data;
//     }
//   });
// }
</script>

</head>

<body class="page-top">

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
  <script src="js/demo/chart-bar-demo.js"></script>

</body>

</html>
