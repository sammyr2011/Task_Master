<?php

require_once 'task_class.php';

$intaskid = $_POST["TaskID"];

$task = new task();

$error = $task->getFromDB($intaskid);

if($error != NULL)
{
  echo $task;
}

?>
