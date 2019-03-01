<?php

// $conn = new mysqli("ebayer.mysql.database.azure.com", "dragos@ebayer", "CDDG_databosses", "ebayer");
// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

$curl = curl_init();

$endpoint = 'https://api.ebay.com/buy/browse/v1/item_summary/search?';  // URL to call

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
    "Authorization: Bearer v^1.1#i^1#f^0#p^3#r^0#I^3#t^H4sIAAAAAAAAAOVYb2wTZRhft24w2QQTFLIssR6oRL32vev9aQ9aU9bONbKtruXPEJjv3b23nVzv6t11W9UPS1ESAZ3wgYCasExjgiwECYofUCMGQwwQMBEhGk1ADQh+kJhgRMT3uj90IwLbSFxivzT3vs+/3/P8nvee90BPReUj6xvWX652TSvt6wE9pS4XNQNUVpQ/endZaU15CSgScPX1zO9x58vOLbJgWssILcjKGLqFPN1pTbeEwmKIyJq6YEBLtQQdppEl2JKQjDQuEWgvEDKmYRuSoRGeeDREIFGSghQbCPoZilYkEa/qwzZTRohgmQAQISspMhvkWdGP9y0ri+K6ZUPdDhE0oIIkoEmaS9FAoCgBsN4AH1hJeJYh01INHYt4AREuhCsUdM2iWG8eKrQsZNrYCBGOR+qTzZF4NNaUWuQrshUeykPShnbWGv1UZ8jIswxqWXRzN1ZBWkhmJQlZFuELD3oYbVSIDAczgfALqZb4gBJEFCMGgAIUyN2RVNYbZhraN4/DWVFlUimICki3VTt3q4zibIjPIskeemrCJuJRj/P3VBZqqqIiM0TEFkdalyZjLYQnmUiYRqcqI9lBivkUoGjKD3girBpQJ2Woq8iCnW1gyNWgvaFEj/FVZ+iy6qTN8jQZ9mKE40ZjswOKsoOFmvVmM6LYTkzFcvRIFpmVTlkH65i1O3SnsiiNU+EpPN66BsOkuE6DO0WLAOQoRpICMk3zFCbGGFo4vT4haoSd6kQSCZ8TCxJhjkxDcy2yMxqUECnh9GbTyFRlwc8qtD+gIFLmggrJBBWFFFmZIykFIYCQKErBwP+LIbZtqmLWRiMsGbtRgOkcnjAnqFARbGMt0lO5DCLGShaOnyFqdFshosO2M4LP19XV5e3yew2z3UcDQPlWNC5JSh0oDYkRWfXWwqRaIImEsJalCjYOIER0Yw5i53o7EW6J1bfEkg1tqeYnY03D/B0VWXjs6r8gTUpGBiUMTZVyUwui35QT0LRzSaRpeGFSIC0H5H8Jz+n1GyE6NixsBGZUr8M4r2SkfQbE55az1FaI2nM7Qj4LJ8k7eApgy14TQdnQtdxElMeho+qduIUMMzcRhyPK49CBkmRkdXsi7oZUx6GhZDVF1TTnlJiIwyL18YSpQy1nq5I14nJSxI9kMvF0OmtDUUNxeYo1eIDhOGbS8KYYqih0eh2/8rQkJGPdMJ3REJloiZI8xSGW5SFD0hINKSo4OeiN7eoUQ05xHEtzDBXkAJgctijqnGpllRDwA0niSYbhAcmICkMGeZ7D8xTl55ECFXzNmhTmOk3FJ8XUmzQaDMtG8uSg4aF4aoFy+nG4HVkoBkgaikGSoUSJFCnGj2dIRbwtyO7wjVkomi1vuFj4Rt/twyWFH5V3fQDyrvdLXS7gAw9S88ADFWVL3WVVNZZqIy+eQ72W2q7jK6uJvGtRLgNVs7TC9XTtnp1tRV8T+laDuSPfEyrLqBlFHxdA7fWdcmrmnGoqCGiawxMzBdiVYN71XTd1n3v25v6LL/kPP1O/7tyegx9Vbl1Vvc3uBdUjQi5XeYk77yqZc2/TyyVXPjmmnFy3IQpjSzbXbjlyfuDzfPv0A9WhU7u2zJ0mPPziF79vnD2H+5Q40Hu0d032IPXa7sea3Sl+14kXcqsW1K0aeP2Hs9/rJyXUUc3fH992ofMXY/fen/ULRzZxG56P9n0n7L/2Ck1X5U/Ud8+7Z9nqmj8CinKldk1v769v8Z4n/n5oX/r8zjNfLrxY8l6V8vVdfbXza2l2/8wz114V3wGPu/sbt59OHTl7auGsr7KHLvzGtg6c/3C5R/o2sUJszU8/vau/55h++O3nUFXj3j/7f5w7a9+C49s3fbax7MDlK+820D/tOHR8xxuurX+1Le++FGnNf3P0zat7cwNnz6Qurb708dXqwfL9A8901yPnEQAA",
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
  // echo $resp;
  echo "<br><br><br><br><br>";
  $resp = json_decode($resp);
  // var_dump($resp);
  echo count($resp->itemSummaries);
  echo "<br><br><br><br><br>";

  print_r($resp->itemSummaries[0]);
  foreach($resp->itemSummaries as $item) {
    // print_r($item['bidcount']);
    echo $item['bidcount'];
    echo "<br><br><br><br><br>";
  }
  // for ($i = 0; $i < count($resp->itemSummaries); $i++) {
  //   echo $resp->itemSummaries[0];
  //   echo "<br><br><br><br><br>";

  // }
  // foreach($resp->itemSummaries as $item) {

  //   echo $item;
//   // }
//   // print_r($resp);
//   echo "<br><br><br><br><br>";
}


?>