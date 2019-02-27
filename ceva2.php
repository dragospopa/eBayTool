eva </p>
</html>

<?php

$clientID = "GeorgiaP-Databoss-PRD-a393cab7d-302f338e";

$apicall= 'http://open.api.ebay.com/shopping?'

$apicall .= "&callname=GetItemStatus&";
$apicall .= "responseencoding=XML&";
$apicall .= "appid=$clientID&";
$apicall .= "siteid=0&";
$apicall .= "version=967&";
$apicall .= "ItemID=180126682091";;



$curl = curl_init();


$ch = curl_init($apicall);

if ($method == 'GET'){
      curl_setopt($ch, CURLOPT_URL, $apicall);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
} else {
      $options = array(
          CURLOPT_HEADER => true,
          CURLINFO_HEADER_OUT => true,
          CURLOPT_VERBOSE => true,
          CURLOPT_HTTPHEADER => null,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_POSTFIELDS => null,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_TIMEOUT => 3
      );
      curl_setopt_array($ch, $options);
}

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;

?>

