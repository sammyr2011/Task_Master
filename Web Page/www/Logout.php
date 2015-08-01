<?php 

require_once 'php/db_connect.php';

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

// Taking the user out of the ActiveUser DB.
$dbhandle = db_connect();

$sqlquery = "DELETE FROM ActiveUsers WHERE UserID = {$_SESSION["userid"]}";
$result = $dbhandle->query($sqlquery);

db_close($dbhandle);

// Closing the session.
session_unset();
session_destroy();
session_start();

$_SESSION['msg_loggedout'] = "Logged Out";
header("Location: index.php");
die;
?>
