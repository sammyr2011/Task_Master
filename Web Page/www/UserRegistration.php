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
    <title>Task Creation</title>
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
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Task Master</a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li ><a href="CreateTask.php">Create Task</a></li>
                    <li><a href="#">View Tasks</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="UserRegistration.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                    <!--
                    <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    -->

                    <li>
                        <?php include "loginMod.php"; ?>  
                    </li>

                </ul>
            </div>
        </div>

        <!-- Search bar-->
        <div class="container">
            <div class="row">

                <!--
                <div class="col-md-1">
                    <select title="search in">
                        <option>Food</option>
                        <option>Car</option>
                        <option>Lawn</option>
                    </select>
                </div>
                -->
                <div id="custom-search-input">

                    <div class="input-group col-md-12">

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

    <!-- Begin page content -->
    <div class="container" style="border:black 9px">
        <form class="form-horizontal" >
            <fieldset>
                <div class="row">
                    <div class="col-md-8 col-sm-8" style="border: 1px solid #ddd; border-radius: 3px 3px 3px 3px;padding: 14px 26px 26px;box-shadow: 4px 4px 1px #c4c4c4;">

                        <!-- Form Name -->
                        <legend>User Registration</legend>

                        <br>

                     <!--Split first name and last name-->
                        <div class="col-md-6 col-sm-6">

                            <!-- Text input-->
                            <div class="control-group">
                                <label class="control-label" for="Fname">First Name</label>
                                <div class="controls">
                                    <input id="Fname" name="textinput" type="text" placeholder="First Name" class="input-xlarge form-control">
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 col-sm-6">

                            <!-- Text input-->
                            <div class="control-group">
                                <label class="control-label" for="Lname">Last Name</label>
                                <div class="controls">
                                    <input id="Lname" name="textinput" type="text" placeholder="Last Name" class="input-xlarge form-control">

                                </div>
                            </div>

                        </div>

                        <div class="control-group col-md-12 col-sm-12">
                            <label class="control-label" for="StreetAddress">Street Address</label>
                            <div class="controls">
                                <input id="StreetAddress" name="textinput" type="text" placeholder="Address" class="input-xlarge form-control">

                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <!-- City -->
                            <div class="control-group">
                                <label class="control-label" for="City">City: </label>
                                <div class="controls">
                                    <input id="City" name="textinput" type="text" placeholder="City" class="input-xlarge form-control">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3">
                            <!-- State -->
                            <div class="control-group">
                                <label class="control-label" for="state">State: <span>*</span></label><br/>
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
                                <label class="control-label" for="Zip">Zip Code: </label>
                                <div class="controls">
                                    <input id="Zip" name="ZipCode" type="text" placeholder="Zip Code" class="input-xlarge form-control">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="control-group">
                                <label class="control-label" for="UserEmail">Email: </label>
                                <div class="controls">
                                    <input id="UserEmail" name="Email" type="email" placeholder="Email" class="input-xlarge form-control">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="control-group">
                                <label class="control-label" for="passwordreg">Password: </label>
                                <div class="controls">
                                    <input id="passwordreg" name="Email" type="password" placeholder="Password" class="input-xlarge form-control">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="control-group">
                                <label class="control-label" for="passwordRegVerify">Confirm Password: </label>
                                <div class="controls">
                                    <input id="passwordRegVerify" name="Email" type="password" placeholder="Password" class="input-xlarge form-control" style="margin-bottom: 30px">

                                </div>
                            </div>
                        </div>



                        <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                            <button type="button" class="btn btn-primary btn-lg raised" onclick="#">Submit</button>
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
