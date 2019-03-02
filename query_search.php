<? php
function get_recommendations($input)
{
  $conn = new mysqli("ebayer.mysql.database.azure.com", "dragos@ebayer", "CDDG_databosses", "ebayer");

  $return_array = [];
  $string_array = explode(" ", $input);

  print_r($string_array);

  if(empty($string_array))
    $string_array[]=$input;
  //print_r($input);

  foreach($string_array as $search_substring)
  {
    $query_sql = "SELECT itemName from items WHERE itemName LIKE CONCAT('%',\"$search_substring\",'%')";
    $query_resp = $conn->query($query_sql);
    if ($conn->query($query_sql) == FALSE){echo "Error: " . $query_sql . "<br>" . $conn->error; continue; }

    while($row = $query_resp->fetch_assoc())
    {
      $return_array[] = $row;
    }
  }

  print_r($return_array);

  return $return_array;
}
?>
