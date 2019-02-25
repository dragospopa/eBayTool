<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging

$clientID = "DanielSa-Example-SBX-4a6d0a603-2941e542";
$clientSecret = "SBX-a6d0a603000a-4767-4066-9526-d574";
$ruName = "Daniel_Savu-DanielSa-Exampl-gpsjh";
$authCode = "v^1.1#i^1#f^0#r^0#p^3#I^3#t^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";


// echo base64_encode($clientID.':'.$clientSecret);
// echo "</br>";

$req_url = "https://auth.sandbox.ebay.com/oauth2/authorize?client_id=DanielSa-Example-SBX-4a6d0a603-2941e542&response_type=code&redirect_uri=Daniel_Savu-DanielSa-Exampl-gpsjh&scope=https://api.ebay.com/oauth/api_scope";
// $conskey = $clientID;
// $conssec = $clientSecret;
// $oauth = new OAuth($conskey,$conssec,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);
// $oauth->enableDebug();
// $request_token_info = $oauth->getRequestToken($req_url);
// echo $request_token_info['oauth_token_secret'];

// $authCode = $request_token_info['oauth_token_secret'];
echo "the received code is:\n";
echo $_GET['code'];



//The url you wish to send the POST request to
$url = "https://api.sandbox.ebay.com/identity/v1/oauth2/token";

$headers = [
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Basic '.base64_encode($clientID.':'.$clientSecret),
];

//The data you want to send via POST
$fields = [
    'grant_type '     =>  'authorization_code',
    'code'            =>  $_GET['code'],
    'redirect_uri'    =>  $ruName
];

//url-ify the data for the POST
$fields_string = http_build_query($fields);

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

//execute post
$result = curl_exec($ch);
echo $result;
















// $q = "iphone";
// $url = "https://api.sandbox.ebay.com/buy/browse/v1/item_summary/search?q=" . $q . "&limit=3";

// $feed_scope = "NEWLY_LISTED";
// $category_id = "625";
// $headers = [
//     'Authorization: Bearer '.$request_token_info['oauth_token_secret'],
//     // 'Accept:application/json',
//     'Content-Type:application/json',
//     'X-EBAY-C-MARKETPLACE-ID:EBAY_GB',
// ];
// $curl = curl_init();
// curl_setopt_array($curl, array(
//     CURLOPT_URL            => $url,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_CUSTOMREQUEST  => 'GET',
//     CURLOPT_HTTPHEADER     => $headers
// ));

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//     echo "cURL Error #:" . $err;
// } else {
//   // $json_string = json_encode($response, JSON_PRETTY_PRINT);
//   // header('Content-Type: application/json');
//     echo $response."\n";
// }

// echo base64_encode($clientID.':'.$clientSecret);
// $response = http_get($api_URL . "?feed_scope=" . $feed_scope . "&category_id=" . $category_id, 
//   array("headers" =>
//     array("X-EBAY-C-MARKETPLACE-ID" => "EBAY_GB", 
//       "Authorization" => "Basic " . base64_encode($clientID.':'.$clientSecret))), 
//   $info);
?>

<!-- Build the HTML page with values from the call response -->
<html>
<head>
<title>eBay Search Results for ye</title>
<style type="text/css">body { font-family: arial,sans-serif;} </style>
</head>
<body>

<!-- <h1>eBay Search Results for ye</h1> -->

<table>
<tr>
  <td>
  </td>
</tr>
</table>

</body>
</html>
