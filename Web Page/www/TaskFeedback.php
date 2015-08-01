<?php

require_once 'php/task_class.php';
require_once 'php/review_class.php';

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

$intaskid;

if (isset($_GET['id'])) $intaskid = $_GET['id'];
else
	die;

$error = array();

$task = new task();
$error = $task->getFromDB($intaskid);
if ($error == NULL)
{
}
else
	die;
	
if (isset($_POST['submit']))
{

	$_POST['taskid'] = $intaskid;
	$review = new review();
	$error = $review->getFromPOST($_POST);
	
	if (count($error) == 0)
	{
		$_SESSION['msg_reviewed'] = "Review placed";
		header("Location: /ViewTask.php?id=".$intaskid);
	}
	else
	{
		echo array_values($error);
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
    <!--font source-->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">


    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <link rel="stylesheet" href="/lib/jquery.raty.css">
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

        /* should fix image sizing issue */
        
        /* Lastly, apply responsive CSS fixes as necessary */
       /* @media (max-width: 767px) {
            #footer {
                margin-left: -20px;
                margin-right: -20px;
                padding-left: 20px;
                padding-right: 20px;
            }
        }*/

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
            <div class="col-md-8 col-sm-8 col-xs-8">
                <!-- Task images -->
                <div class="col-md-5 col-sm-5 col-xs-5">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
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
                            <img src="images/baking.jpg" alt="baking" style="width:auto;height:200px;margin: 0 auto" class="img-responsive">
                            <div class="carousel-caption">
                            </div>
                        </div>

                        <div class="item">
                            <img src="images/oil.jpg" alt="CarWork" style="width:auto;height:200px;margin: 0 auto" class="img-responsive">
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
                <div class="col-md-7 col-sm-7 col-xs-7">
                        <h1 style="font-size:18px;font-weight: bold;line-height: normal;margin: 0px;padding:0px;"><?php echo $task->title; ?></h1>

                        <p style="font-size:small;color:#777 !important;margin:0px;padding0px;font-weight:normal;line-height:normal;"><?php echo $task->description; ?></p>

                        <p>Final bid: <b>US $45.00</b></p>

                </div>
                
            <div class="row" style="border-style: solid; border-top: black;">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <p>Cerebella ii du attingere alligatus ac 
                        suspicari id eminenter. Ac in remotis 
                        exsolvi dicamne proxime ad an. Quam 
                        ei inge ea isti data soni ex duce. Tollentur
                        co an im tantumque videlicet. Naturae viderer 
                        propria co an se is. Repugnemus ei an ob 
                        distinguit propositio id facultatem percipimus. 
                        Dubitare cur lor experiar extensum. Jam pudeat 
                        vim ita movere maxima igitur nihili. Originis cognitio
                        temporis vi naturali ne. Memores revolvo hos poniturhaberem 
                        rei est vox movendi ejusdem. Omne duo cum ipse fert tria
                        rum vera. Seu nemoque frigida nostrae quasdam nec. Ad du 
                        haud et quas foco visu dare meas me. Ea externa relabor de
                        duratio. Illo addo ente si in quis ne hinc hanc</p>
                    <br>
                    <p>Bids:</p>
                    <p>Maybe have users that have bid on a task along with their ratings and bid.</p>

                </div>
            </div>
                
                
            </div>
               <!-- Give feedback on task -->
            <div class="col-md-4 col-sm-4 col-xs-4" style="border:lightgrey solid 3px; padding:14px 18px">
                <h1>Rate User on Task</h1>
                <p>Now that the task has been completed please rate how your task master did on the job.</p>
                
				<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET); ?>" method="post" enctype="multipart/form-data">
					<?php if (isset($error['rating'])) echo "Invalid rating"; ?>
					<div id="rating" scoreName="rating"></div>

                    <!-- Rating System Scripts -->
                    <script>
                        $('#rating').raty( {
                            score: function () {
                                return $(this).attr('data-score');
                            },
                            scoreName: 'rating'
                        });
                    </script>
                    <!-- Testing hidden input solution -->
					<!--<input type="text" name="rating" id="rating" placeholder="Rating" class="form-control">-->
					<!-- Textarea -->
					<div class="control-group">
						<label class="control-label" for="comment">Comments:</label>
						<div class="controls">
							<?php if (isset($error['comment'])) echo "Invalid comment"; ?>
							<textarea id="comment" name="comment" placeholder="Your thoughts" class="form-control"></textarea>
						</div>
						
						<div class="col-md-6 col-sm-6 col-xs-6 text-right">
							<input type="submit" name="submit" class="btn btn-primary btn-lg raised" value="Submit">
                        </div>
					</div>

				</form>
            </div>
            
            
        </div>


         
        <br>
       
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
