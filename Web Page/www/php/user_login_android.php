<?php
  require_once 'user_login.php';
  
  $user = $_POST['username'];
  $pass = $_POST['password'];
  
  $response = user_login($user,$pass);
  if($response == NULL)
  {
    $response = $_SESSION;
  }
  
  echo json_encode($response));
?>
