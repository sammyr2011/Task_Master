<?php

//Connect to the database
//Returns handle
function db_connect()
{
    $sqlname = "root";
    $sqlpass = "datapass";
    $hostname = "localhost"; 

    $dbhandle = mysqli_connect($hostname, $sqlname, $sqlpass);
    
    mysqli_select_db("TaskMaster", $dbhandle);
    
    return $dbhandle;
}

function db_close($dbhandle)
{
    mysqli_close($dbhandle);
}

?>
