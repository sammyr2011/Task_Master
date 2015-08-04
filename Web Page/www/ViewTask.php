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
	require_once 'php/user_class.php';
	$user = new user();
	$user->getFromDB($task->userid);
	
	$date = new DateTime();
	$date->setTimestamp($task->enddatetime);
	$dateStr = $date->format('m/d/Y g:i A');
}
else
{
	$_SESSION['msg_badtaskid'] = "Bad task id";
}

if (isset($_POST['submit']))
{
	$biderror = array();
	if (isset($_SESSION['userid']))
		$biderror = $task->addBid($_SESSION['userid'], $_POST['Bid']);
	else
		$_SESSION['msg_needlogin'] = "Log in to bid";
	
	if (count($biderror) == 0)
	{
		$_SESSION['msg_bidplaced'] = "Bid Placed";
	}
	if (isset($biderror['active']))
	{
		$_SESSION['msg_bidover'] = "Bidding Ended";
	}
	if (isset($biderror['bidamount']))
	{
		$_SESSION['msg_bidless'] = "Must Bid Less Than Current";
	}
	if (isset($biderror['bidnegative']))
	{
		$_SESSION['msg_bidnegative'] = "Bid Cannot Be Negative";
	}
	if (isset($biderror['bidself']))
	{
		$_SESSION['msg_bidself'] = "Can't bid on own task";
	}
	if (isset($biderror['login']))
	{
		$_SESSION['msg_needlogin'] = "Log in to bid";
	}
	
	//redirect to prevent form resubmission on refresh
	header("Location: /ViewTask.php?id=".$intaskid);
	die;
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
            
			<?php if ($task->numimg != 0)
			{ ?> 
			
            <!-- Task images -->
            <div class="col-md-3 col-sm-3 col-xs-3">
				
                <div id="myCarousel TasksImages" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
					<?php for ($i=0; $i < $task->numimg; $i++)
					{ ?>
                    <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" <?php if($i == 0) echo 'class="active"'; ?>></li>
					<?php } ?>
                </ol>
				
				

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">

					<?php for ($i=0; $i < $task->numimg; $i++)
					{ ?>
                    <div class="item<?php if($i == 0) echo ' active'; ?>">
                        <img src="taskcontent/<?php echo $task->taskid; ?>/<?php echo $i; ?>.jpg" alt="Image <?php echo $i; ?>" style="width:auto;height:200px auto;margin: 0 auto">
                        <div class="carousel-caption">
                        </div>
                    </div>
					<?php } ?>

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
			
			<?php } 
			else
			{?>
			<!-- Task images -->
            <div class="col-md-3 col-sm-3 col-xs-3">
				
                <div id="myCarousel TasksImages" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="images/img_placeholder.jpg" alt="Image 1" style="width:auto;height:200px auto;margin: 0 auto">
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
			<?php } ?>
            
            <!-- Task title and description and bid -->
            <div class="col-md-6 col-sm-6 col-xs-6">
                <h1 style="font-size:18px;font-weight: bold;line-height: normal;margin: 0px;padding:0px;"><?php echo $task->title; ?></h1>

                <p style="font-size:small;color:#777 !important;margin:0px;padding0px;font-weight:normal;line-height:normal;"><?php echo $task->description; ?></p>
                
                <p style="font-size:small;color:#777 !important;margin:0px;padding0px;font-weight:normal;line-height:normal;">Location: <?php echo $task->location; ?></p>
                <br>
				
				<?php if ($task->active == 1)
				{
				?>
				<p><span id="endtime">Bid End Time: </span><?php echo $dateStr; ?> </p>
				<p>Time Left: <span id="countdown" style="color:red"></span></p>
				<script>
				function countDownFunc(enddate, divid)
				{
					// set the date we're counting down to
					var target_date = new Date(enddate*1000).getTime();

					// variables for time units
					var days, hours, minutes, seconds;

					// get tag element
					var countdown = document.getElementById(divid);

					//countdown function
					function countfn() {

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
						countdown.innerHTML = days + " days, " + hours + "h, "
							+ minutes + "m, " + seconds + "s";

						//should terminate when countdown is done
						//refresh the page to show new feedback controls
						if(seconds<=0 && minutes<=0 && days<=0 && hours<=0)
						{
							seconds=0;
							minutes=0;
							days=0;
							hours=0;
							clearInterval(timerID);
							location.reload(true);
							return;
						}

					}
					
					countfn();
					// update the tag with id "countdown" every 1 second
					var timerID = setInterval(countfn, 1000);
				}
				var timer = new countDownFunc(<?php echo $task->enddatetime; ?>, "countdown");
                </script>
				
				
				
				<p><span id="bidtime">Current bid: </span>$<b><?php echo $task->getCurrentBid(); ?>.00
				<?php 
				if (isset($_SESSION['userid']) && $task->getBidLeaderID() == $_SESSION['userid'])
				{
					echo ' (You)';
				} 
				?>
				</b></p>

                <!-- style="background-color: #E2E2E2;" -->
				<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] . '?'.http_build_query($_GET); ?>" method="post">
					<div class="col-md-6 col-sm-6 col-xs-6" >
						<label class="control-label" for="Bid">Set Bid: </label>
						<div class="controls">
							<input id="Bid" name="Bid" type="text" placeholder="Bid" class="input-xlarge form-control" style="margin-bottom: 30px">

							<input type="submit" name="submit" class="btn btn-primary btn-lg raised" value="Make Offer" style="margin-bottom: 30px">
						</div>
					</div>
				</form>
				
				<?php
				}
				else
				{ 
					echo '<p><span id="endtime">Bid Ended: </span>'.$dateStr.'</p>';
					echo '<p><span id="bidtime">Final bid: </span>$<b>'.$task->getCurrentBid().'.00';
					if (isset($_SESSION['userid']) && $task->getBidLeaderID() == $_SESSION['userid'])
					{
						echo ' (You)';
					} 
					
					if (isset($_SESSION['userid']) && ($_SESSION['userid'] == $task->userid || $_SESSION['userid'] == $task->winnerid) && $task->winnerid != NULL)
					{
					?>
					
					<div class="col-md-6 col-sm-6 col-xs-6 text-left">
							<a href="/TaskFeedback.php?id=<?php echo $task->taskid; ?>"><button type="button" class="btn btn-primary btn-lg raised" onclick="#">Leave Feedback</button></a>
					</div>
					<?php
					}
				}
				?>

            </div>
            
			<a href="/UserProfile.php?id=<?php echo $user->userid; ?>">
				<div class="col-md-3 col-sm-3 col-xs-3 text-center" style="border:solid lightgrey 3px;">
					<h3><?php echo $user->username; ?></h3>
					< href="UserProfile.php?id=<?php echo $user->userid ?>">
                        <img src="<?php echo $user->getAvatarURL(); ?>" height="100px">
					</a>
					<?php $rating = $user->getListerRating(); ?>
					<div id="ratyRating"></div>
					<?php echo $rating['weight']." ratings"; ?>
				</div>
			</a>
        </div>

        <!-- Rating System Scripts -->
        <script>
				
                $('#ratyRating').raty({ readOnly: true, score: <?php echo $rating['rating']; ?> });
        </script>

        <br>
        
        <div class="row" style="border-style: solid; border-top: black;"></div>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <p><b>Detailed Task Description:</b></p>
                <p><?php echo $task->content; ?></p>

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
