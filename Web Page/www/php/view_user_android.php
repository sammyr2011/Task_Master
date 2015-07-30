<?php
require_once 'user_class.php';
require_once 'lister.php';

$user = new user();
if(isset($_POST['userid']))
{
  $user->getFromDB($_POST['userid']);
  
  $userout = array();
  
  $userout['Username']=$user->username;
  $userout['AvatarURL']=$user->avatarurl;
  
  $listerreviews = listReviewsByTime($_POST['userid']);
  $doerreviews = listDoReviewsByTime($_POST['userid']);
  
  $userout['ListerReviews']=$listerreviews;
  $userout['DoerReviews']=$doerreviews;
  
  echo json_encode($userout);
}


?>
