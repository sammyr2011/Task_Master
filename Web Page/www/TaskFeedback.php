<?php

require_once 'php/task_class.php';
require_once 'php/review_class.php';

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

//get the id of the task to be reviewed, blank page if none
$intaskid;

if (isset($_GET['id'])) 
	$intaskid = $_GET['id'];
else
	die;
	
if (!isset($_SESSION['userid']))
	die;

$error = array();

//get the task info
$task = new task();
$error = $task->getFromDB($intaskid);
if ($error != NULL)
	die;

//review form was submitted
if (isset($_POST['submit']))
{
	//prepare review info to be sent to POST
	$_POST['taskid'] = $intaskid;
	
	$review = new review();
	$error = $review->getFromPOST($_POST);
	
	if (count($error) == 0) //success
	{
		$_SESSION['msg_reviewed'] = "Review placed";
		header("Location: /ViewTask.php?id=".$intaskid);
		die;
	}
	else //error
	{
		die;
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

    <meta charset="utf-8">
    <title>Task Master</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<link rel="stylesheet" href="/lib/jquery.raty.css">
    <script src="vendor/jquery.js"></script>
    <script src="lib/jquery.raty.js"></script>
    <script src="javascripts/labs.js" type="text/javascript"></script>

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

        /* Lastly, apply responsive CSS fixes as necessary */
        /*@media (max-width: 767px) {
             #footer {
                 margin-left: -20px;
                 margin-right: -20px;
                 padding-left: 20px;
                 padding-right: 20px;
             }
         }*/

        @import url('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');

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
    <div class="container">
		<div class="col-md-8 col-sm-8 col-xs-8" style="border:lightgrey solid 3px; padding:14px 18px">
                <h1>Leave Feedback</h1>
				<?php if ($_SESSION['userid'] == $task->userid)
					echo '<p>If the task is completed, rate the user on how well they did the job!</p>';
				else
					echo '<p>If the task is completed, rate the lister on how well they described the task and followed up with you!</p>';
				?>
                
                
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
