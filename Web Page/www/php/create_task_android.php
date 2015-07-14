<?php
require_once 'task_class.php';

$task = new task();

//Initialize task fields
$task->createFromPost($_POST);

//Try to put task into DB
$errors = $task->register();

//Echo errors as JSON object
echo json_encode($errors);

?>
