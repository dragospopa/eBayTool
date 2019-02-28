<?php


$conn = new mysqli("ebayer.mysql.database.azure.com", "dragos@ebayer", "CDDG_databosses", "ebayer");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$clientIDShopping = "GeorgiaP-Databoss-PRD-a393cab7d-302f338e";

// production
$clientID = "DanielSa-Example-PRD-716e557a4-2c2a1194";
$clientSecret = "PRD-16e557a45ab8-2ab9-41bc-b143-02fb";
$ruName = "Daniel_Savu-DanielSa-Exampl-lwxtsaiw";

$curl = curl_init();
$endpoint = 'https://api.ebay.com/buy/browse/v1/item_summary/search?';  // URL to call
$endpointShopping = 'http://open.api.ebay.com/shopping';

$filters =
  array(
    'q' => 'Iphone X',
    'limit' => '100',
    'filter' => array('price:[150..2000],priceCurrency:USD', 'buyingOptions:{AUCTION}'),
  );
$endpoint .= "q=".urlencode($filters['q'])."&";
$endpoint .= "limit".urlencode($filters['limit']);
foreach($filters['filter'] as $filter) {
  $endpoint .= "&filter=".urlencode($filter);
}
echo $endpoint;
echo "<br><br><br>";
echo "im done here<br><br>";

$token_sql = "select * from tokens limit 1;";
$tokens_result = $conn->query($token_sql);

if ($conn->query($token_sql) === FALSE) {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$get_token_url = 'https://auth.ebay.com/oauth2/authorize?client_id=DanielSa-Example-PRD-716e557a4-2c2a1194&response_type=code&redirect_uri=Daniel_Savu-DanielSa-Exampl-lwxtsaiw&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly';
$authenticate_message = '<p>Please visit <a href="'.$get_token_url.'">this link</a> in order to authenticate with eBay.</p>';

if ($tokens_result->num_rows==0){
    echo $authenticate_message;
} else {
   $token_row = $tokens_result->fetch_assoc();
   if ( time() > strtotime($token_row['expirationTime']) ){
     echo $authenticate_message;
     $delete_sql = "DELETE from tokens;";
     if ($conn->query($delete_sql) === FALSE) {exit();}
   } else { $auth_token = $token_row['auth_token']; }
}

print_r("hi<br>");


curl_setopt_array($curl, array(
  CURLOPT_URL => $endpoint,
  CURLOPT_ENCODING => "",
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_RETURNTRANSFER => TRUE,
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $auth_token",
    "Content-Type: application/json",
    "cache-control: no-cache"
  ),
));
$resp = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
echo "hi<br>";
if ($err) {
  print_r($err);
  echo "cURL Error #:" . $err;
} else {
  //print_r($resp);
  $resp = json_decode($resp);
  //print_r($resp->itemSummaries[0]);
  foreach($resp->itemSummaries as $item) {
    $itemId = substr($item->itemId, 3, -2);


    $query_sql = "SELECT * from auctions WHERE itemID = $itemId;";
    $query_resp = $conn->query($query_sql);
    if($query_resp->num_rows != 0) { continue; }

    print_r($itemId);

    $productName = $item->title;
    $highestBid = $item->currentBidPrice->value;
    $currency = $item->currentBidPrice->currency;

    if(isset($item->thumbnailImages[0]->imageUrl))
        $thumbnailPhotoURL = $item->thumbnailImages[0]->imageUrl;
      else {
          $thumbnailPhotoURL="";
      }

    if(!isset($item->seller->username))
            continue;

    $sellerUsername = $item->seller->username;
    $sellerFeedbackPercentage = $item->seller->feedbackScore;
    $itemCondition = $item->condition;
    $bidCount = $item->bidCount;

    if (count($item->buyingOptions)>1) {
      $buyingOptions = 3;
    } else if ($item->buyingOptions[0] == "AUCTION"){
      $buyingOptions = 1;
    } else {
      $buyingOptions = 2;
    }
    //print_r($item->currentBidPrice->value);
    //print_r($thumbnailPhotoURL);

    //Shopping API
    print_r("<br><br>");
    $apicall  = "$endpointShopping?";
    $apicall .= "callname=GetItemStatus&";
    $apicall .= "responseencoding=JSON&";
    $apicall .= "appid=$clientIDShopping&";
    $apicall .= "siteid=0&";
    $apicall .= "version=967&";
    $apicall .= "ItemID=$itemId";
    //print_r($apicall);
    //$resp_shop = simplexml_load_file($apicall);

   $ch = curl_init();
   curl_setopt_array($ch, array(
     CURLOPT_URL => $apicall,
     CURLOPT_ENCODING => "",
     CURLOPT_TIMEOUT => 30,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => "GET",
     CURLOPT_RETURNTRANSFER => TRUE,
     CURLOPT_POSTFIELDS => "",
     CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $auth_token",
    "Content-Type: application/json",
    "cache-control: no-cache"
    ),
  ));

   $response = curl_exec($ch);
   curl_close($ch);

   $response = json_decode($response);

  // print_r ($response->Item[0]->EndTime);

    //print_r($response);

    $auctionEndTime  =  $response->Item[0]->EndTime;
    $listingStatus = $response->Item[0]->ListingStatus;

    //print_r ($response);
    //print_r($auctionEndTime);
    //print_r($listingStatus);


    $sql = '';

    $sql .=  "INSERT INTO auctions (itemID, productName, highestBid, currency, thumbnailPhotoURL, sellerUsername, sellerFeedbackPercentage, itemCondition, bidCount,  auctionEndTime, buyingOptions, listingStatus) values
                                  (\"$itemId\", \"$productName\", $highestBid, \"$currency\",\"$thumbnailPhotoURL\", \"$sellerUsername\", $sellerFeedbackPercentage, \"$itemCondition\", $bidCount, \"$auctionEndTime\", \"$buyingOptions\", \"$listingStatus\"); ";

    // else {
    //   $sql .=  "INSERT INTO auctions (itemID, productName, highestBid, currency, thumbnailPhotoURL, sellerUsername, sellerFeedbackPercentage, itemCondition, bidCount,  auctionEndTime, buyingOptions, listingStatus) values
    //                                 (\"$itemId\", \"$productName\", $highestBid, \"$currency\", , \"$sellerUsername\", $sellerFeedbackPercentage, \"$itemCondition\", $bidCount, \"$auctionEndTime\", \"$buyingOptions\", \"$listingStatus\"); ";
    // }
    $query_r = $conn->query($sql);
    // urmeaza chestia complicata

    foreach($item->categories as $category)
    {
      print_r ($category);
      print_r ("<br>");
      $categoryId = $category->categoryId;
      $query_sql = "SELECT * from categories WHERE id = $categoryId;";
      $query_resp = $conn->query($query_sql);

      if($query_resp->num_rows == 0) {
        $sql = '';
        $sql .= "INSERT INTO categories (id) values ('$categoryId');";
        if ($conn->query($sql) === FALSE) { echo "Error: " . $sql . "<br>" . $conn->error; }

      }

      $sql = "SELECT id from categories WHERE id = $categoryId;";
      $category_resp =  $conn->query($sql);

      $category_row = $category_resp->fetch_assoc();
      $category_id = $category_row['id'];

      $sql = "INSERT INTO product_category_junction (itemID, categoryID) values ('$itemId','$category_id');";
      if ($conn->query($sql) === FALSE) { echo "Error: " . $sql . "<br>" . $conn->error; }
    }


    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    echo "<br><br><br><br><br>";
  }
}

?>
