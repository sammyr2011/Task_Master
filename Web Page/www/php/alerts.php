<?php

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

if (isset($_SESSION['msg_registered']))
{ 
	unset($_SESSION['msg_registered']); ?>
	<div class="alert alert-success">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Registration Success!</strong> You may now log in.
	</div> 
	<?php
} 

if (isset($_SESSION['msg_loggedin']))
{ 
	unset($_SESSION['msg_loggedin']); ?>
	<div class="alert alert-success">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Login Success!</strong> You are now logged in.
	</div> 
	<?php
} 

if (isset($_SESSION['msg_taskmade']))
{ 
	unset($_SESSION['msg_taskmade']); ?>
	<div class="alert alert-success">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Task Success!</strong> Your task is now listed.
	</div> 
	<?php
} 

if (isset($_SESSION['msg_bidplaced']))
{ 
	unset($_SESSION['msg_bidplaced']); ?>
	<div class="alert alert-success">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Bid Success!</strong> Your bid was placed.
	</div> 
	<?php
} 

?>