<?php
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging


$conn = new mysqli("ebayer.mysql.database.azure.com", "dragos@ebayer", "CDDG_databosses", "ebayer");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query =  $_POST['query'];

$sql = "select * from items as i, sellers as s where i.itemName like '%$query%' and s.username = i.sellerUsername;";

if ($conn->query($sql) === FALSE) {
  echo "Error: " . $sql . "<br>" . $conn->error;
} else{

  $items_resp = $conn->query($sql);
  while($item_row = $items_resp->fetch_assoc()){
    //print_r($item_row);
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

    $url = 'item.php?itemName='.$itemName.'&highestBid='.$highestBid.'&bidCount='.$bidCount.'&thumbnailPhotoURL='.$thumbnailPhotoURL.'&sellerUsername='.$sellerUsername.'&currency='.$currency.'&itemCondition='.$itemCondition.'&auctionEndTime='.$auctionEndTime.'&sellerFeedbackPercentage='.$sellerFeedbackPercentage;
    
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
                        <span class="listing-price-bid listing-price-bid-currency">'.$currency.'</span><!-- commented to remove whitespace on page
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
                      <span>Auction ends in: <span class="listing-extra-values" id=\'countdown\'></span></span>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>';
    echo $results;
  }
}

//echo $items_resp;
/*if ($items_resp->num_rows==0){
    echo "No results";
} else {
    //while(){
    //  echo $item_row;
   // }
}*/
/*
// API request variables
$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
$version = '1.0.0';  // API version supported by your application
$appid = 'DragosPo-Test-PRD-fa6d8bb94-d0289368';  // Replace with your own AppID
$globalid = 'EBAY-US';  // Global ID of the eBay site you want to search (e.g., EBAY-DE)
$query =  $_POST['query']; // You may want to supply your own query
$safequery = urlencode($query);  // Make the query URL-friendly
$i = '0';  // Initialize the item filter index to 0

// Create a PHP array of the item filters you want to use in your request
$filterarray =
  array(
    array(
    'name' => 'MaxPrice',
    'value' => $_POST['maxPrice'],
    'paramName' => 'Currency',
    'paramValue' => $_POST['currency']),
    array(
    'name' => 'MinPrice',
    'value' => $_POST['minPrice'],
    'paramName' => 'Currency',
    'paramValue' => $_POST['currency']),
    array(
    'name' => 'FreeShippingOnly',
    'value' => $_POST["freeShippingOnly"],
    'paramName' => '',
    'paramValue' => ''),
    array(
    'name' => 'ListingType',
    'value' => array('AuctionWithBIN','FixedPrice','StoreInventory'),
    'paramName' => '',
    'paramValue' => ''),
  );

// Generates an indexed URL snippet from the array of item filters
function buildURLArray ($filterarray) {
  global $urlfilter;
  global $i;
  // Iterate through each filter in the array
  foreach($filterarray as $itemfilter) {
    // Iterate through each key in the filter
    foreach ($itemfilter as $key =>$value) {
      if(is_array($value)) {
        foreach($value as $j => $content) { // Index the key for each value
          $urlfilter .= "&itemFilter($i).$key($j)=$content";
        }
      }
      else {
        if($value != "") {
          $urlfilter .= "&itemFilter($i).$key=$value";
        }
      }
    }
    $i++;
  }
  return "$urlfilter";
} // End of buildURLArray function

// Build the indexed item filter URL snippet
buildURLArray($filterarray);

// Construct the findItemsByKeywords HTTP GET call 
$apicall = "$endpoint?";
$apicall .= "OPERATION-NAME=findItemsByKeywords";
$apicall .= "&SERVICE-VERSION=$version";
$apicall .= "&SECURITY-APPNAME=$appid";
$apicall .= "&GLOBAL-ID=$globalid";
$apicall .= "&keywords=$safequery";
$apicall .= "&paginationInput.entriesPerPage=100";
$apicall .= "$urlfilter";

// Load the call and capture the document returned by eBay API
$resp = simplexml_load_file($apicall);

// Check to see if the request was successful, else print an error
if ($resp->ack == "Success") {
  $results = '';
  // If the response was loaded, parse it and build links
  foreach($resp->searchResult->item as $item) {
    $pic   = $item->galleryURL;
    $link  = $item->viewItemURL;
    $title = $item->title;
    $price = $item->price;

    if (empty($price)) { $price = 200.555; }


    // For each SearchResultItem node, add it to database.
    $sql = '';
    $sql .=  "INSERT INTO products (name, price, url) values (\"$title\", $price, \"$link\");";

    // For each SearchResultItem node, build a link and append it to $results
    $results .= "<tr><td><img src=\"$pic\"></td><td><a href=\"$link\">$title</a>$query</td></tr>";
  }
}
// If the response does not indicate 'Success,' print an error
else {
  $results  = "<h3>Oops! The request was not successful. Make sure you are using valid filters.</h3>";
}
*/




?>