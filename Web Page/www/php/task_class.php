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
	
	//Takes fields from POST and stores them in user object
	public function createFromPost($info)
	{
		//task info
		if (isset($_SESSION['userid'])) $this->userid = $_SESSION['userid'];
		
		if (isset($info['title'])) $this->title = $info['title'];
		
		if (isset($info['description'])) $this->description = $info['description'];
		
		if (isset($info['content'])) $this->content = $info['content'];
		
		if (isset($info['location'])) $this->location = $info['location'];
		
		//metadata
		if (isset($info['category'])) $this->category = $info['category'];
		
		if (isset($info['tags'])) $this->tags = $info['tags'];
		
		if (isset($info['numimg'])) $this->numimg = $info['numimg'];
	}
	
	//Gets info of a task stored in the database
	public function getFromDB($intaskid)
	{
		$errors = array();
		
		//Fill out fields from database
		$dbhandle = db_connect();
		
		$sqlquery = "SELECT * FROM Tasks WHERE TaskID = {$intaskid} LIMIT 1";
		$result = $dbhandle->query($sqlquery);
		$row = $result->fetch_array();
		
		db_close($dbhandle);
		
		//return an error if this taskid was not found
		if($result->num_rows == 0)
		{
			$errors['taskid'] = true;
			return $errors;
		}
		
		//Take info from DB and store it in this task instance
		if (isset($row['TaskID'])) $this->taskid = $row['TaskID'];
		
		if (isset($row['Lister'])) $this->userid = $row['Lister'];
		
		if (isset($row['Title'])) $this->title = $row['Title'];
		
		if (isset($row['Description'])) $this->description = $row['Description'];
		
		//if (isset($row['Content'])) $this->content = $row['Content'];
		
		if (isset($row['Location'])) $this->location = $row['Location'];
		
		if (isset($row['Category'])) $this->category = $row['Category'];
		
		if (isset($row['Tags'])) $this->tags = $row['Tags'];
		
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
		
		$sqlquery = "INSERT INTO Tasks
		(
			Title,
			Description, 
			Location,
			Category,
			Tags,
			Lister,
			NumImages
		) 
		
		VALUES
		(
			'$this->title',
			'$this->description',
			'$this->location',
			'$this->category',
			'$this->tags',
			'$this->userid',
			'$this->numimg'
		)";
			
		$result = $dbhandle->query($sqlquery);
		
		$this->taskid = $dbhandle->insert_id;
		
		//close connection and return 0
		db_close($dbhandle);
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
			//return $errors;
		}
		
		//verify all info is provided
		if (empty($this->title)) $errors['title'] = true;
		if (empty($this->description)) $errors['description'] = true;
		if (empty($this->content)) $errors['content'] = true;
		if (empty($this->location)) $errors['location'] = true;
		if (empty($this->category)) $errors['category'] = true;
		if (empty($this->tags)) $errors['tags'] = true;
		
		//If we made it here, all is valid
		if (count($errors) > 0)
			return $errors;
		else
			return NULL;
	}
	
	public function uploadImg($images)
	{
		$errors = array();
		
		$allowedext = array("jpg", "jpeg", "png");
		
		$imgindex = 0;
		
		foreach($images['tmp_name'] as $key->$file_temp)
		{
			$file_ext = pathinfo($images['name'][$key], PATHINFO_EXTENSION);
			
			if (in_array($file_ext, $allowedext))
			{
				$folderpath = "/images/task/".$this->taskid."/";
				
				if (!is_dir($folderpath)) 
					mkdir($folderpath);
				
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
}

?>
