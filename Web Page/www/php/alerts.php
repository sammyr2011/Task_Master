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

if (isset($_SESSION['msg_loggedout']))
{ 
	unset($_SESSION['msg_loggedout']); ?>
	<div class="alert alert-warning">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Logout Success!</strong> You are now logged out.
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

if (isset($_SESSION['msg_reviewed']))
{ 
	unset($_SESSION['msg_reviewed']); ?>
	<div class="alert alert-success">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Review placed!</strong> Your review has been recorded.
	</div> 
	<?php
}

if (isset($_SESSION['msg_taskover_won']))
{ 
	unset($_SESSION['msg_taskover_won']); ?>
	<div class="alert alert-success">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Bidding has ended!</strong> You won this bid! Message the owner to begin the task.
	</div> 
	<?php
}

if (isset($_SESSION['msg_taskover']))
{ 
	unset($_SESSION['msg_taskover']); ?>
	<div class="alert alert-info">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Bidding is over!</strong> This listing has ended.
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

if (isset($_SESSION['msg_bidself']))
{ 
	unset($_SESSION['msg_bidself']); ?>
	<div class="alert alert-danger">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Bid Rejected!</strong> You cannot bid on your own task!
	</div> 
	<?php
}

if (isset($_SESSION['msg_bidnegative']))
{ 
	unset($_SESSION['msg_bidnegative']); ?>
	<div class="alert alert-danger">  
		<a class="close" data-dismiss="alert">X</a>  
		<strong>Bid Rejected!</strong> You cannot bid a negative amount!
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