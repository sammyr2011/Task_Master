<?php 

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

require_once 'php/user_class.php';
require_once 'php/task_class.php';
require_once 'php/review_class.php';
require_once 'php/lister.php';

if (!isset($_GET['id']))
	die;
	
$user = new user();
$user->getFromDB($_GET['id']);
	
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

    <!-- Raty Resources -->
    <link rel="stylesheet" href="/lib/jquery.raty.css">
    <script src="vendor/jquery.js"></script>
    <script src="lib/jquery.raty.js"></script>
    <script src="javascripts/labs.js" type="text/javascript"></script>

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

        /* Lastly, apply responsive CSS fixes as necessary */
        /*@media (max-width: 767px) {
             #footer {
                 margin-left: -20px;
                 margin-right: -20px;
                 padding-left: 20px;
                 padding-right: 20px;
             }
         }*/
		 
		 .avatar_big {
			width: 150px;
			height: 150px;
			line-height: 150px;
			text-align: center;
			float: left;
			margin-right: 15px;
		}
		
		.resize_fit_center {
			max-width:100%;
			max-height:100%;
			vertical-align: middle;
		}

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

        <!-- Will hold user general information -->
        <div class="row" style="margin-bottom: 30px">
            <!-- Avatar -->
            <div class="col-md-4 col-sm-4 col-xs-4" >
			<div class="avatar_big">
                <img class = "resize_fit_center" src="<?php echo $user->getAvatarURL(); ?>" style="height:150px;width:auto;float:none;display:inline-block;vertical-align:middle;" class="img-responsive">
            </div>
			</div>
            <!-- Username and avg ratings -->
            <div class="col-md-4 col-sm-4 col-xs-4">
                <h2><?php echo $user->username; ?></h2>
                <h4>Average Lister Rating</h4>
				<?php 
					$listerrating = $user->getListerRating(); 
					$doerrating = $user->getDoerRating(); 
				?>
                <div id="AVGL" data-score="<?php echo $listerrating['rating']; ?>"></div>
                <script>
                    $('#AVGL').raty({
                        readOnly: true,
                        score: function() {
                            return $(this).attr('data-score');
                        }
                    });
                </script>
				<?php echo $listerrating['weight']; ?> ratings
                <h4>Average Doer Rating</h4>
                <div id="AVGD" data-score="<?php echo $doerrating['rating']; ?>"></div>
                <script>
                    $('#AVGD').raty({
                        readOnly: true,
                        score: function() {
                            return $(this).attr('data-score');
                        }
                    });
                </script>
				<?php echo $doerrating['weight']; ?> ratings
            </div>
            <!-- Message me button -->
            <div class="col-md-4 col-sm-4 col-xs-4" style="height:150px;width:auto;float:none;display:inline-block;vertical-align:middle;">
                <?php
                //check if this is the current users page
                if($_SESSION['userid'] == $_GET['id'])
                {
                    ?>

                    <!-- User Info -->
                    <div class="row" style="padding:14px 18px">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <legend class="text-center">Actions</legend>

                                <a href="UpdatePassword.php" class="text-center">Update Password</a>
                                <br>
                                <a href="UpdateAddress.php" class="text-center">Update Address</a>
                                <br>
                                <a href="UpdateEmail.php" class="text-center">Update Email</a>
                                <br>
                                <a href="AvatarUpload.php" class="text-center">Change Avatar</a>

                        </div>
                    </div>

                    <?php
                }

                else {
                ?>
                    <button onclick="location.href='/Messaging.php?UserID=<?php echo $user->userid; ?>'" type="submit" name="messageuser" class="btn btn-primary btn-lg raised" style="vertical-align: middle">Message Me</button>
                <?php
                }
                ?>


            </div>
        </div>



        <!-- User Ratings as Lister -->
        <div class="row" style="border-top: thin lightgray">
        	
		<?php
		
		$reviews = array();
		$reviews = listReviewsByTime($user->userid);
		?>
            <legend>Lister(<?php echo count($reviews) ?>)</legend>
	
            <!-- A div per task -->
			<?php
			foreach ($reviews as $key=>$review)
			{
			?>
			<a href="/ViewTask.php?id=<?php echo $review->taskid; ?>">
				<div class="col-md-4 col-sm-4 col-xs-4">
					<!--Increment raing number in loop-->
					<!--
						 change the id of the div and the value in the JS script at the same time to have
						 variable number of ratings on a page.
					 -->
					<div id="score-callback<?php echo $key; ?>" data-score="<?php echo $review->rating; ?></"></div>
					<blockquote><?php echo $review->comment; ?></blockquote>

					<script>
						$('#score-callback<?php echo $key; ?>').raty({
							readOnly: true,
							score: function() {
								return $(this).attr('data-score');
							}
						});
					</script>
				</div>
			</a>
			<?php
			}
			
			?>


        </div>

        <!-- User Ratings as task doer -->
        <div class="row">
        	<?php
			
			$doreviews = array();
			$doreviews = listDoReviewsByTime($user->userid);
		?>
        	
            <legend>Doer(<?php echo count($doreviews) ?>)</legend>
		<?php
		foreach ($doreviews as $key=>$doreview)
		{
		?>
		<a href="/ViewTask.php?id=<?php echo $doreview->taskid; ?>">
			<div class="col-md-4 col-sm-4 col-xs-4">
				<!--Increment raing number in loop-->
				<!--
					 change the id of the div and the value in the JS script at the same time to have
					 variable number of ratings on a page.
				 -->
				<div id="score-callbackd<?php echo $key; ?>" data-score="<?php echo $doreview->rating; ?>"></div>
				<blockquote><?php echo $doreview->comment; ?></blockquote>

				<script>
					$('#score-callbackd<?php echo $key; ?>').raty({
						readOnly: true,
						score: function() {
							return $(this).attr('data-score');
						}
					});
				</script>
			</div>
		</a>
		<?php
		}
		
		?>


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
