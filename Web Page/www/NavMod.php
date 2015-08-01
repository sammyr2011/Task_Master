<?php 

if (session_status() == PHP_SESSION_NONE) 
{
    session_start();
}

?>

<style>
.dropdown-menu {
  min-width:320px;
}
</style>

<nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Task Master</a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="CreateTask.php">Create Task</a></li>
                    <li><a href="ViewTasks.php">View Tasks</a></li>
                    <!-- Maybe add in parenthesis how many unread messages the user has like "Messge Center (5)" -->
                    <li><a href="MessageCenter.php">Message Center<span id="NewMessages" style="color:red"> (2)</span></a></li>
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
					<li><a href="AccountSettings.php"><img src="<?php echo $_SESSION['avatarurl']; ?>" height="19px"> <?php echo $_SESSION['username']; ?></a></li>
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
