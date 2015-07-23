<?php

require_once 'task_class.php';

if(isset($_POST['taskid']))
{
  $task = new task();
  $task->getFromDB($_POST['taskid']);
}
else
{
  $error['invalidtaskid']=true;
  echo json_encode($error);
  return;
}

if(isset($_POST['bidderid']) && isset($_POST['bidamt'])) 
{
  $error = $task->addBid($_POST['bidderid'],$_POST['bidamt']);
  
  if($error==null)
  {
    $error['Success']=true;
  }
  
  echo json_encode($error);
}
else
{
  $error['invalidbidderidorbidamt']=true
  echo json_encode($error);
}

?>
