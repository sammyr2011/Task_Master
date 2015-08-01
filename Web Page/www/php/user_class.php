<?php
require_once 'db_connect.php';

class user
{
	var $userid;
	var $username;
	var $password;
	var $passwordverify;
	
	var $firstname;
	var $lastname;
	var $email;
	
	var $address;
	var $city;
	var $state;
	var $zipcode;
	var $country;
	
	var $avatarurl;
	
	//
	//CONSTRUCTORS
	//
	public function getFromDB($inuserid)
	{
		$errors = array();
		
		//Fill out fields from database
		$dbhandle = db_connect();
		
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("SELECT Username FROM Users WHERE UserID=?");
		$stmt->bind_param("i", $inuserid);
		$stmt->execute();
		
		$stmt->bind_result($rowusername);
		$stmt->store_result();
		$stmt->fetch();
		
		//return an error if this user was not found
		if($stmt->num_rows == 0)
		{
			$errors['userid'] = true;
			$dbhandle->close();
			return $errors;
		}
		$stmt->close();
		
		$this->userid = $inuserid;
		$this->username = $rowusername;
		
		//now we need to get info from the other user info db
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("SELECT FirstName, LastName, Email, Address, City, State, ZipCode, Country FROM UserAccounts WHERE UserID=?");
		$stmt->bind_param("i", $inuserid);
		$stmt->execute();
		
		$stmt->bind_result($this->firstname, $this->lastname, $this->email, $this->address, $this->city, $this->state, $this->zipcode, $this->country);
		$stmt->store_result();
		$stmt->fetch();
		$stmt->close();
		
		$this->avatarurl = $this->getAvatarURL();
		
		//close connection and return 0
		$dbhandle->close();
		return NULL;
	}
	
	//Takes fields from POST and stores them in user object
	public function createFromPost($info)
	{
		//user login
		if (isset($info['username'])) $this->username = $info['username'];
			
		if (isset($info['password'])) $this->password = $info['password'];
		
		if (isset($info['passwordverify'])) $this->passwordverify = $info['passwordverify'];
			
		//user info
		if (isset($info['firstname'])) $this->firstname = $info['firstname'];
			
		if (isset($info['lastname'])) $this->lastname = $info['lastname'];
			
		if (isset($info['email'])) $this->email = $info['email'];
		
		//address
		if (isset($info['address'])) $this->address = $info['address'];
			
		if (isset($info['city'])) $this->city = $info['city'];
			
		if (isset($info['state'])) $this->state = $info['state'];
			
		if (isset($info['zipcode'])) $this->zipcode = $info['zipcode'];
			
		//if (isset($info['country'])) $this->country = $info['country'];
		$this->country = "USA";
	}
	
	//Registers user into the database
	public function register()
	{
		//validate that all fields are filled and proper
		$valid_errors = $this->validate();
		if ($valid_errors != NULL)
			return $valid_errors;
		
		//insert user login info
		$dbhandle = db_connect();
		
		$hashpass = password_hash($this->password, PASSWORD_BCRYPT);
		
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("INSERT INTO Users(Username, HashPassword) VALUES (?, ?)");
		$stmt->bind_param("s", $this->username);
		$stmt->bind_param("s", $hashpass);
		$stmt->execute();
		$stmt->store_result();
		$stmt->fetch();
		$stmt->close();
		
		$this->userid = $dbhandle->insert_id;
		
		//insert user account info
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("INSERT INTO UserAccounts(
			UserID,
			FirstName, 
			LastName,
			Email,
			Address,
			City,
			State,
			ZipCode,
			Country) 
			
			VALUES (?,?,?,?,?,?,?,?,?)");
			
		$stmt->bind_param("i", $this->userid);
		$stmt->bind_param("s", $this->firstname);
		$stmt->bind_param("s", $this->lastname);
		$stmt->bind_param("s", $this->email);
		$stmt->bind_param("s", $this->address);
		$stmt->bind_param("s", $this->city);
		$stmt->bind_param("s", $this->state);
		$stmt->bind_param("i", $this->zipcode);
		$stmt->bind_param("s", $this->country);
		$stmt->execute();
		$stmt->close();
		
		//close connection and return 0
		$dbhandle->close();
		return NULL;
	}
	
	//Validates all info fields to make sure they are of proper format and all filled out
	public function validate()
	{
		$errors = array();
		
		//validate username and password
		if (empty($this->username)) $errors['username'] = true;
		
		if (empty($this->password)) $errors['password'] = true;
		
		//make sure the two password fields match
		if ($this->password != $this->passwordverify) $errors['passwordmatch'] = true;
		
		//verify user account info is provided
		if (empty($this->firstname)) $errors['firstname'] = true;
		if (empty($this->lastname)) $errors['lastname'] = true;
		if (empty($this->email)) $errors['email'] = true;
		if (empty($this->address)) $errors['address'] = true;
		if (empty($this->city)) $errors['city'] = true;
		if (empty($this->state)) $errors['state'] = true;
		if (empty($this->zipcode)) $errors['zipcode'] = true;
		if (empty($this->country)) $errors['country'] = true;
		
		//Check if the username is already taken
		$dbhandle = db_connect();
		
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("SELECT UserName FROM Users where UserName=?");
		$stmt->bind_param("s", $this->username);
		$stmt->execute();
		
		$stmt->store_result();
		if($stmt->num_rows != 0) $errors['usertaken'] = true;
		$stmt->close();
		
		//If we made it here, all is valid
		$dbhandle->close();
		
		if (count($errors) > 0)
			return $errors;
		else
			return NULL;
	}
	
	//Login function. If matches, fills out user instance with info
	function login($inuser, $inpass)
	{
		$dbhandle = db_connect();
		
		$errors = array();
		
		//query user		
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("SELECT UserID, HashPassword FROM Users WHERE UserName=?");
		$stmt->bind_param("s", $inuser);
		$stmt->execute();
		$stmt->bind_result($rowuserid, $hashpass);
		$stmt->store_result();
		$stmt->fetch();
		
		//fail if user does not exist
		if($stmt->num_rows == 0)
		{
			$stmt->close();
			$dbhandle->close();
			$errors['username'] = true;
			return $errors;
		}
		$stmt->close();
		
		//query password		
		
		//check if using old unhashed pass
		if (password_needs_rehash($inpass, PASSWORD_BCRYPT))
		{
			if ($inpass == $hashpass) //rehash it if so
			{
				$hashpass = password_hash($inpass, PASSWORD_BCRYPT);

				$stmt = $dbhandle->stmt_init();
				$stmt->prepare("UPDATE Users SET HashPassword=? WHERE UserName=?");
				$stmt->bind_param("s", $hashpass);
				$stmt->bind_param("s", $inuser);
				$stmt->execute();
				$stmt->close();
			}
		}
		
		//fail if password does not match
		if (!password_verify($inpass, $hashpass))
		{
			$dbhandle->close();
			$errors['password'] = true;
			return $errors;
		}

		//create login session
		session_start();
		$_SESSION["userid"] = $rowuserid;
		$_SESSION["username"] = $inuser;
		
		// Adding user to ActiveUser table in DB.
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("INSERT INTO ActiveUsers (UserID) VALUES (?)");
		$stmt->bind_param("i", $rowuserid);
		$stmt->execute();
		
		$stmt->close();
		$dbhandle->close();

		//fill out user object instance with info
		$this->getFromDB($rowuserid);
		
		//return 0 indicates success
		return NULL;
	}
	
	//
	//SETTERS
	//
	public function uploadAvatar($file)
	{
		$errors = array();
		
		$allowedext = array("jpg");
		
		$file_temp = $file['tmp_name'];
		$file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			
		if (in_array($file_ext, $allowedext))
		{
			//$folderpath = "/images/avatars/";
			$folderpath = "/var/www/html/images/avatars/";
			
			if (!is_dir($folderpath)) 
				mkdir($folderpath,0777,true);
			
			if (!move_uploaded_file($file_temp,$folderpath.$this->userid.$file_ext))
				$errors['imgupload'] = true;
		}
		
		//If we made it here, all is valid
		if (count($errors) > 0)
			return $errors;
		else
			return NULL;
	}
	
	//
	//GETTERS
	//
	
	//Returns user avatar URL. For displaying the proper avatar.
	//Returns the stock avatar image if it does not exist.
	public function getAvatarURL()
	{
		$fullpath = "/var/www/html/";
		$url = "images/avatars/".$this->userid.".jpg";
		//if (file_exists($url))
		if (stream_resolve_include_path($fullpath.$url))
			return $url;
		else
			return "images/UserStock.png";
	}
	
	//Get Lister rating. Returns an array containing:
	//'rating' = stars out of 5. Can be fractional.
	//'weight' = how many ratings
	public function getListerRating()
	{
		$rating = array();
		
		$dbhandle = db_connect();
		
		//query rating
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("SELECT Rating FROM Ratings WHERE ListerID=?");
		$stmt->bind_param("i", $this->userid);
		$stmt->execute();
		$stmt->bind_result($rowrating);
		$stmt->store_result();
		
		$rating['weight'] = $stmt->num_rows;
		$rating['rating'] = 0;
		
		//get the sum
		while ($stmt->fetch())
			$rating['rating'] += $rowrating;
		
		//calculate the average
		if ($rating['weight'] != 0)
			$rating['rating'] /= $rating['weight'];
		
		$stmt->close();
		$dbhandle->close();
		
		return $rating;
	}
	
	//Get Doer rating. Returns an array containing:
	//'rating' = stars out of 5. Can be fractional.
	//'weight' = how many ratings
	public function getDoerRating()
	{
		$rating = array();
		
		$dbhandle = db_connect();
		
		//query rating
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("SELECT Rating FROM DoRatings WHERE ResponderID=?");
		$stmt->bind_param("i", $this->userid);
		$stmt->execute();
		$stmt->bind_result($rowrating);
		$stmt->store_result();
		
		$rating['weight'] = $stmt->num_rows;
		$rating['rating'] = 0;
		
		//get the sum
		while ($stmt->fetch())
			$rating['rating'] += $rowrating;
		
		//calculate the average
		if ($rating['weight'] != 0)
			$rating['rating'] /= $rating['weight'];
		
		$stmt->close();
		$dbhandle->close();
		
		return $rating;
	}
	
	//Checks if user with specified ID exists
	//return true - exists
	//return false - does not exist
	public function checkExistence($inuserid)
	{
		$retval; 
		
		//do the query
		$dbhandle = db_connect();
		
		$stmt = $dbhandle->stmt_init();
		$stmt->prepare("SELECT UserID FROM Users WHERE UserID=?");
		$stmt->bind_param("i", $inuserid);
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows == 0) //user not found
			$retval = false;
		else //user found
			$retval = true;
		
		$stmt->close();
		$dbhandle->close();
		
		return $retval;
	}
}

?>
