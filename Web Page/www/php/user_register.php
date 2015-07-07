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
	
	//Takes fields from POST and stores them in user object
	public function __construct($info)
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
		$sqlquery = "INSERT INTO Users(UserName, HashPassword) VALUES ('{$this->username}', '{$hashpass}')";
		$result = $dbhandle->query($sqlquery);
		
		$this->userid = $dbhandle->insert_id;
		
		//insert user account info
		$sqlquery = "INSERT INTO UserAccounts(
			UserID,
			FirstName, 
			LastName,
			Email,
			Address,
			City,
			State,
			ZipCode,
			Country) 
			
			VALUES (
			'{$this->userid}',
			'{$this->firstname}',
			'{$this->lastname}',
			'{$this->email}',
			'{$this->address}',
			'{$this->city}',
			'{$this->state}',
			'{$this->zipcode}',
			'{$this->country}')";
			
		$result = $dbhandle->query($sqlquery);
		
		//close connection and return 0
		db_close($dbhandle);
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
		
		$sqlquery = "SELECT UserName FROM Users where UserName='{$this->username}'";
		$result = $dbhandle->query($sqlquery);
		
		if($result->num_rows != 0) $errors['usertaken'] = true;
		
		//If we made it here, all is valid
		db_close($dbhandle);
		
		if (count($errors) > 0)
			return $errors;
		else
			return NULL;
	}
}

?>
