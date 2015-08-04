<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <meta charset="utf-8">
    <title>Task Master</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSS -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">

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

		
		@import url('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');

        .carousel .item {
            width: 75%; /*slider width*/
            /*max-height: 760px;*/ /*slider height*/
        }
        .carousel .item img {
            width: 100%; /*img width*/

        }
        /*add some makeup*/
        .carousel .carousel-control {
            background: none;
            border: none;
            top: 50%;
        }
        /*full width container
        @media (max-width: 767px) {
            .block {
                margin-left: -20px;
                margin-right: -20px;
            }
        */
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
</head>

<body>


<!-- Part 1: Wrap all page content here -->
<div id="wrap">

    <!-- Fixed navbar -->
    <?php include "NavMod.php"; ?>

    <!-- Begin page content -->
    <div>

	<?php include "php/alerts.php"; ?>
        <section class="block">
            <div id="myCarousel" class="carousel slide" style="margin-bottom: 50px">
                <div class="carousel-inner text-center">
                    <div class="active item">
                        <img src="images/baking.jpg" alt="Slide1" style="margin: auto auto auto 230px"/>
                    </div>
                    <div class="item">
                        <img src="images/lawn2.jpg" alt="Slide2" style="margin: auto auto auto 230px"/>
                    </div>
                    <div class="item">
                        <img src="images/car%20cleaning.jpg" alt="Slide3" style="margin: auto auto auto 230px"/>
                    </div>
                </div>
                <a class="carousel-control left" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control right" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div>
        </section>

        <div class="container marketing">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <img class="img-circle" src="images/headshot_grandma.jpg" width="140" height="140">
                    <h2>Heading</h2>
                    <blockquote>thoughts on site</blockquote>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <img class="img-circle" src="images/headshot_attractive_female.jpg" width="140" height="140">
                    <h2>Heading</h2>
                    <blockquote>thoughts on site</blockquote>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <img class="img-circle" src="images/headshot_old_chinese_man.jpg" width="140" height="140">
                    <h2>Heading</h2>
                    <blockquote>thoughts on site</blockquote>
                </div>
            </div>
        </div>



        <div id="push"></div>


		<!--
            not very attrativ looking

        <div class="row">
			<div class="col-md-6 col-sm-6 col-xs-6 text-right">
				<a href="CreateTask.php"><button type="button" class="btn btn-primary btn-lg raised" style="margin-bottom: 500px">Create Task</button></a>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6 text-left">
				<a href="ViewTasks.php"><button type="button" class="btn btn-primary btn-lg raised">Find Task</button></a>
			</div>
		</div>
	    -->
    </div>
	<div id="push"></div>
</div>

<div id="push"></div>

<div id="footer">
    <div class="container">
        <p class="muted credit" style="color: white">COP 4331 Project 2</p>
    </div>
</div>


</body>
</html>
