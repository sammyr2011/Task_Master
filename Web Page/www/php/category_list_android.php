<!-- JSON encodes current list of categories -->
<?php
	require_once 'db_connect.php';
	$dbhandle = db_connect();
	$query = "SELECT * FROM Categories";
	$result $dbhandle->query($query);
  	$categories = array();
  
	while ($row = $result->fetch_array())
	{
		array_push($categories, $row);
	}
	
	$dbhandle->close();
	
	echo $categories;
	
	echo json_encode($categories);
?>
