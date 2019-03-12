<?php
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging
$currencyCodeToSymbol = array("USD" => "$", "GBP" => "£");

$conn = new mysqli("ebayer.mysql.database.azure.com", "dragos@ebayer", "CDDG_databosses", "ebayer");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query =  $_POST['query'];
$form_validation_regex = "/^[A-Za-z0-9 ]+$/i";
if(!preg_match($form_validation_regex, $query)) {
  exit();
}
$sql_subq = "select * from items where itemName like '%$query%'";
$sql = "SELECT * FROM sellers s 
      INNER JOIN ($sql_subq) i 
      ON s.username = i.sellerUsername";

if ($conn->query($sql) === FALSE) {
  echo "Error: " . $sql . "<br>" . $conn->error;
} else{
  $items_resp = $conn->query($sql);
  while($item_row = $items_resp->fetch_assoc()){
    // print_r($item_row);
    $itemID = $item_row['itemID'];
    $itemName = $item_row['itemName'];
    $highestBid = $item_row['highestBid'];
    $highestBid = number_format($highestBid, 2, '.', '');
    $bidCount = $item_row['bidCount'];
    $thumbnailPhotoURL = $item_row['thumbnailPhotoURL'];
    $sellerUsername = $item_row['sellerUsername'];
    $currency = $item_row['currency'];
    $itemCondition = $item_row['itemCondition'];
    $auctionEndTime = $item_row['auctionEndTime'];
    $sellerFeedbackPercentage = $item_row['feedbackPercentage'];
    $sellerFeedbackPercentage = number_format($sellerFeedbackPercentage, 1, '.', '');

    $url = 'item.php?itemName='.$itemName.'&highestBid='.$highestBid.'&bidCount='.$bidCount.'&thumbnailPhotoURL='.$thumbnailPhotoURL.'&sellerUsername='.$sellerUsername.'&currency='.$currency.'&itemCondition='.$itemCondition.'&auctionEndTime='.$auctionEndTime.'&sellerFeedbackPercentage='.$sellerFeedbackPercentage.'&itemID='.$itemID.'&query='.$query;;
    
    $results = '
            <div class="col-xl-12 col-12">
              <div class="card shadow mb-2">
                <!-- Card Body -->
                <div class="card-body row">
                  <div class="chart-pie col-3">
                    <a href="'.$url.'"><img class="listing-img" src="'.$thumbnailPhotoURL.'"/></a>
                  </div>
                  <div class="col">
                    <div class="card-body">
                      <a href="'.$url.'"><h5 class="listing-title font-weight-bold text-dark text-uppercase mb-0">'.$itemName.'</h5></a>
                      <div class="listing-seller mb-3">sold by '.$sellerUsername.' <span class="listing-seller-score">| '.$sellerFeedbackPercentage.'% Positive feedback </span></div>

                      <div class="text-dark">
                        <span class="listing-price-bid-description">Highest Bid:</span>
                        <span class="listing-price-bid listing-price-bid-currency">'.$currencyCodeToSymbol[$currency].'</span><!-- commented to remove whitespace on page
                     --><span class="listing-price-bid listing-price-bid-whole">'.$highestBid.'</span><!--  commented to remove whitespace on page
                     --><span class="listing-price-bid listing-price-bid-currency"></span>
                      </div>

                      <div class="listing-price-fixed">
                        <span class="listing-price-fixed-description">Buy it now: </span>
                        <span class="listing-price-fixed-currency">$</span><!-- commented to remove whitespace on page
                     --><span class="listing-price-fixed-whole">1000</span><!--  commented to remove whitespace on page
                     --><span class="listing-price-fixed-currency">00</span>
                      </div>
                      <br/>
                      <div class="text-dark listing-text">
                      <span>Condition: <span class="listing-extra-values">'.$itemCondition.'</span></span><br>
                      <span>Auction ends in: <span class="countdown listing-extra-values"></span></span>
                      <span class="auctionEndTime" style="display:none">'.$auctionEndTime.'</span>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>';
    echo $results;
  }
}

?>
