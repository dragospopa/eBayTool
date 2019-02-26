<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging

// sandbox
$clientID = "DanielSa-Example-SBX-4a6d0a603-2941e542";
$clientSecret = "SBX-a6d0a603000a-4767-4066-9526-d574";
$ruName = "Daniel_Savu-DanielSa-Exampl-gpsjh";
$authCode = "v^1.1#i^1#f^0#r^0#p^3#I^3#t^H4sIAAAAAAAAAOVYW2wUVRju9gKpWOUWRKi6D

// production
// $clientID = "DanielSa-Example-PRD-716e557a4-2c2a1194";
// $clientSecret = "PRD-16e557a45ab8-2ab9-41bc-b143-02fb";
// $ruName = "Daniel_Savu-DanielSa-Exampl-lwxtsaiw";


echo "the received code is:\n";
echo $_GET['code'];
echo "<br>";


// //The url you wish to send the POST request to
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
