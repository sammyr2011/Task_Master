<?
require_once 'user_class.php';
if(isset($_POST['UserID']) && isset($_POST['Img']))
{
  $userid = $_POST['UserID'];
  $image = $_POST['Img'];
  
  $user = new user();
  $user->getFromDB($userid);
  
  echo json_encode($user->uploadAvatar($image));
}
else
{
  $error['error']=true;
  $error['UserID or Img not set']=true;
  echo json_encode()
}
?>
