<?php

require_once 'task_class.php';
require_once 'user_class.php';
require_once 'review_class.php';
require_once 'db_connect.php';

function listTasksByCategory($incatid)
{
	$tasks = array();

	$dbhandle = db_connect();
	$stmt = $dbhandle->stmt_init();
	
	if ($incatid != 0)
	{
		$stmt->prepare("SELECT TaskID, (? - EndDateTime) AS TimeRemaining FROM Tasks WHERE Category=? AND Active=1 ORDER BY TimeRemaining DESC");
		$stmt->bind_param("ii", time(), $incatid);
		
	}
	else
	{
		$stmt->prepare("SELECT TaskID, (? - EndDateTime) AS TimeRemaining FROM Tasks WHERE Active=1 ORDER BY TimeRemaining DESC");
		$stmt->bind_param("i", time());
	}
		
	$stmt->execute();
	
	$stmt->store_result();
	$stmt->bind_result($resultID, $temptime);

	while ($stmt->fetch())
	{
		$newtask = new task();
		$newtask->getFromDB($resultID);
		array_push($tasks, $newtask);
	}
	
	$stmt->close();
	$dbhandle->close();
	
	return $tasks;
}

function listTasksByUser($inuserid)
{
	$tasks = array();

	$dbhandle = db_connect();

	$query = "SELECT TaskID FROM Tasks WHERE Lister={$inuserid}";
	
	$result = $dbhandle->query($query);

	while ($row = $result->fetch_array())
	{
		$newtask = new task();
		$newtask->getFromDB($row['TaskID']);
		array_push($tasks, $newtask);
	}
	
	$dbhandle->close();
	
	return $tasks;
}

function listReviewsByTime($inuser)
{
	$reviews = array();
	
	$dbhandle = db_connect();
	
	$query = "SELECT RatingID FROM Ratings WHERE ListerID={$inuser} ORDER BY TimeStamp";
	
	$result = $dbhandle->query($query);
	
	while ($row = $result->fetch_array())
	{
		$newreview = new review();
		$newreview->getFromDBLister($row['RatingID']);
		array_push($reviews, $newreview);
	}
	
	$dbhandle->close();
	
	return $reviews;
}

function listDoReviewsByTime($inuser)
{
	$reviews = array();
	
	$dbhandle = db_connect();
	
	$query = "SELECT RatingID FROM DoRatings WHERE ResponderID={$inuser} ORDER BY TimeStamp";
	
	$result = $dbhandle->query($query);
	
	while ($row = $result->fetch_array())
	{
		$newreview = new review();
		$newreview->getFromDBDoer($row['RatingID']);
		array_push($reviews, $newreview);
	}
	
	$dbhandle->close();
	
	return $reviews;
}

?>
