<?php

include_once 'db_connect.php';
include_once 'user_class.php';
include_once 'task_class.php';

class review
{
	var $reviewee_uid;
	var $taskid;
	var $comment;
	var $rating;
	
	var $listerOrDoer;
	
	public function __construct($info)
	{
		$error = array();
		
		$error = $this->initialize($info);
		
		if (count($error) != 0)
			return $error;
			
		$error = $this->register();
		
		if (count($error) != 0)
			return $error;
	}
	
	//Adds this review to the database
	public function register()
	{
		$error = array();
		$dbhandle = db_connect();
		
		if ($this->listerOrDoer == 0)
			$query = "INSERT INTO Ratings (TaskID, ListerID, Rating, Comment) 
					VALUES ({$this->TaskID}, {$this->reviewee_uid}, {$this->rating}, {$this->comment})";
		else
			$query = "INSERT INTO DoRatings (TaskID, ResponderID, Rating, Comment) 
					VALUES ({$this->TaskID}, {$this->reviewee_uid}, {$this->rating}, {$this->comment})";
		
		$result = $dbhandle->query($query);
		if (!$result)
			$error['query'] = true;
		
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
		if (isset($info['rating']) && is_numeric($info['rating']) && $info['rating']) >= 0 && $info['rating']) <= 5)
			$this->rating = $info['rating'];
		else
			$error['rating'] = true;
		
		//1 if a Doer rating, 0 if a Lister rating
		if (isset($info['listerOrDoer']) && is_bool($info['listerOrDoer']))
			$this->listerOrDoer = $info['listerOrDoer'];
		else
			$error['listerOrDoer'] =  true;
			
		return $error;
	}
}

?>