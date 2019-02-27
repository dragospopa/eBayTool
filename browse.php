<?php
$conn = new mysqli("ebayer.mysql.database.azure.com", "dragos@ebayer", "CDDG_databosses", "ebayer");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$clientID = "GeorgiaP-Databoss-PRD-a393cab7d-302f338e";

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
curl_setopt_array($curl, array(
  CURLOPT_URL => $endpoint,
  CURLOPT_ENCODING => "",
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_RETURNTRANSFER => TRUE,
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer v^1.1#i^1#f^0#p^3#I^3#r^0#t^H4sIAAAAAAAAAOVYW2wUVRju9gYVCtHIJbWRZaoBMbN7ZmZ3Zndk1yxtoYu0XXYLCATLmZkz7dDZmWXObNs1SMqaNAYiVkETIomEGAL4IIgYIzFRA/WSoJEHhBceNEYwMUiCoUATPbO9sC0KtOVhE/dlM//5b993/v/kPwf0lFcs6W3ovVHpmlZ8oAf0FLtczAxQUV727KyS4qqyIpCn4DrQ81RPabbk8lIMk3pKjCOcMg2M3N1J3cBiThii0pYhmhBrWDRgEmHRlsVEpHGVyHqAmLJM25RNnXJH60KUAvmAqvhZpEicqnISkRojPlvMEMUyrMIxiPULiuLnkY+sY5xGUQPb0LDJOmCCNGBpVmhh/CIHRI73BLjgBsq9FllYMw2i4gFUOJeumLO18nK9d6oQY2TZxAkVjkaWJ5oj0br6ppal3jxf4WEeEja003jsV62pIPdaqKfRvcPgnLaYSMsywpjyhocijHUqRkaSmUT6Oap9As8jyLJBEBQQF+AfCpXLTSsJ7Xvn4Ug0hVZzqiIybM3O3I9Rwoa0Bcn28FcTcRGtczt/q9NQ11QNWSGqfllk/ZpEfZxyJ2Ixy+zUFKQ4SJkgA/wc4FmBCrchM2X5BYEdDjLkaZjicVFqTUPRHMKwu8m0lyGSMRrPC5vHC1FqNpqtiGo72eTrCaP88RucDR3awbTdbjh7ipKEBHfu8/7sj5TDnQJ4WAWhSj6GFRSek3lB5gKBfy0Ip9cnWBRhZ18isZjXyQVJMEMnodWB7JQOZUTLhN50ElmaInJ+leUCKqIVPqjSvqCq0pJf4WlGRQggJElyMPB/qQ3btjQpbaPR+hi/kAMYohw+RQ2qom12IKMlk0LUeM3ckTNcFN04RLXbdkr0eru6ujxdnMe02rwsAIz3xcZVCbkdJSE1qqvdX5nWcuUhI2KFNdEmCYSoblJ9JLjRRoXj9cvj9YmG1pbmF+qbRip3TGbh8dL/QJqQzRSKmbomZwoLImcpMWjZmQTSdSKYEkjsgCwUeE6vD0F0fGDiBKY0j1NxHtlMek1ITixH1JrL2v0gSl5MSPIM9T/x7LEQVExDz0zGeAI2mtFJWsi0MpMJOGo8ARsoy2basCcTbth0AhZqWlc1XXdOickEzDOfSJoG1DO2JuPRkFMq/EgqFU0m0zaUdBRVCqUDhhs8AECQnzK8AkO1AhEbDcboOkhYd3rdxJiOxetoyAU5GUqCQnOAVTkugKaEvbFNKzDoDM8yPgYwvI+YTQlbHeostH0NMD5WkJGPhpIP0j4FIVpSAgLNBgJIZiUOABVOCXOtrpGjovBGjQYT20iZGjQyDxcWKKcfR9qRYSVAA8gB2seSARnKLEtLqiw8KOQRQSl393B5153CO/ZCHy7K/Zis6yTIuo4Xu1zAC55masDC8pI1pSUzq7BmIw8ZRD1YazPIPdVCng6USUHNKi53baw+dqQ17wnhwCYwf/QRoaKEmZH3ogCq76yUMbPnVTJBwLICQ6Z0MiGDmjurpczc0sfji47Dj3dt93/Udebt03+jJ6ZXzgyCylEll6usqDTrKqptam38cee2g98f6u4Nzbpy9b3ORwfO/7H35Ot9A/u6z2ptc/+SLszaPv3q2cXXvxpouDh4qOnb/nepJ1XPF95Q/8GeL1e+dT6FHzs3/8JW1zvgu6rBFdMjs29/dlOsmpPcf2vauu7PDzeeWdB+rfjVwa8/SE5rHuysPlEjXDzM6RXxSqoj05R9f8nMvk8u37rWVb2gY+PNX/f0PrL45c0D2pK+PUd2rFy3Wn2tZvZWftfFyN7z3/y55+i5+Lz9cy41tvbu7uu8Hl3keuWSp1/c9ObRTdnnP/x0Z+3GfnPHjVP7Tl9Z+MOJll3bfj/z06mbx0q3nOt7Jttd9NJzMXG9X9l8ZMYvv6VuH/z50nz5jWVD2/cPSAUlo9wRAAA=",
    "Content-Type: application/json",
    "cache-control: no-cache"
  ),
));
$resp = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
echo "hi<br>";
if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $resp = json_decode($resp);
  print_r($resp->itemSummaries[0]);
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
    //$auctionStartTime = '2000-01-01 00:00:00';
    //$auctionEndTime   = '2020-01-01 00:00:00';
    if (count($item->buyingOptions)>1) {
      $buyingOptions = 3;
    } else if ($item->buyingOptions[0] == "AUCTION"){
      $buyingOptions = 1;
    } else {
      $buyingOptions = 2;
    }
    //print_r($item->currentBidPrice->value);
    //print_r($thumbnailPhotoURL);

    //Shopping api

    $apicall  = "$endpoint?";
    $apicall .= "callname=GetItemStatus&";
    $apicall .= "responseencoding=XML&";
    $apicall .= "appid=$clientID&";
    $apicall .= "siteid=0&";
    $apicall .= "version=967&";
    $apicall .= "ItemID=$itemID";

    $resp_shop = simplexml_load_file($apicall);


    foreach($resp_shop->Item as $item) {
      $auctionEndTime  = $item->EndTime;
      $listingStatus = $item->ListingStatus;
    }

    $sql = '';
    $sql .=  "INSERT INTO auctions (itemID, productName, highestBid, currency, thumbnailPhotoURL, sellerUsername, sellerFeedbackPercentage, itemCondition, bidCount,  auctionEndTime, buyingOptions, listingStatus,) values
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
