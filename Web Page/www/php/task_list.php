<?php

require_once 'task_class.php';
require_once 'db_connect.php';

function listByCategory($incatid)
{
	$tasks = array();
	
	$dbhandle = $db_connect();
	
	if ($incatid = NULL)
		$query = "SELECT * FROM Tasks";
	else
		$query = "SELECT * FROM Tasks WHERE Category={$incatid}";
		
	$result = $dbhandle->query($query);
	while ($row = $result->fetch_array())
	{
		$newtask = new task();
		$newtask->getFromDB($row['TaskID']);
		array_push($tasks, $newtask);
	}
		
	return $tasks;
}

?>