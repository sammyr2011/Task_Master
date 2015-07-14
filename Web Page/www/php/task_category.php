<?php

require_once 'db_connect.php';

class category
{
	var $id;
	var $title;
	
	function __construct($row)
	{
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
		$category = new category($row);
		array_push($categories, $category);
	}
	
	db_close($dbhandle);
	
	return $categories;
}

?>