<?php

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

include_once 'db_connect.php';
include_once 'user_class.php';
include_once 'task_class.php';


class review
{
	var $reviewee_uid;
	var $taskid;
	var $comment;
	var $rating;
	var $ratingid;
	var $timestamp;
	
	var $listerOrDoer;
	
	public function getFromPOST($info)
	{
		$error = array();
		
		$error = $this->initialize($info);
		
		if (count($error) != 0)
			return $error;
			
		$error = $this->register();
		
		if (count($error) != 0)
			return $error;
	}
	
	public function getFromDBLister($inratingid)
	{
		$error = array();
		
		$dbhandle = db_connect();
		
		$query = "SELECT * from Ratings WHERE RatingID = '{$inratingid}' LIMIT 1";
			
		$result = $dbhandle->query($query);
		if ($result->num_rows == 0)
		{
			$dbhandle->close();
			$error['ratingid'] = true;
			return $error;
		}
		
		$row = $result->fetch_array();
		
		$this->ratingid = $row['RatingID'];
		$this->taskid = $row['TaskID'];
		$this->rating = $row['Rating'];
		$this->comment = $row['Comment'];
		$this->timestamp = $row['TimeStamp'];
		
		$this->reviewee_uid = $row['ListerID'];
	}
	
	public function getFromDBDoer($inratingid)
	{
		$error = array();
		
		$dbhandle = db_connect();
		
		$query = "SELECT * from DoRatings WHERE RatingID = '{$inratingid}' LIMIT 1";
			
		$result = $dbhandle->query($query);
		if ($result->num_rows == 0)
		{
			$dbhandle->close();
			$error['ratingid'] = true;
			return $error;
		}
		
		$row = $result->fetch_array();
		
		$this->ratingid = $row['RatingID'];
		$this->taskid = $row['TaskID'];
		$this->rating = $row['Rating'];
		$this->comment = $row['Comment'];
		$this->timestamp = $row['TimeStamp'];
		
		$this->reviewee_uid = $row['ResponderID'];
	}
	
	//Adds this review to the database
	public function register()
	{
		$error = array();
		$dbhandle = db_connect();
		
		if ($this->listerOrDoer == false)
			$query = "INSERT INTO Ratings (TaskID, ListerID, Rating, Comment, TimeStamp) VALUES ({$this->taskid}, {$this->reviewee_uid}, {$this->rating}, '{$this->comment}', {$this->timestamp})";
		else
			$query = "INSERT INTO DoRatings (TaskID, ResponderID, Rating, Comment, TimeStamp) VALUES ({$this->taskid}, {$this->reviewee_uid}, {$this->rating}, '{$this->comment}', {$this->timestamp})";
		
		$result = $dbhandle->query($query);
		if (!$result)
			$error['query'] = true;
			
		//DEBUG
		$error['querycontent'] = $query;
		//DEBUG
		
		$dbhandle->close();
		
		return $error;
	}
	
	//Sets values from input
	//Verifies them to ensure validity
	public function initialize($info)
	{
		$error = array();
		
		//check if user exists
		$reviewee = new user();
		if (isset($info['reviewee_uid']) && $reviewee->checkExistence($info['reviewee_uid']))
			$this->reviewee_uid = $info['reviewee_uid'];
		else
			$error['reviewee_uid'] = true;
		
		//check if task exists
		$task = new task();
		if (isset($info['taskid']) && $task->checkExistence($info['taskid']))
			$this->taskid = $info['taskid'];
		else
			$error['taskid'] = true;
			
		//check if task is already rated
		
		//strip tags from comment before adding
		if (isset($info['comment']))
			$this->comment = strip_tags($info['comment']);
		else
			$error['comment'] = true;
		
		//check if rating is a number between 0 and 5 inclusive
		if (isset($info['rating']) && is_numeric($info['rating']) && $info['rating'] >= 0 && $info['rating'] <= 5)
			$this->rating = $info['rating'];
		else
			$error['rating'] = true;
		
		//1 if a Doer rating, 0 if a Lister rating
		//determine if Lister or Doer
		$task->getFromDB($this->taskid);
		$bidwinner = $task->getWinnerID();
		
		//If you're the winner, you are leaving a review for the Lister
		if ($_SESSION['userid'] == $bidwinner)
			$this->listerOrDoer = false;
		//If you're the lister, you are leaving a review for the Doer
		else if ($_SESSION['userid'] == $task->userid)
			$this->listerOrDoer = true;
		//Otherwise, you're not involved with the task at all 
		//and have no business leaving a review
		else
			$error['notinvolved'] =  true;
			
		$this->timestamp = time();
			
		return $error;
	}
	
}

?>
