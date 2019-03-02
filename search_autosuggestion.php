
<?php

// Database configuration
$dbHost     = 'ebayer.mysql.database.azure.com';
$dbUsername = 'dragos@ebayer';
$dbPassword = 'CDDG_databosses';
$dbName     = 'ebayer';

// Connect with the database
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Get search term
$searchTerm = $_GET['term'];

// Get matched data from skills table
$query = $db->query("SELECT itemName FROM items WHERE itemName LIKE '%".$searchTerm."%' ");

// Generate skills data array
$skillData = array();
if($query->num_rows > 0){
    while($row = $query->fetch_assoc()){
        $data['id'] = $row['id'];
        $data['value'] = $row['itemName'];
        array_push($skillData, $data);
    }
}

// Return results as json encoded array
echo json_encode($skillData);
?>
