<?php

//remember submitted values in case of error
$_username = '';
$_firstname = '';
$_lastname = '';
$_address = '';
$_city = '';
$_zipcode = '';
$_email = '';

//Registration form was submitted
if (isset($_POST['submit']))
{
	include_once 'php/user_class.php';
	$newuser = new user();
	$newuser->createFromPost($_POST);
	
	$error = $newuser->register();
	
	//did registration succeed?
	if ($error == NULL) //success, redirect to index and show message
	{
		session_start();
		$_SESSION['msg_registered'] = "Registered";
		header("Location: index.php");
		die;
	}
	else //did not, restore submitted values
	{
		if (isset($_POST['username'])) $_username = $_POST['username'];
		if (isset($_POST['firstname'])) $_firstname = $_POST['firstname'];
		if (isset($_POST['lastname'])) $_lastname = $_POST['lastname'];
		if (isset($_POST['address'])) $_address = $_POST['address'];
		if (isset($_POST['city'])) $_city = $_POST['city'];
		if (isset($_POST['zipcode'])) $_zipcode = $_POST['zipcode'];
		if (isset($_POST['email'])) $_email = $_POST['email'];
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
                    <div class="col-md-8 col-sm-8" style="border: 1px solid #ddd; border-radius: 3px 3px 3px 3px;padding: 14px 26px 26px;box-shadow: 4px 4px 1px #c4c4c4;">

                        <!-- Form Name -->
                        <legend>User Registration</legend>

                        <br>
                        
                       <div class="control-group col-md-12 col-sm-12">
                            <label class="control-label" for="username">Username: </label>
							<?php if (isset ($error['username'])) echo '<font color = "red">Invalid Username</font>'; ?>
							<?php if (isset ($error['usertaken'])) echo '<font color = "red">Username Already Taken</font>'; ?>
                            <div class="controls">
                                <input id="username" name="username" type="text" placeholder="Username" class="input-xlarge form-control" value="<?php echo $_username; ?>">
                            </div>
                        </div>
                        
                        <!-- Password and confirmation-->
                        <div class="col-md-12 col-sm-12">
                            <div class="control-group">
                                <label class="control-label" for="password">Password: </label>
								<?php if (isset ($error['password'])) echo '<font color = "red">Invalid Password</font>'; ?>
                                <div class="controls">
                                    <input id="password" name="password" type="password" placeholder="Password" class="input-xlarge form-control">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="control-group">
                                <label class="control-label" for="passwordverify">Confirm Password: </label>
								<?php if (isset ($error['passwordmatch'])) echo "<font color = 'red'>Passwords don't match</font>"; ?>
                                <div class="controls">
                                    <input id="passwordverify" name="passwordverify" type="password" placeholder="Retype Password" class="input-xlarge form-control" style="margin-bottom: 30px">

                                </div>
                            </div>
                        </div>
						
                     <!--Split first name and last name-->
                        <div class="col-md-6 col-sm-6">

                            <!-- Text input-->
                            <div class="control-group">
                                <label class="control-label" for="Fname">First Name:</label>
								<?php if (isset ($error['firstname'])) echo '<font color = "red">Invalid First Name</font>'; ?>
                                <div class="controls">
                                    <input id="firstname" name="firstname" type="text" placeholder="First Name" class="input-xlarge form-control" value="<?php echo $_firstname; ?>">
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 col-sm-6">

                            <!-- Text input-->
                            <div class="control-group">
                                <label class="control-label" for="lastname">Last Name:</label>
								<?php if (isset ($error['lastname'])) echo '<font color = "red">Invalid Last Name</font>'; ?>
                                <div class="controls">
                                    <input id="lastname" name="lastname" type="text" placeholder="Last Name" class="input-xlarge form-control" value="<?php echo $_lastname; ?>">

                                </div>
                            </div>

                        </div>

                        <div class="control-group col-md-12 col-sm-12">
                            <label class="control-label" for="address">Street Address:</label>
							<?php if (isset ($error['address'])) echo '<font color = "red">Invalid Address</font>'; ?>
                            <div class="controls">
                                <input id="address" name="address" type="text" placeholder="Address" class="input-xlarge form-control" value="<?php echo $_address; ?>">

                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <!-- City -->
                            <div class="control-group">
                                <label class="control-label" for="city">City:</label>
								<?php if (isset ($error['city'])) echo '<font color = "red">Invalid City</font>'; ?>
                                <div class="controls">
                                    <input id="city" name="city" type="text" placeholder="City" class="input-xlarge form-control" value="<?php echo $_city; ?>">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3">
                            <!-- State -->
                            <div class="control-group">
                                <label class="control-label" for="state">State:</label><br/>
                                <select name="state" class="form-control" id="state">
                                    <option value="AL">AL</option>
                                    <option value="AK">AK</option>
                                    <option value="AZ">AZ</option>
                                    <option value="AR">AR</option>
                                    <option value="CA">CA</option>
                                    <option value="CO">CO</option>
                                    <option value="CT">CT</option>
                                    <option value="DE">DE</option>
                                    <option value="DC">DC</option>
                                    <option value="FL">FL</option>
                                    <option value="GA">GA</option>
                                    <option value="HI">HI</option>
                                    <option value="ID">ID</option>
                                    <option value="IL">IL</option>
                                    <option value="IN">IN</option>
                                    <option value="IA">IA</option>
                                    <option value="KS">KS</option>
                                    <option value="KY">KY</option>
                                    <option value="LA">LA</option>
                                    <option value="ME">ME</option>
                                    <option value="MD">MD</option>
                                    <option value="MA">MA</option>
                                    <option value="MI">MI</option>
                                    <option value="MN">MN</option>
                                    <option value="MS">MS</option>
                                    <option value="MO">MO</option>
                                    <option value="MT">MT</option>
                                    <option value="NE">NE</option>
                                    <option value="NV">NV</option>
                                    <option value="NH">NH</option>
                                    <option value="NJ">NJ</option>
                                    <option value="NM">NM</option>
                                    <option value="NY">NY</option>
                                    <option value="NC">NC</option>
                                    <option value="ND">ND</option>
                                    <option value="OH">OH</option>
                                    <option value="OK">OK</option>
                                    <option value="OR">OR</option>
                                    <option value="PA">PA</option>
                                    <option value="RI">RI</option>
                                    <option value="SC">SC</option>
                                    <option value="SD">SD</option>
                                    <option value="TN">TN</option>
                                    <option value="TX">TX</option>
                                    <option value="UT">UT</option>
                                    <option value="VT">VT</option>
                                    <option value="VA">VA</option>
                                    <option value="WA">WA</option>
                                    <option value="WV">WV</option>
                                    <option value="WI">WI</option>
                                    <option value="WY">WY</option>
                                </select>

                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3">
                            <div class="control-group">
                                <label class="control-label" for="zipcode">Zip Code:</label>
                                <div class="controls">
                                    <input id="zipcode" name="zipcode" type="text" placeholder="Zip Code" class="input-xlarge form-control" value="<?php echo $_zipcode; ?>">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="control-group">
                                <label class="control-label" for="email">Email:</label>
                                <div class="controls">
                                    <input id="email" name="email" type="email" placeholder="Email" class="input-xlarge form-control" style="margin-bottom: 30px" value="<?php echo $_email; ?>">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                            <input type="submit" name="submit" class="btn btn-primary btn-lg raised" value="Submit">
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                            <button type="button" class="btn btn-primary btn-lg raised" onclick="#">Cancel</button>
                        </div>

                    </div>

                    <!-- Help side div-->
                    <div class="col-md-4 col-sm-4">
                        <p>Welcome to the User Registration page.  Congratulations you have taken
                            the first step to having your task completed by a Task Master.</p><br>
                        <p></p>

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
