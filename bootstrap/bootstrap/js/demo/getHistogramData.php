<?php
function getHistogram($query){
    $conn = new mysqli("ebayer.mysql.database.azure.com", "dragos@ebayer", "CDDG_databosses", "ebayer");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $sql = '';
    $sql .= "SELECT * FROM items";  
    //$sql .= "select * from items where itemName like " ."%".$query."%;"; 
    //$sql .= "select * from items where itemName like '%$query%';";
    $data = array();
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            $in = strpos(strtolower($row['itemName']),strtolower($query));
            if( $in !== false){
                array_push($data, $row['highestBid']);
            }
            //array_push($data, $row['highestBid']);
        }
    }
    return implode(" ", $data);
    $maxVal = max($priceDistribution);
    $minVal = min($priceDistribution);
    $binWidth = 20;
    $roundedData = array();
    foreach($data as $price){
        $val = round($price / $binWidth) * $binWidth;
        array_push($roundedData, $val);
    }
    $histogram = array_count_values(array_map('intval', $roundedData));
    return implode(" ", $roundedData);
    
}
$req = $_POST['action'];
$query = $_POST['query'];
$try = $_POST['try'];
if($req == "update" ){
    $result = getHistogram($query);
    echo $result;
}else{
    echo "1 2 3 4";
}
// $result = getHistogram();
// foreach($result as $elem){
//     echo "<br>";
//     echo $elem;
// }
// echo $result;
?>
