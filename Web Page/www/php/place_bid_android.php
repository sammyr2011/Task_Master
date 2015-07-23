<?php

require_once 'task_class.php';

if(isset(_POST['taskid']))
{
  $task = new task();
  $task->getFromDB(_POST['taskid']);
}
else
{
  echo json_encode($error['invalidtaskid']=true);
  return;
}

if(isset(_POST['bidderid']) && isset(_POST['bidamt'])) 
{
  $error = $task->addBid(_POST['bidderid'],_POST['bidamt']);
  
  echo json_encode($error);
}
else
{
  echo json_encode($error['invalidbidderidorbidamt']=true);
}

?>
