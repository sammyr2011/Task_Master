<?php 

require_once 'db_connect.php'

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

// Taking the user out of the ActiveUser DB.
$dbhandle = db_connect();

$sqlquery = "DELETE FROM ActiveUsers WHERE UserID = {$_SESSION["userid"]}";
$result = $dbhandle->query($sqlquery);

dbclose($dbhandle);


// Closing the session.
session_unset();
session_destroy();

header("Location: index.php");
?>
