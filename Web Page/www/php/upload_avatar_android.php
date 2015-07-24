<?php
require_once 'user_class.php';
if(isset($_POST['UserID']) && isset($_REQUEST['Img']))
{
  $userid = $_POST['UserID'];
  $image = $_REQUEST['Img'];
  
  $user = new user();
  $user->getFromDB($userid);
  
  header('Content-Type: bitmap; charset=utf-8');
  $imagefile = fopen('upload_avatar_android_tmp.jpg','wb');
  fwrite($imagefile, base64_decode($image));
  fclose($imagefile);
  
  $result = $user->uploadAvatar($imagefile);
  
  if($result==null)
  {
    $result['success']=true;
  }
  
  echo json_encode($result);
}
else
{
  $error['error']=true;
  $error['UserID or Img not set']=true;
  echo json_encode();
}
?>
