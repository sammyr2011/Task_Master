<?php

require_once 'task_class.php';
require_once 'db_connect.php';

function listByCategory($incatid)
{
	
	$tasksarray = array();
	echo 'test1';
	$dbhandle = $db_connect();
	echo 'test2';
	$query = "SELECT TaskID FROM Tasks";
	
	if ($incatid != 0)
		$query = "SELECT TaskID FROM Tasks WHERE Category={$incatid}";
	
	echo 'test3';
	$result = $dbhandle->query($query);
	echo 'test4';
	while ($row = $result->fetch_array())
	{
		$newtask = new task();
		$newtask->getFromDB($row['TaskID']);
		array_push($tasksarray, $newtask);
	}
	
	$dbhandle->close();
	
	return $tasksarray;
}

?>