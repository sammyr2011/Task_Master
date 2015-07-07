<?php

//Connect to the database
//Returns handle
function db_connect()
{
    $sqlname = "root";
    $sqlpass = "datapass";
    $hostname = "localhost"; 
	$dbname = "TaskMaster";

    $dbhandle = new mysqli($hostname, $sqlname, $sqlpass, $dbname);
    
	if ($dbhandle->connect_error)
		die('DB Connection Error (' . $dbhandle->connect.errno . ') '. $dbhandle->connect_error);
    
    return $dbhandle;
}

//Close the database connection at this handle
function db_close($dbhandle)
{
    $dbhandle->close();
}

?>
