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
$token_sql = "select * from tokens limit 1;";
$tokens_result = $conn->query($token_sql);
if ($conn->query($token_sql) === FALSE) {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
$get_token_url = 'https://auth.ebay.com/oauth2/authorize?client_id=DanielSa-Example-PRD-716e557a4-2c2a1194&response_type=code&redirect_uri=Daniel_Savu-DanielSa-Exampl-lwxtsaiw&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly';
$scopes = 'https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly';
$authenticate_message = '<p>Please visit <a href="'.$get_token_url.'">this link</a> in order to authenticate with eBay.</p>';
if ($tokens_result->num_rows==0){
    echo $authenticate_message;
} else {
   $token_row = $tokens_result->fetch_assoc();
   if ( time() > strtotime($token_row['expirationTime']) ){
      //need to use refresh_token to fetch new auth_token
    $clientID = "DanielSa-Example-PRD-716e557a4-2c2a1194";
    $clientSecret = "PRD-16e557a45ab8-2ab9-41bc-b143-02fb";
    $url = "https://api.ebay.com/identity/v1/oauth2/token";
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "grant_type=refresh_token&scope=" .  urlencode($scopes) . "&refresh_token=" . $token_row['refreshToken'],
      CURLOPT_HTTPHEADER => array(
        "Authorization: Basic " . base64_encode($clientID.':'.$clientSecret),
        "Content-Type: application/x-www-form-urlencoded",
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $response = json_decode($response);
    $expirationTime = time() + (3600 * 2 - 10 * 60);
    $mysqlExpirationTime = date ("Y-m-d H:i:s", $expirationTime);
     $update_sql = "update tokens set
              auth_token = \"" . $response->access_token . "\",
              expirationTime = \"" . $mysqlExpirationTime . "\"
              where refreshToken= \"" . $token_row['refreshToken'] . "\" ;";
     if ($conn->query($update_sql) === FALSE) {exit();}
     echo "Successfully updated auth_token";
   } else { $auth_token = $token_row['auth_token']; }
}
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
if ($err) {
  print_r($err);
  echo "cURL Error #:" . $err;
} else {
  $resp = json_decode($resp);
  foreach($resp->itemSummaries as $item) {
    $itemId = substr($item->itemId, 3, -2);
    $highestBid = $item->currentBidPrice->value;
    $bidCount = $item->bidCount;
    $query_sql = "SELECT * from items WHERE itemID = $itemId;";
    $query_resp = $conn->query($query_sql);
    if($query_resp->num_rows != 0) {
      $row = $query_resp->fetch_assoc();
      if (($row['highestBid'] != $highestBid) || ($row['bidCount'] != $bidCount)){
      //  $sql = 'UPDATE items set highestBid = $highestBid, bidCount = $bidCount, where itemID = $itemId;';
        $sql = $conn->prepare ('UPDATE items set highestBid = ?, bidCount = ? where itemID = ?;');
        if($sql==false)
        {
          trigger_error($sql->error, E_USER_ERROR);
        }
        $sql->bind_param('dii', $highestBid, $bidCount, $itemId);
        $status = $sql->execute();
        if ($status == false){trigger_error($status->error,E_USER_ERROR);}
        $sql='';
        $sql = "INSERT INTO timestamps (bidTime) values (NOW());";
        print_r($sql);
        if($conn->query($sql) == FALSE){ echo "Error: " . "<br>" . $sql->error; continue; }
        $timestamp_query = "SELECT max(timeID) FROM timestamps;";
        if($conn->query($timestamp_query)==FALSE) { echo "Error: " . "<br>" . $sql->error; continue; };
        $timestamp_resp = $conn->query($timestamp_query);
        $timestamp_row = $timestamp_resp->fetch_assoc();
        $timestampID = $timestamp_row["max(timeID)"];
        print_r($itemId);
        $sql='';
        $sql = $conn->prepare("INSERT INTO product_timestamp_junction (timestampID, itemID, highestBid, bidCount) values(?,?,?,?);");
        if($sql==false)
        {
          trigger_error($conn->error, E_USER_ERROR);
        }
        $sql->bind_param("iidi", $timestampID,$itemId,$highestBid,$bidCount);
        $sql->execute();
        if($sql==false)
        {
          trigger_error($conn->error, E_USER_ERROR);
        }
      }
    } else {
      // branch for item non-existent in table *yet*
      $sql = "INSERT INTO timestamps (bidTime) values (NOW());";
      if($conn->query($sql) == FALSE){ echo "Error: " . "<br>" . $sql->error; continue; }
      $timestamp_query = "SELECT max(timeID) FROM timestamps;";
      if($conn->query($timestamp_query)==FALSE) { echo "Error: " . "<br>" . $sql->error; continue; };
      $timestamp_resp = $conn->query($timestamp_query);
      $timestamp_row = $timestamp_resp->fetch_assoc();
      $timestampID = $timestamp_row["max(timeID)"];
      $sql = $conn->prepare("INSERT INTO product_timestamp_junction (timestampID, itemID, highestBid, bidCount) values(?,?,?,?);");
      $sql->bind_param("iidi", $timestampID, $itemId, $highestBid, $bidCount);
      $sql->execute();
      if($sql==false) { trigger_error($conn->error, E_USER_ERROR); }
    }
    $productName = $item->title;
    $currency = $item->currentBidPrice->currency;
    if(!isset($item->thumbnailImages[0]->imageUrl)) continue;
    if(!isset($item->seller->username)) continue;
    $thumbnailPhotoURL = $item->thumbnailImages[0]->imageUrl;
    $sellerUsername = $item->seller->username;
    $sellerFeedbackPercentage = $item->seller->feedbackPercentage;
    $itemCondition = $item->condition;
    if (count($item->buyingOptions)>1) {
      $buyingOptions = 3;
    } else if ($item->buyingOptions[0] == "AUCTION"){
      $buyingOptions = 1;
    } else {
      $buyingOptions = 2;
    }
    //Shopping API
    $apicall  = "$endpointShopping?";
    $apicall .= "callname=GetItemStatus&";
    $apicall .= "responseencoding=JSON&";
    $apicall .= "appid=$clientIDShopping&";
    $apicall .= "siteid=0&";
    $apicall .= "version=967&";
    $apicall .= "ItemID=$itemId";
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
    $auctionEndTime  =  $response->Item[0]->EndTime;
    $listingStatus = $response->Item[0]->ListingStatus;
    $auction_sql =$conn->prepare("INSERT INTO items (itemID, itemName, highestBid, currency, thumbnailPhotoURL, sellerUsername, itemCondition, bidCount,  auctionEndTime, listingStatus) values
                                  (?, ?, ?, ?,?, ?, ?, ?, ?, ?);");
    $auction_sql->execute();
    if($auction_sql==false)
    {
        trigger_error($conn->error, E_USER_ERROR);
    }
    $auction_sql ->bind_param("isddsssdss", $itemId, $productName, $highestBid, $currency,$thumbnailPhotoURL, $sellerUsername, $itemCondition, $bidCount, $auctionEndTime, $listingStatus);
    $auction_sql->execute();
    if($auction_sql==false)
    {
      trigger_error($auction_sql->error, E_USER_ERROR);
    }
    $seller_sql = $conn->prepare("INSERT INTO sellers (username, feedbackPercentage) values (?, ?);");
    $seller_sql->bind_param("ss", $sellerUsername, $sellerFeedbackPercentage);
    $seller_sql->execute();
    if($seller_sql==false)
    {
      trigger_error($seller_sql->error, E_USER_ERROR);
    }
    // Populate the buyingOptions Table - Many to Many relation
    foreach($item->buyingOptions as $buyingOption) {
      $query_sql = $conn->prepare("SELECT * from buyingOptions where buyingOption = ?;");
      $query_sql->bind_param("i",$buyingOption);
      $query_sql->execute();
      $query_resp = $query_sql->get_result();
      if($query_resp->num_rows == 0){
        $sql = $conn->prepare("INSERT INTO buyingOptions (buyingOption) values (?);");
        $sql->bind_param("i",$buyingOption);
        $sql->execute() or trigger_error($sql->error, E_USER_ERROR);
      }
      $sql = $conn->prepare("SELECT id from buyingOptions WHERE buyingOption = ?;");
      $sql->bind_param("i", $buyingOption);
      $sql->execute() or trigger_error($sql->error, E_USER_ERROR);
      $buyingOption_resp = $sql->get_result();
      $buyingOption_row = $buyingOption_resp->fetch_assoc();
      $buyingOption_id = $buyingOption_row['id'];
      $sql = "INSERT INTO product_buyingoptions_junction (itemID, buyingOptionID) values ('$itemId','$buyingOption_id');";
      if ($conn->query($sql) === FALSE) { }
    }
    // Populate the categories Table - Many to Many relation
    foreach($item->categories as $category) {
      $categoryId = $category->categoryId;
      $query_sql = $conn->prepare("SELECT * from categories WHERE id = ?;");
      $query_sql->bind_param("i", $categoryId);
      $query_sql->execute();
      $query_resp = $query_sql->get_result();
      if($query_resp->num_rows == 0) {
        $sql = $conn->prepare("INSERT INTO categories (id) values (?);");
        $sql->bind_param("i",$categoryId);
        $sql->execute();
        if ($sql === FALSE) { trigger_error($sql->error,E_USER_ERROR);}
      }
      $sql =$conn->prepare("SELECT id from categories WHERE id = ?;");
      $sql->bind_param("i", $categoryId);
      $sql->execute();
      $result = $sql->get_result();
      $category_row = $result->fetch_assoc();
      $category_id = $category_row['id'];
      $sql = $conn->prepare("INSERT INTO product_category_junction (itemID, categoryID) values (?,?);");
      $sql->bind_param("ii", $itemId, $category_id);
      $sql->execute();
    }
  }
}
