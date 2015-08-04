<?php

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

require_once 'php/user_class.php';

if (isset($_GET['getMessages']) && isset($_SESSION['username']))
{
	require_once 'php/message_lister.php';
	
	$unread = countUnreadMessages();
	if ($unread > 0)
		echo ' ('.$unread.') ';
	die;
}

?>

<style>
.dropdown-menu {
  min-width:320px;
}

.avatar_tiny {
	width: auto;
	height: 30px;
	line-height: 18px;
	text-align: center;
	float: left;
	margin-right: 5px;
}
.resize_fit_center {
	max-width:100%;
	max-height:100%;
	vertical-align: center;
}
</style>

<script>
$(function(){
if ('<?php echo isset($_SESSION['userid']); ?>' == '1')
{
	getMessageCount();
	setInterval(getMessageCount, 1000);
}

});

function getMessageCount() 
{
    $.get("NavMod.php", { getMessages: "1"}, function(data) 
	{
			$('#NewMessages').html(data);
	}
    );
}
</script>

<nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"><img src="images/Task%20Master%20White.png" style="height:50px;width:auto;"></a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    
                    <li><a href="ViewTasks.php">View Tasks</a></li>
                    <?php if (isset($_SESSION['username']))
					{ ?>
						<li><a href="CreateTask.php">Create Task</a></li>
						<li><a href="MessageCenter.php">Message Center<span id="NewMessages" style="color:red"></span></a></li>
					<?php 
					}	
					?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
					<?php if (!isset($_SESSION['username']))
					{ ?>
                    <li><a href="UserRegistration.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
					<li>
                        <div class="dropdown">
							<button type="button" class="btn btn-default navbar-btn" data-toggle="dropdown">Login</button>
							
							<div class="dropdown-menu" style="padding: 10px; background: #ddd">
								<form action="Login.php" method="post" role="form">
									<div class="form-group">
										<label for="user">User</label>
										<input type="text" class="form-control" id="user" placeholder="User" name="username">
										<label for="password">Password</label>
										<input type="password" class="form-control" id="password" placeholder="Password" name="password">
									</div>
									<div class="checkbox">
										<label>
											<input type="checkbox" id="RememberMe" name="remember"> Remember Me
										</label>
									</div>
									<input type="submit" name="loginsubmit" class="btn btn-default" value="Sign in">
								</form>
							</div>
						</div>
                    </li><?php
					}
					else
					{ ?>
					<li>
							<a href="UserProfile.php?<?php echo $_SESSION['userid']; ?>">
								<div class="avatar_tiny">
									<img src="<?php echo $_SESSION['avatarurl']; ?>" class="resize_fit_center"> 
								</div>
								<?php echo $_SESSION['username']; ?>
							</a>
					</li>
					<li><button onclick="location='Logout.php'" class="btn btn-default navbar-btn"> Log Out</button></li>
					<?php
					}
					?>

                </ul>
            </div>
        </div>

        <!-- Search bar-->
        <div class="container">
            <div class="row">
                <div id="custom-search-input">

                    <div class="input-group col-md-12 col-sm-12 col-xs-12">

                        <div class="input-group-btn input-group-btn-static">
                            <button type="button" class="btn btn-default" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu" role="menu" id="drop">
                                <li><a href="#">Select Category</a></li>
                            </ul>
                        </div>

                        <input type="text" class="  search-query form-control" placeholder="Search" />
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button">
                                        <span class=" glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                    </div>
                </div>
            </div>
        </div>
</nav>
