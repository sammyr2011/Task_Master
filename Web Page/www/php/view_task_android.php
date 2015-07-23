<?php

require_once 'task_class.php';
require_once 'user_class.php';

if(isset($_POST['TaskID']))
{
  $intaskid = $_POST['TaskID'];
  //echo "post set\n";
}
else
{
  $intaskid = 0;
 //echo "post not set\n";
}

$task = new task();

$error = $task->getFromDB($intaskid);

if($error == NULL)
{
  //Convert task to usable array
  $taskout = array();
  $taskout['TaskID'] = $task->taskid;
  $taskout['Lister'] = $task->userid;
  $taskout['Title'] = $task->title;
  $taskout['Description'] = $task->description;
  $taskout['Location'] = $task->location;
  $taskout['Category'] = $task->category;
  $taskout['Tags'] = $task->tags;
  $taskout['NumImages']=$task->numimg;
  
  //Get user info
  $lister = new user();
  if($lister->getFromDB($task->userid) == null)
  {
    $taskout['ListerUsername']=$lister->username;
    //$taskout['ListerAvatarURL']=$lister->getAvatarURL();
    $taskout['ListerAvatarURL']=$lister->avatarurl;
    $rating = $lister->getListerRating();
    $taskout['ListerRating']=$rating['rating'];
    $taskout['ListerRatingWeight']=$rating['weight'];
  }
  else
  {
    $taskout['ErrorUserNotFound']=true;
    $taskout['error']=true;
  }

  echo json_encode($taskout);
}
else
{
  $error['error'] = true;
  echo json_encode($error);
}

//gitpull?
?>

