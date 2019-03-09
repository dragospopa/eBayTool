<?php

	$dbHost     = 'ebayer.mysql.database.azure.com';
	$dbUsername = 'dragos@ebayer';
	$dbPassword = 'CDDG_databosses';
	$dbName     = 'ebayer';

	$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

	$searchTerm = $_GET['term'];

	$query = $db->prepare("SELECT itemName FROM items WHERE itemName LIKE ? ");
	$value = "%$searchTerm%";
	$query->bind_param("s", $value);
	$query->execute() or trigger_error($query->error, E_USER_ERROR);

	$query_result = $query->get_result();

	$autosuggestions = array();
	if($query_result->num_rows > 0){
	    while($row = $query_result->fetch_assoc()){
	        $data['id'] = $row['id'];
	        $data['value'] = $row['itemName'];
	        array_push($autosuggestions, $data);
	    }
	}

	echo json_encode($autosuggestions);
?>
