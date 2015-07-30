<?php

require_once 'task_class.php';
require_once 'user_class.php';
require_once 'review_class.php';
require_once 'db_connect.php';

function listTasksByCategory($incatid)
{
	$tasks = array();

	$dbhandle = db_connect();

	$query = "SELECT TaskID FROM Tasks";
	
	if ($incatid != 0)
		$query = "SELECT TaskID FROM Tasks WHERE Category={$incatid}";
	
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

function listTasksByTags($incatid)
{
	$tasks = array();

	$dbhandle = db_connect();

	$query = "SELECT TaskID FROM Tasks";
	
	if ($incatid != 0)
		$query = "SELECT TaskID FROM Tasks WHERE Category={$incatid}";
	
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
		$newreview->getFromDB($row['RatingID'], 0);
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
		$newreview->getFromDB($row['RatingID'], 1);
		array_push($reviews, $newreview);
	}
	
	$dbhandle->close();
	
	return $reviews;
}

?>