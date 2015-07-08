<?php
  require_once 'user_login.php';
  
  $user = $_POST['username'];
  $pass = $_POST['password'];
  
  echo json_encode(user_login($user,$pass));
?>
