
<?php
	require_once 'db_connect.php';
	$dbhandle = db_connect();
	$query = "SELECT * FROM Categories";
	$result = $dbhandle->query($query);
  	$categories = array();
  	
  
  
	while ($row = $result->fetch_array())
	{
		$task = new task();
		$task->getFromDB($row["TaskID"]);
		$row["currentbid"]=$task->getCurrentBid();
		array_push($categories, $row);
	}
	
	$dbhandle->close();
	
	echo json_encode($categories);
?>
