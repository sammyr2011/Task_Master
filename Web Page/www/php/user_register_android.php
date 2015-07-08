<?php
  require_once 'user_register.php';
  $user = new user($_POST);
  $error= $user->register();
  
  if($error == NULL)
  {
    session_start();
    $_SESSION['msg_registered'] = "Registered";
    $response = $_SESSION;
  }
  else
  {
    $response = $error;
  }
  
  echo json_encode($response);
?>
