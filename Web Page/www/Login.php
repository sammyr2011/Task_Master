<?php

//remember submitted values in case of error
$_username = '';
$_password = '';

//Registration form was submitted
if (isset($_POST['loginsubmit']))
{

	if (isset($_POST['username'])) $_username = $_POST['username'];
	if (isset($_POST['password'])) $_password = $_POST['password'];
	
	include_once 'php/user_login.php';
	
	$error = user_login($_username, $_password);
	
	//did login succeed?
	if ($error == NULL) //success, redirect to index and show message
	{
		if (session_status() == PHP_SESSION_NONE) 
		{
			session_start();
		}
		$_SESSION['msg_loggedin'] = "Logged In";
		header("Location: index.php");
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>



    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>




    <meta charset="utf-8">
    <title>User Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <style>
        /* Sticky footer styles
        -------------------------------------------------- */

        html,
        body {
            height: 100%;
            /* The html and body elements cannot have any padding or margin. */
        }

        /* Wrapper for page content to push down footer */
        #wrap {
            min-height: 100%;
            height: auto !important;
            height: 100%;
            /* Negative indent footer by it's height */
            margin: 0 auto -60px;
        }

        /* Set the fixed height of the footer here */
        #push,
        #footer {
            height: 60px;
        }
        #footer {
            background-color: #222;
        }

        /* Lastly, apply responsive CSS fixes as necessary */
        @media (max-width: 767px) {
            #footer {
                margin-left: -20px;
                margin-right: -20px;
                padding-left: 20px;
                padding-right: 20px;
            }
        }

        @import url('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');

        .date-form { margin: 10px; }
        label.control-label span { cursor: pointer; }
		
		.col-center-block {
			float: none;
			display: block;
			margin-left: auto;
			margin-right: auto;
		}
}

    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <script>
        $(function() {
            $( "#datepicker" ).datepicker();
        });

        $(function() {
            $( "#datepicker2" ).datepicker();
        });
    </script>

</head>

<body>


<!-- Part 1: Wrap all page content here -->
<div id="wrap">

    <!-- Fixed navbar -->
    <?php include "NavMod.php"; ?>

    <!-- Begin page content -->
    <div class="container" style="border:black 9px">
        <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <fieldset>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-center-block" style="border: 1px solid #ddd; border-radius: 3px 3px 3px 3px;padding: 26px 26px 26px;box-shadow: 4px 4px 1px #c4c4c4;">

                        <!-- Form Name -->
                        <legend>Log In</legend>

                        <br>
                        
                       <div class="col-md-12 col-sm-12 col-center-block">
                            <label class="control-label" for="username">Username: </label>
							<?php if (isset ($error['username'])) echo '<font color = "red">User Not Found</font>'; ?>
                            <div class="controls">
                                <input id="username" name="username" type="text" placeholder="Username" class="input-xlarge form-control" value="<?php echo $_username; ?>">
                            </div>
                        </div>
                        
                        <!-- Password and confirmation-->
                        <div class="col-md-12 col-sm-12 col-center-block">
                            <div class="control-group">
                                <label class="control-label" for="password">Password: </label>
								<?php if (isset ($error['password'])) echo '<font color = "red">Incorrect Password</font>'; ?>
                                <div class="controls">
                                    <input id="password" name="password" type="password" placeholder="Password" class="input-xlarge form-control">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 text-left col-center-block">
							<br><input type="submit" name="loginsubmit" class="btn btn-primary btn-lg" value="Log In">
                        </div>

                    </div>


                </div>

            </fieldset>
        </form>


    </div>


    <div id="push"></div>
</div>

<div id="push"></div>
</div>

<div id="footer">
    <br>
    <div class="container">
        <p class="muted credit" style="color: white">COP 4331 Project 2</p>
    </div>
</div>


</body>
</html>
