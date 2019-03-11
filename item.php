<?php
  $itemID = $_GET['itemID'];
  $itemName = $_GET['itemName'];
  $highestBid = $_GET['highestBid'];
  $bidCount = $_GET['bidCount'];
  $thumbnailPhotoURL = $_GET['thumbnailPhotoURL'];
  $sellerUsername = $_GET['sellerUsername'];
  $currency = $_GET['currency'];
  $itemCondition = $_GET['itemCondition'];
  $auctionEndTime = $_GET['auctionEndTime'];
  $sellerFeedbackPercentage = $_GET['sellerFeedbackPercentage'];

  // recommended bid is 1% over the current highest bid. This is in line with
  // ebay's automatic bidding system guidelines
  // https://www.ebay.co.uk/gds/EBAY-AUTOMATIC-BIDDING-SYSTEM-/10000000006909959/g.html
  $recBidWhole = (string)((int)($highestBid + (int)($highestBid/100)));
  $recBidDecimal = (string)((int)(($highestBid + ($highestBid/100) - $reccBidWhole) * 100));

  $currencyCodeToSymbol = array("USD" => "$", "GBP" => "£");

  $html = '<div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar"> <img src="img/sidebar.png" /> </ul>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"> <i class="fa fa-bars"></i> </button>
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                    <div class="sidebar-brand-icon"> <i class="fas fa-tags"></i> </div>
                    <div class="sidebar-brand-text mx-3">eBay Auctioneer</div>
                </a>
                <form action="search.html"  onclick="if(event.target.id === \'search-btn\') this.submit()" method="get" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input id="search-txt" name="search" type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button id="search-btn" class="btn btn-primary" type="button"> <i class="fas fa-search fa-sm"></i> </button>
                        </div>
                    </div>
                </form>
            </nav>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="item-card shadow mb-1">
                            <div class="card-body row" style="height: 100px; !important">
                                <div class="item-chart-pie col-5"> <img class="item-img" src="'.$thumbnailPhotoURL.'" /> </div>
                                <div class="col">
                                    <div class="card-body item-right-card">
                                        <h5 class="listing-title font-weight-bold text-dark text-uppercase mb-0">'.$itemName.'</h5>
                                        <div class="listing-seller mb-3">sold by '.$sellerUsername.'<span class="listing-seller-score">| '.$sellerFeedbackPercentage.'% Positive feedback </span></div>
                                        <div class="text-dark"> <span class="listing-price-bid-description">Highest Bid:</span> <span class="listing-price-bid listing-price-bid-currency">'.$currencyCodeToSymbol[$currency].'</span>
                                            <!-- commented to remove whitespace on page --><span id="highestBid" , class="listing-price-bid listing-price-bid-whole">'.$highestBid.'</span>
                                            <!-- commented to remove whitespace on page --><span class="listing-price-bid listing-price-bid-currency"></span> </div>
                                        <div class="text-dark"> <span><span class="listing-extra-values">'.$bidCount.'</span> bids were placed!</span>
                                            <br>
                                        </div>
                                        <div class="listing-price-fixed"> <span class="listing-price-fixed-description">Buy it now: </span> <span class="listing-price-fixed-currency">'.$currencyCodeToSymbol[$currency].'</span>
                                            <!-- commented to remove whitespace on page --><span class="listing-price-fixed-whole">1000</span>
                                            <!-- commented to remove whitespace on page --><span class="listing-price-fixed-currency">00</span> </div>
                                        <br/>
                                        <div class="text-dark listing-text"> <span>Condition: <span class="listing-extra-values">'.$itemCondition.'</span></span>
                                            <br><span>Auction ends in: <span class="listing-extra-values" id=\'countdown\'></span></span>
                                        </div>
                                        <!--<br/> <button id="histButton", class="d-none d-sm-inline-block btn btn-primary shadow-sm col-g-2", onclick="getHistogram()">Update Histogram</button> -->

                                        <br>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/> <span class="text-dark listing-text">Recommended bid:</span> <span class="item-price-bid item-price-bid-currency">'.$currencyCodeToSymbol[$currency].'</span>
                                        <!-- commented to remove whitespace on page --><span class="item-price-bid item-price-bid-whole">' . $recBidWhole . '</span>
                                        <!-- commented to remove whitespace on page --><span class="item-price-bid item-price-bid-currency">' . $recBidDecimal . '</span>
                                        <br/>
                                        <div class="input-group">
                                            <div class="input-icon"> <a href="https://www.ebay.co.uk/itm/'.$itemID.'" class="d-none d-sm-inline-block btn btn-primary shadow-sm col-g-2"><i class="fas fa-gavel"> </i>&nbsp Go Bid!</a> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div class="container-fluid">
            <div class="row" style="margin-top: 400px;">

                <div class="col-xl-6 col-lg-6">

                    <!-- Area Chart -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Highest bid evolution</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart" style="width: 1000px!important;"></canvas>
                            </div>
                            <hr> Evolution of the <code>highest bid</code> of this auction over time.
                        </div>
                    </div>

                </div>

                <!-- Bar Chart -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">How does this auction compare with others?</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-bar pt-4">
                                <canvas id="myBarChart"></canvas>
                            </div>
                            <hr> Comparison between the <code>highest bid</code> on the current auction and the other auctions returned by the previous search.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Set the date were counting down to
            var countDownDate = new Date("'.$auctionEndTime.'").getTime();
        </script>
  ';

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
// var countDownDate = new Date("2019-03-05 20:20:39").getTime();

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
  if(distance < 0) {
    clearInterval(x);
    document.getElementById("countdown").innerHTML = "EXPIRED";
  } else {
    document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
  }
}, 1000);

/*
window.onload = function() {
  search();
  var input = document.getElementById("search-txt");
  input.addEventListener("keyup", function(event) {
    // Cancel the default action, if needed
    event.preventDefault();
    console.log("preventium leviosa");
    // Number 13 is the "Enter" key on the keyboard
    if (event.keyCode === 13) {
      // Trigger the button element with a click
      document.getElementById("search-btn").click();
      document.getElementById("ui-id-1").style.display = "none";
      
      // search();
      return false;
    }
    
  });
}*/

function search(){
  var suggestionBox = document.getElementById("ui-id-1");
  if(suggestionBox) {
    suggestionBox.style.display = "none";
  }
}

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
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
  <script src="js/demo/chart-bar-demo.js"></script>

</body>

<script>
 $(function()
 {
   $("#search-txt").autocomplete({
     source: "./search_autosuggestion.php",
     minlength:2,
   });
 });
</script>

</html>
