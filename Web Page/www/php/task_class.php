<?php

require_once 'db_connect.php';

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

class task
{
	var $taskid;
	var $userid;
	var $title;
	var $description;
	var $content;
	var $location;
	var $category;
	var $tags;
	var $numimg;
	var $price;
	var $currentbid;
	var $active;
	var $enddatetime;
	var $winnerid;
	
	//Takes fields from POST and stores them in user object
	public function createFromPost($info)
	{
		//task info
		if (isset($_SESSION['userid'])) $this->userid = $_SESSION['userid'];
		
		if (isset($info['title'])) $this->title = $info['title'];
		
		if (isset($info['description'])) $this->description = $info['description'];
		
		if (isset($info['content'])) $this->content = $info['content'];
		
		if (isset($info['location'])) $this->location = $info['location'];
		
		if (isset($info['price'])) $this->price = $info['price'];
		
		//metadata
		if (isset($info['category'])) $this->category = $info['category'];
		
		if (isset($info['tags'])) $this->tags = $info['tags'];
		
		if (isset($info['numimg'])) $this->numimg = $info['numimg'];
		
		if (isset($info['enddatetime'])) $this->enddatetime = $info['enddatetime'];
	}
	
	//Gets info of a task stored in the database
	public function getFromDB($intaskid)
	{
		$errors = array();
		
		//Fill out fields from database	
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
		
		$stmt->prepare("SELECT TaskID, Lister, Title, Description, Information, Location, Category, Tags, NumImages, InitialBid, Active, EndDateTime FROM Tasks WHERE TaskID = ? LIMIT 1");
		$stmt->bind_param("i", $intaskid);
		$stmt->execute();
		
		$stmt->bind_result($this->taskid, $this->userid, $this->title, $this->description, $this->content, $this->location, $this->category, $this->tags, $this->numimg, $this->price, $this->active, $this->enddatetime);
		$stmt->store_result();
		$stmt->fetch();
		
		//return an error if this taskid was not found
		if($stmt->num_rows == 0)
		{
			$errors['taskid'] = true;
			$stmt->close();
			$dbhandle->close();
			return $errors;
		}
		
		$stmt->close();
		$dbhandle->close();
		
		//close connection and return 0
		return NULL;
	}
	
	//Registers new task into the database
	public function register()
	{
		//validate that all fields are filled and proper
		$errors = $this->validate();
		if ($errors != NULL)
			return $errors;
		
		//insert task into database
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
		
		$stmt->prepare("INSERT INTO Tasks
		(
			Title,
			Description, 
			Location,
			Category,
			Tags,
			EndDateTime,
			Lister,
			NumImages,
			Information,
			InitialBid,
			Active
		) 
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
		$stmt->bind_param("sssisiiisi", $this->title, $this->description, $this->location, $this->category, $this->tags, $this->enddatetime, $this->userid, $this->numimg, $this->content, $this->price);
		$stmt->execute();
		$stmt->store_result();
		
		$this->taskid = $dbhandle->insert_id;
		
		//close connection and return 0
		$stmt->close();
		$dbhandle->close();
		return NULL;
	}
	
	//Validates all info fields to make sure they are of proper format and all filled out
	public function validate()
	{
		$errors = array();
		
		//make sure user is logged in
		if (empty($this->userid)) 
		{
			$errors['userid'] = true;
		}
		
		//verify all info is provided
		if (isset($this->title))
			$this->title = strip_tags($this->title);
		else
			$errors['title'] = true;
			
		if (isset($this->description))
			$this->description = strip_tags($this->description);
		else
			$errors['description'] = true;
			
		if (isset($this->content))
			$this->content = strip_tags($this->content, "<br><b>");
		else
			$errors['content'] = true;
			
		if (isset($this->location))
			$this->location = strip_tags($this->location);
		else
			$errors['location'] = true;
		
		if (!isset($this->category))
			$errors['category'] = true;
			
		if (!isset($this->enddatetime))
			$errors['enddatetime'] = true;
		
		
		//If we made it here, all is valid
		if (count($errors) > 0)
			return $errors;
		else
			return NULL;
	}
	
	public function uploadImg($images)
	{
		$errors = array();
		
		$allowedext = array("jpg", "jpeg");
		
		$imgindex = 0;
		
		$numimg = count($images['tmp_name']);
		
		foreach($images['tmp_name'] as $key=>$file_temp)
		{
			$file_ext = pathinfo($images['name'][$key], PATHINFO_EXTENSION);
			
			if (in_array($file_ext, $allowedext))
			{
				$folderpath = "/var/www/html/taskcontent/".$this->taskid."/";
				
				if (!is_dir($folderpath)) 
					mkdir($folderpath,0777,true);
				
				if (!move_uploaded_file($file_temp,$folderpath.$imgindex.".".$file_ext))
					$errors['imgupload'] = true;
			}
			
			$imgindex++;
		}
		
		//If we made it here, all is valid
		if (count($errors) > 0)
			return $errors;
		else
			return NULL;
	}
	
	public function addBid($bidderid, $bidamount)
	{
		$error = array();
		
		$currentbid = $this->getCurrentBid();
		$leader = $this->getBidLeaderID();
		
		//task bidding has ended
		if ($this->isPastBidTime() == true)
		{
			$error['active'] = true;
			return $error;
		}
			
		//must bid lower than current bid
		if ($bidamount >= $currentbid)
		{
			$error['bidamount'] = true;
			return $error;
		}
		
		//bid can't be negative
		
		if ($bidamount < 0)
		{
			$error['bidnegative'] = true;
			return $error;
		}
		
		//notify the previous bid leader that they got outbid
		if ($bidderid != $this->userid && $bidderid != $leader)
			$res = $this->notifyOutbid();
		
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
		
		$stmt->prepare("INSERT INTO BidHistory
		(
			TaskID,
			BidderID,
			BidAmount
		)
		VALUES
		(?, ?, ?)");
		
		$stmt->bind_param("iii", $this->taskid, $bidderid, $bidamount);
		
		$stmt->execute();
		
		$dbhandle->close();
	}
	
	public function getCurrentBid()
	{
		$currentbid = 0;
		
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
		
		$stmt->prepare("SELECT BidAmount FROM BidHistory WHERE TaskID = ? ORDER BY BidAmount LIMIT 1");
		$stmt->bind_param("i", $this->taskid);
		$stmt->execute();
		
		$stmt->store_result();
		
		//no bids, use initial bid amount
		if ($stmt->num_rows == 0)
		{
			$stmt->prepare("SELECT InitialBid FROM Tasks WHERE TaskID = ?");
			$stmt->bind_param("i", $this->taskid);
			$stmt->execute();
			$stmt->store_result();
		}
		
		$stmt->bind_result($currentbid);
		$stmt->fetch();
		
		$stmt->close();
		$dbhandle->close();
		
		return $currentbid;
	}
	
	//Return userid of bid winner
	public function getBidLeaderID()
	{
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
			
		$stmt->prepare("SELECT BidderID FROM BidHistory WHERE TaskID = ? ORDER BY BidAmount LIMIT 1");
		$stmt->bind_param("i", $this->taskid);
		$stmt->execute();
		
		$stmt->store_result();
		$stmt->bind_result($outBidderID);
		$stmt->fetch();
		
		$stmt->close();
		$dbhandle->close();
		
		if(isset($outBidderID))
			return $outBidderID;
		else
			return NULL;
	}
	
	//Unsets Active flag in db
	public function unsetActive()
	{
		$this->active = 0;
		
		$dbhandle = db_connect();
		
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
		
		$stmt->prepare("UPDATE Tasks SET Active=0 WHERE TaskID = ?");
		$stmt->bind_param("i", $this->taskid);
		$stmt->execute();
		
		$stmt->close();
		$dbhandle->close();
	}
	
	//Check if the current time is past the bid end time
	//If so, unsets Active flag
	public function isPastBidTime()
	{
		if ($this->enddatetime <= time() || !$this->active)
		{
			$this->unsetActive();
			return true;
		}
		
		return false;
	}
	
	//Checks if task with specified ID exists
	//return true - exists
	//return false - does not exist
	public function checkExistence($intaskid)
	{
		$retval; 
		
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
		
		$stmt->prepare("SELECT TaskID FROM Tasks WHERE TaskID=?");
		$stmt->bind_param("i", $intaskid);
		$stmt->execute();
		
		$stmt->store_result();
		
		if ($stmt->num_rows == 0)
			$retval = false;
		else
			$retval = true;
		
		$stmt->close();
		$dbhandle->close();
		
		return $retval;
	}
	
	//Ends a task bidding. Unsets the active-ness, and alerts the winner.
	public function endTask()
	{
		require_once 'message_class.php';
		
		$this->unsetActive();
		$this->winnerid = $this->getBidLeaderID();
		
		$notifymessage = new message();
		$messageinfo = array();
		$messageinfo['content'] = "Congratulations! You have won the bidding for the task <a href='/ViewTask.php?id=".$this->taskid."'>".$this->title."</a>!";
		$messageinfo['taskID'] = $this->taskid;
		$messageinfo['receiverID'] = $this->winnerid;
		$messageinfo['isSystem'] = true;
		$notifymessage->send($messageinfo);
	}
	
	//Notifies current bid leader that they have been outbid.
	//Call this BEFORE adding the new bid!!
	public function notifyOutbid()
	{
		require_once 'message_class.php';
		
		$notifymessage = new message();
		$messageinfo = array();
		$messageinfo['content'] = "Alert! You have been outbid on the task <a href='/ViewTask.php?id=".$this->taskid."'>".$this->title."</a>!";
		$messageinfo['taskID'] = $this->taskid;
		$messageinfo['receiverID'] = $this->getBidLeaderID();
		$messageinfo['isSystem'] = true;
		$notifymessage->send($messageinfo);
		
		return 1;
	}
}

?>
