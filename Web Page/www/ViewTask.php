<?php

require_once 'php/task_class.php';

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

$intaskid;

if (isset($_GET['id'])) $intaskid = $_GET['id'];

$error = array();

$task = new task();
$error = $task->getFromDB($intaskid);

if ($error == NULL)
{
}
else
{
	$_SESSION['msg_badtaskid'] = "Bad task id";
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
    <!--font source-->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">


    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <link rel="stylesheet" href="lib/jquery.raty.css">
    <script src="vendor/jquery.js"></script>
    <script src="lib/jquery.raty.js"></script>
    <script src="javascripts/labs.js" type="text/javascript"></script>

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
    <div class="container">
	<?php include "php/alerts.php"; ?>
        <div class="row">
            
            <!-- Task images -->
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div id="myCarousel TasksImages" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">

                    <div class="item active">
                        <img src="images/mowinggrass.jpg" alt="tutoring" style="width:auto;height:200px;margin: 0 auto">
                        <div class="carousel-caption">
                        </div>
                    </div>

                    <div class="item">
                        <img src="images/baking.jpg" alt="baking" style="width:auto;height:200px;margin: 0 auto">
                        <div class="carousel-caption">
                        </div>
                    </div>

                    <div class="item">
                        <img src="images/oil.jpg" alt="CarWork" style="width:auto;height:200px;margin: 0 auto">
                        <div class="carousel-caption">
                        </div>
                    </div>

                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
                </div>
            </div>
            
            <!-- Task title and description and bid -->
            <div class="col-md-6 col-sm-6 col-xs-6">
                    <h1 style="font-size:18px;font-weight: bold;line-height: normal;margin: 0px;padding:0px;"><?php echo $task->title; ?></h1>
					
					<p style="font-size:small;color:#777 !important;margin:0px;padding0px;font-weight:normal;line-height:normal;"><?php echo $task->description; ?></p>
					<br>
                    <p>Current bid: <b>US $45.00</b></p>
                    <p>Time Left: <span id="countdown" style="color:red"></span></p>

                    <script>
                        // set the date we're counting down to
                        var target_date = new Date("Aug 15, 2019").getTime();

                        // variables for time units
                        var days, hours, minutes, seconds;

                        // get tag element
                        var countdown = document.getElementById("countdown");

                        // update the tag with id "countdown" every 1 second
                        setInterval(function () {

                            // find the amount of "seconds" between now and target
                            var current_date = new Date().getTime();
                            var seconds_left = (target_date - current_date) / 1000;

                            // do some time calculations
                            days = parseInt(seconds_left / 86400);
                            seconds_left = seconds_left % 86400;

                            hours = parseInt(seconds_left / 3600);
                            seconds_left = seconds_left % 3600;

                            minutes = parseInt(seconds_left / 60);
                            seconds = parseInt(seconds_left % 60);

                            // format countdown string + set tag value
                            countdown.innerHTML = days + "d, " + hours + "h, "
                                + minutes + "m, " + seconds + "s";

                        }, 1000);

                    </script>




                    <!-- style="background-color: #E2E2E2;" -->
                    <div class="col-md-6 col-sm-6 col-xs-6" >
                        <label class="control-label" for="Bid">Set Bid: </label>
                        <div class="controls">
                            <input id="Bid" name="Bid" type="text" placeholder="Bid" class="input-xlarge form-control" style="margin-bottom: 30px">

                            <a href="#"><button type="button" class="btn btn-primary btn-lg raised">Make Offer</button></a>
                        </div>

                    </div>

            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="border:solid lightgrey 3px;">
                <h3>Username</h3>
                <img src="images/UserStock.png" height="100px">
                <div id="ratyRating"></div>
                <p>star rating (5)</p>
            </div>
        </div>

        <!-- Rating System Scripts -->
        <script>
                $('#ratyRating').raty({ readOnly: true, score: 4 });
        </script>

        <br>
        
        <div class="row" style="border-style: solid; border-top: black;"></div>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <p>Detailed Task Description:</p>
                <p>Cerebella ii du attingere alligatus ac suspicari id eminenter. Ac in remotis exsolvi dicamne proxime ad an. Quam ei inge ea isti data soni ex duce. Tollentur co an im tantumque videlicet. Naturae viderer propria co an se is. Repugnemus ei an ob distinguit propositio id facultatem percipimus. Dubitare cur lor experiar extensum. Jam pudeat vim ita movere maxima igitur nihili. Originis cognitio temporis vi naturali ne. Memores revolvo hos ponitur haberem rei est vox movendi ejusdem. Omne duo cum ipse fert tria rum vera. Seu nemoque frigida nostrae quasdam nec. Ad du haud et quas foco visu dare meas me. Ea externa relabor de duratio. Illo addo ente si in quis ne hinc hanc</p>

            </div>
        </div>
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
