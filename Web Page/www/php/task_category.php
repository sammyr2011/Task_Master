<?php

require_once 'db_connect.php';

class category
{
	var $id;
	var $title;
	
	function __construct($row)
	{
		echo 'inid = {$row['id']}';
		$id = $row['id'];
		$title = $row['title'];
	}
}

function getCategories()
{
	$categories = array();
	
	$dbhandle = db_connect();
	
	$query = "SELECT CategoryID as id, CategoryName as title FROM Categories";
	
	$result = $dbhandle->query($query);
	
	while ($row = $result->fetch_array())
	{
		$newcategory = new category($row);
		array_push($categories, $newcategory);
	}
	
	db_close($dbhandle);
	
	return $categories;
}

?>