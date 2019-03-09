<?php
function updateAreaChart($itemID){
    $conn = new mysqli("ebayer.mysql.database.azure.com", "dragos@ebayer", "CDDG_databosses", "ebayer");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "select * from product_timestamp_junction where itemID = \"$itemID\";";
    $timestamps_sql = "select * from timestamps;";

    $timestamps_results = $conn->query($timestamps_sql);
    $result = $conn->query($sql);

    $timestamps = array();
    $datetime_treshold = date_create('2009-10-11'); // check for empty or invalid dates in poluted table

    while($row = $timestamps_results->fetch_assoc()){
        $date_check = date_create($row['bidTime']);
        if ($date_check > $datetime_treshold){ $timestamps[$row['timeID']] = $row['bidTime']; }
    }

    $data = array();
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            if (!empty($timestamps[$row['timestampID']])) {$data[$timestamps[$row['timestampID']]] = $row['highestBid'];}
        }
    }
    return http_build_query($data);
}

$req = $_POST['action'];
$itemID = $_POST['itemID'];
//$req = "update";
//$itemID = "362574801322";
if($req == "update" ){
    $result = updateAreaChart($itemID);
    echo $result;
}else{
    $error = 'req is not update';
    throw new Exception($error);
}
?>