<?php
function getHistogram($query){
    $conn = new mysqli("ebayer.mysql.database.azure.com", "dragos@ebayer", "CDDG_databosses", "ebayer");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $sql = "select highestBid from items where LOWER(itemName) like '%".strtolower($query)."%';";
    $data = array();
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            array_push($data, $row['highestBid']);
        }
    }
    return implode(" ", $data);
}
$req = $_POST['action'];
$query = $_POST['query'];
$try = $_POST['try'];
if($req == "update" ){
    $result = getHistogram($query);
    echo $result;
}else{
    $error = 'req is not update';
    throw new Exception($error);
}
?>
