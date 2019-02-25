<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging

$clientID = "DanielSa-Example-SBX-4a6d0a603-2941e542";
$clientSecret = "SBX-a6d0a603000a-4767-4066-9526-d574";
$ruName = "Daniel_Savu-DanielSa-Exampl-gpsjh";
$authCode = "v^1.1#i^1#f^0#r^0#p^3#I^3#t^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";


$req_url = "https://auth.sandbox.ebay.com/oauth2/authorize?client_id=DanielSa-Example-SBX-4a6d0a603-2941e542&response_type=code&redirect_uri=Daniel_Savu-DanielSa-Exampl-gpsjh&scope=https://api.ebay.com/oauth/api_scope";

echo "the received code is:\n";
echo $_GET['code'];
echo "<br>";


// //The url you wish to send the POST request to
$url = "https://api.sandbox.ebay.com/identity/v1/oauth2/token";

echo urlencode($_GET['code']);
echo "<br>";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "grant_type=authorization_code&code=" .  urlencode($_GET['code']) . "&redirect_uri=" . $ruName,
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic " . base64_encode($clientID.':'.$clientSecret),
    "Content-Type: application/x-www-form-urlencoded",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}



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
