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

$token_sql = "select auth_token from tokens limit 1;";
$tokens_result = $conn->query($token_sql);

    if ($conn->query($token_sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

print_r($tokens_result);
if ($tokens_result->num_rows==0){
   $execute = 'token.php';
   echo $execute;
   // cheama token.php si populeaza $auth_token
} else {
   print_r("good branch of if-statement");
   $token_row = $tokens_result->fetch_assoc();
   $auth_token = $token_row['auth_token'];
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
    $productName = $item->title;
    $highestBid = $item->currentBidPrice->value;
    $currency = $item->currentBidPrice->currency;
    $thumbnailPhotoURL = $item->thumbnailImages[0]->imageUrl;
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
    "Authorization: Bearer v^1.1#i^1#r^0#p^3#f^0#I^3#t^H4sIAAAAAAAAAOVYXWwUVRTu9o80iAJiVQRZB0wQmN07P7uzO7JLtrSlG2i77BYCNQp3Zu60087OrHPvtN0HYqkJNCZCfDAxJCaNQIyoAYOVYBpEDSYoEtEQKUooPmh9gBh8gIREnNn+sK0KtOVhE/dlM+eev+8759ycGdBdXrFiV92uG3M8s4r7ukF3scfDzAYV5WUrHy4pXlhWBPIUPH3dy7pLe0qGV2OY1jNiEuGMaWDk7UrrBhZzwghlW4ZoQqxh0YBphEUii6lY/QaR9QExY5nElE2d8sarI5SKeAUAVVE4luMgwzpSY8xnkxmhlHCQYYGCBCCBUCDMOOcY2yhuYAINEqFYwIRpwNKs0MSERI4XGcYHeKGZ8m5GFtZMw1HxASqaS1fM2Vp5ud49VYgxsojjhIrGY7Wpxli8uqahabU/z1d0lIcUgcTGE5/Wmgryboa6je4eBue0xZQtywhjyh8diTDRqRgbS2Ya6eeoFhDLynyYVVQpIIdDoQdCZa1ppSG5ex6uRFNoNacqIoNoJHsvRh02pDYkk9GnBsdFvNrr/m20oa6pGrIiVE1VbOumVE2S8qYSCcvs0BSkuEiZMBtiOEFgGCqqWDBj0mFuNMaIo1GGJwVZaxqK5vKFvQ0mqUJOwmgyLWweLY5So9FoxVTiJpOnx4Jx+gLNbj1HCmiTVsMtKUo7HHhzj/cmf6wb7tT/QfUDK6iswIYEiZXZUCAg/Xs/uLM+tZ6IumWJJRJ+NxckwSydhlY7IhkdyoiWHXrtNLI0ReQCKsuFVEQrwbBK82FVpaWAEqQZFSGAkCQ5Xfo/aQ1CLE2yCRpvj8kHOXwRyqVT1KAqErMdGU3ZDKIma+YunNGe6MIRqpWQjOj3d3Z2+jo5n2m1+FkAGP+W+g0puRWlITWuq91bmdZy3SEjxwprInESiFBdTvM5wY0WKpqsqU3WpOq2NTWur2kYa9wJmUUnS/8DaUo2Myhh6pqcLSyInKUkoEWyKaTrjmBGILELsnDgubPuQnR9YMcJzGg+t+N8spn2m9C5sFzRtlzW3vtR8mOHJN/I+Dtc+SwEFdPQs9MxnoKNZnQ4I2Ra2ekEHDeegg2UZdM2yHTCjZpOwUK1dVXTdfeWmE7APPOppGlAPUs0GY+HnFHjxzKZeDptEyjpKK4UzgTkBjzEB4P8jOEVGKpqaGhIT0G6pgumMzpyZ51OJKtpgQmiQECAPO3sAZBhwjODXt+iFRhyJiiwPODZEACAmxG2atRRaGWVEeCALAs0zwuA5iWVp8OCEHQWKWfLQSpUw9zM6rlW15ybovA2jToTE6TMDJqzDRcWKHcex8YxAKUQzUIpTPOMJNMSw3PODqlK9ws5T1AKJu2W/3ij8E98m48W5X5Mj6cf9Hg+KvZ4gB88yywFz5SXbCoteWgh1gjyOXuoD2sthvOSaiFfO8pmoGYVl3teWHTkvW153w/6XgRPjH9BqChhZud9TgCL7pyUMY88PocJA5YVmBDHM0wzWHrntJSpLF2gfKtvT52vf3O4v+9r+9X4c98/dbkXzBlX8njKikp7PEU/pD/zxn+59caZg4/uHVzQs/vw/tKh7Ln4z3v1WbvYeU8vPv/Kut7fjwye+PSnI0s+uFhtXrPfPX3zwsl0Gek/s/86Wf7yj3siX32y+4J48/Vm382Bc4eW7fn8+pPL3yHoYORkjO1+H5O6xKGN605t+W6o6o/WfTg794B1tuzK3LbjC/e9lvm1vXL9n+c7v3zp2HDb1VW9j10aqFt/dXGr59Lbtw+sogMD8+YOnEpt93+xYP7io7ul2bfWtPy2mTP7L1/cfmX1irbDS1Y0r2m/zVUOn0bJ+Rt6T6xMXH9rXcWSVcd7hi7uWBaSdppD9tGqs/bpA/OSmzbuqLwRDf01uFX5Bl5rf/7DnR8f6xgcKd/fPPSP4tkRAAA=",
    "Content-Type: application/json",
    "cache-control: no-cache"
    ),
  ));

   $response = curl_exec($ch);
   curl_close($ch);

   $response = json_decode($response);

    //print_r($response);
    $auctionEndTime  = $response->Item[0]->EndTime;
    $listingStatus = date("Y-m-d H:i:s", $response->Item[0]->ListingStatus);
    //print_r($auctionEndTime);
    //print_r($listingStatus);
    $sql = '';
    $sql .=  "INSERT INTO auctions (itemID, productName, highestBid, currency, thumbnailPhotoURL, sellerUsername, sellerFeedbackPercentage, itemCondition, bidCount,  auctionEndTime, buyingOptions, listingStatus) values
                                  (\"$itemId\", \"$productName\", $highestBid, \"$currency\",\"$thumbnailPhotoURL\", \"$sellerUsername\", $sellerFeedbackPercentage, \"$itemCondition\", $bidCount, \"$auctionEndTime\", \"$buyingOptions\", \"$listingStatus\"); ";
    print_r($sql);


    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    echo "<br><br><br><br><br>";
  }
}
?>
