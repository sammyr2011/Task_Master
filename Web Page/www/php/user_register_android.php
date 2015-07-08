<?php
  require_once 'user_register.php';
  $user = new user($_POST);
  $error= $user->register();
  
  if($error == NULL)
  {
  
  }
?>
