<?php
  require_once 'user_class.php';
  
  $user = $_POST['username'];
  $pass = $_POST['password'];
  
  $newuser = new user();
  $response = $newuser->login($user,$pass);
  if($response == NULL)
  {
    $response = $_SESSION;
  }
  
  echo json_encode($response);
?>
