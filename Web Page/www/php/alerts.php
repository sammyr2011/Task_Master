<?php

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

//successes

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

//warnings

if (isset($_SESSION['msg_needlogin']))
{ 
	unset($_SESSION['msg_needlogin']); ?>
	<div class="alert alert-danger">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Error!</strong> You must log in first!
	</div> 
	<?php
}

if (isset($_SESSION['msg_badtaskid']))
{ 
	unset($_SESSION['msg_badtaskid']); ?>
	<div class="alert alert-danger">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Bad Task ID!</strong> This is a site error!
	</div> 
	<?php
}

if (isset($_SESSION['msg_bidless']))
{ 
	unset($_SESSION['msg_bidless']); ?>
	<div class="alert alert-danger">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Bid Rejected!</strong> You must bid less than the current bid!
	</div> 
	<?php
}

if (isset($_SESSION['msg_bidover']))
{ 
	unset($_SESSION['msg_bidover']); ?>
	<div class="alert alert-danger">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Bidding Over!</strong> Sorry, the bidding time has ended.
	</div> 
	<?php
}

?>