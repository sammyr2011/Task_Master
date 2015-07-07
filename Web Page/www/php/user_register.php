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
	public function __construct($info = $array() )
	{
		//user login
		if (isset($info['Uname'])) $this->username = $info['Uname'];
			
		if (isset($info['passwordreg'])) $this->password = $info['passwordreg'];
		
		if (isset($info['passwordRegVerify'])) $this->password = $info['passwordRegVerify'];
			
		//user info
		if (isset($info['Fname'])) $this->firstname = $info['Fname'];
			
		if (isset($info['Lname'])) $this->lastname = $info['Lname'];
			
		if (isset($info['UserEmail'])) $this->email = $info['UserEmail'];
		
		//address
		if (isset($info['StreetAddress'])) $this->address = $info['StreetAddress'];
			
		if (isset($info['City'])) $this->city = $info['City'];
			
		if (isset($info['state'])) $this->state = $info['state'];
			
		if (isset($info['Zip'])) $this->zipcode = $info['Zip'];
			
		//if (isset($info['Country'])) $this->country = $info['Country'];
		$this->country = "USA";
	}
	
	//Registers user into the database
	public function register()
	{
		//validate that all fields are filled and proper
		$valid_result = this->validate();
		if ($valid_result != 0)
			return $valid_result;
		
		//insert user login info
		$hashpass = password_hash($this->password, PASSWORD_BCRYPT);
		$sqlquery = "INSERT INTO Users(UserName, HashPassword) VALUES ('{$this->username}', '{$hashpass}')";
		$result = mysqli_query($sqlquery);
		
		$this->userid = mysqli_insert_id($dbhandle);
		
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
			
		$result = mysqli_query($sqlquery);
		
		//close connection and return 0
		db_close($dbhandle);
		return 0;
	}
	
	//Validates all info fields to make sure they are of proper format and all filled out
	public function validate()
	{
		if (!isset($this->username)) return 1;
		
		if (!isset($this->password)) return 3;
		
		//make sure confirm password matches
		if ($this->password != $this->passwordverify) return 4;
		
		if (!isset($this->firstname)
			|| !isset($this->lastname)
			|| !isset($this->email)
			|| !isset($this->address)
			|| !isset($this->city)
			|| !isset($this->state)
			|| !isset($this->zipcode)
			|| !isset($this->country))
			return 5;
		
		//Check if the username is already taken
		$dbhandle = db_connect();
		
		$sqlquery = "SELECT UserName FROM Users where UserName='{$this->username}'";
		$result = mysqli_query($sqlquery);
		
		if(mysqli_num_rows($result) != 0)
		{
			db_close($dbhandle);
			return 2;
		}
		
		//If we made it here, all is valid
		db_close($dbhandle);
		return 0;
	}
}

?>
