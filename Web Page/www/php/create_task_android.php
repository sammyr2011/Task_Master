<?php
require_once 'task_class.php';

$task = new task();

//Initialize task fields
$task->createFromPost($_POST);

//Try to put task into DB
$errors = $task->register();

//If no errors, return a happy message
if($errors==NULL)
{
  $errors['submitted']=true;
}

//Echo errors as JSON object
echo json_encode($errors);

?>
