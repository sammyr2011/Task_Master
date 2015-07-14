<?php

require_once 'db_connect.php';

function user_login($inuser, $inpass)
{
	$dbhandle = db_connect();
	
	$errors = array();
	
	//query user
	$sqlquery = "SELECT UserID, HashPassword FROM Users WHERE UserName='{$inuser}'";
	$result = $dbhandle->query($sqlquery);
	
	//fail if user does not exist
	if($result->num_rows == 0)
	{
		$dbhandle->close();
		$errors['username'] = true;
		return $errors;
	}
	
	//query password
	$row = $result->fetch_array();
	$hashpass = $row['HashPassword'];
	
	//check if using old unhashed pass
	if (password_needs_rehash($inpass, PASSWORD_BCRYPT))
	{
		if ($inpass == $hashpass) //rehash it if so
		{
			$hashpass = password_hash($inpass, PASSWORD_BCRYPT);
			$query = "UPDATE Users SET HashPassword='{$hashpass}' WHERE UserName='{$inuser}'";
			$result = $dbhandle->query($query);
		}
	}
	
	//fail if password does not match
	if (!password_verify($inpass, $hashpass))
	{
		$dbhandle->close();
		$errors['password'] = true;
		return $errors;
	}

	//Grab the UserID for the redirect link
	$userid = $row['UserID'];

	//create login session
	session_start();
	$_SESSION["userid"] = $userid;
	$_SESSION["username"] = $inuser;
	
	// Adding user to ActiveUser table in DB.
	$sqlquery = "INSERT INTO ActiveUsers(UserID) VALUES ('{$userid}')";
	$result = $dbhandle->query($sqlquery);
	
	db_close($dbhandle);

	//return 0 indicates success
	return NULL;
}

?>
