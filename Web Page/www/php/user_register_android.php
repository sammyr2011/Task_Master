<?php
  require_once 'user_class.php';
  $user = new user();
  $user->createFromPost($_POST);
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
