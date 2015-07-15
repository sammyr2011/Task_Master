<?php

require_once 'task_class.php';

if(isset($_POST['TaskID']))
{
  $intaskid = $_POST['TaskID'];
}
else
{
  $intaskid = 0;
}

$task = new task();

$error = $task->getFromDB($intaskid);

if($error != NULL)
{
  echo $task;
}

?>
