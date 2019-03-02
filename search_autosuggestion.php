
<?php

$dbHost     = 'ebayer.mysql.database.azure.com';
$dbUsername = 'dragos@ebayer';
$dbPassword = 'CDDG_databosses';
$dbName     = 'ebayer';

$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

$searchTerm = $_GET['term'];

$query = $db->query("SELECT itemName FROM items WHERE itemName LIKE '%".$searchTerm."%' ");

$autosuggestions = array();
if($query->num_rows > 0){
    while($row = $query->fetch_assoc()){
        $data['id'] = $row['id'];
        $data['value'] = $row['itemName'];
        array_push($autosuggestions, $data);
    }
}

echo json_encode($autosuggestions);
?>
