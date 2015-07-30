<?php
require_once 'user_class.php';

$user = new user();
if(isset($_POST['userid']))
{
  $user->getFromDB($_POST['userid']);
  
  $userout = array();
  
  $userout['Username']=$user->username;
  $userout['AvatarURL']=$uesr->avatarurl;
}


?>
