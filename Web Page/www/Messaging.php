<?php 

require_once 'php/message_class.php';

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

if (!isset($_SESSION['userid']))
	die;

//requires POST['receiverID'] and POST['content']
//This is called from AJAX so we don't need to bother with the rest of the page
if (isset($_GET['submit']))
{
	$outmessage = new message();
	$outmessage->send($_GET);
	$outmessages = array();
	array_push($outmessages, $outmessage);
	printMessages($outmessages);
	die;
}

require_once 'php/user_class.php';
require_once 'php/message_lister.php';

//AJAX call to get new message content
if (isset($_GET['getConvos']))
{
	$convoUsers = array();
	$convoUsers = getConversationList();
	
	foreach ($convoUsers as $user)
	{
	?>
	<tr onclick="window.document.location='Messaging.php?UserID=<?php echo $user->userid; ?>';">
		<td>
			<div class="avatar_big">
			<img class="resize_fit_center" src="<?php echo $user->getAvatarURL(); ?>">
			</div>
			<span class="userNames"><?php echo $user->username; ?></span>
			<br>
				<span class="status">
				   First few characters of message...
				</span>
		</td>
	</tr>
	<?php 
	}
	die;
}

if (!isset($_GET['UserID']))
	die;

$userTalkingTo = new user();
$userTalkingTo->getFromDB($_GET['UserID']);

$userYou = new user();
$userYou->getFromDB($_SESSION['userid']);
	
//AJAX call to get old conversation content
if (isset($_GET['initMessages']))
{
	$oldMessages = array();
	$oldMessages = getReadMessages($_GET['UserID']);
	printMessages($oldMessages);
	die;
}

//AJAX call to get new message content
if (isset($_GET['getMessages']))
{
	$newMessages = array();
	$newMessages = getUnreadMessages($_GET['UserID']);
	printMessages($newMessages);
	die;
}

//Adds new messages to the message window.
function printMessages($messages)
{
	foreach($messages as $message)
	{
		$msguser = new user();
		$msguser->getFromDB($message->senderID);

		if ($message->senderID == $_SESSION['userid'])
			echo '<li class="server">';
		else
			echo '<li class="client">';
	?>
			<!-- links to UserProfile.php?id={userid} -->
			<a href="UserProfile.php?id=<?php echo $msguser->userid ?>" title>
				<!-- Use php to change alt="" to show actual username -->
				<div class="avatar_small">
				<img class = "resize_fit_center" src="<?php echo $msguser->getAvatarURL(); ?>">
				</div>
			</a>
			<div class="message-area">
				<span class="pointer"></span>
				<div class="info-row">
					<span class="user-name">
						<!-- Should also link to UserProfile.php?id= -->
						<a href="UserProfile.php?id=<?php echo $msguser->userid ?>">
							<!-- Username or first name of user -->
							<strong><?php echo $msguser->username; ?></strong>
						</a>
						says:
					</span>
					<!-- Time message was sent -->
					<span class="time">
						<?php echo date("M j, Y  g:i:s A", $message->timestamp); ?>
					</span>
					<div class="clear"></div>
				</div>
				<!-- User message -->
				<p><?php echo $message->content; ?></p>
			</div>
		</li>


	<?php
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

    <!-- Resources for box design -->
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/user_css.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/jChat.css">

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

        @import url('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');

        tr:hover {
            cursor: pointer;
            background-color: lightyellow;
        }
		
		.avatar_big {
			width: 75px;
			height: 75px;
			line-height: 75px;
			text-align: center;
			float: left;
			margin-right: 15px;
		}
		
		.avatar_small {
			width: 35px;
			height: 35px;
			line-height: 35px;
			text-align: center;
		}
		.resize_fit_center {
			max-width:100%;
			max-height:100%;
			vertical-align: middle;
		}

    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

</head>

<script>
$(function()
{
	$.get("Messaging.php", {UserID: "<?php echo $_GET['UserID']; ?>", initMessages: "1"}, function(data) 
	{
        $('#chat-area').append(data);
		$('#chat-area').scrollTop($('#chat-area')[0].scrollHeight);
    });
	getMessages();
	getConvos();

	setInterval(getMessages, 1000);
	setInterval(getConvos, 1000);

});
function getMessages() 
{
    $.get("Messaging.php", {UserID: "<?php echo $_GET['UserID']; ?>", getMessages: "1"}, function(data) 
	{
		if (data != "") //only scroll if there is a new message
		{
			$('#chat-area').append(data);
			$('#chat-area').scrollTop($('#chat-area')[0].scrollHeight);
		}
    });
}
function getConvos() 
{
    $.get("Messaging.php", {UserID: "<?php echo $_GET['UserID']; ?>", getConvos: "1"}, function(data) 
	{
		if (data != $('#convo-area').html()) //only scroll if there is a new message
		{
			$('#convo-area').html(data);
			$('#convo-area').scrollTop(0);
		}
    });
}
</script>

<!-- Part 1: Wrap all page content here -->
<div id="wrap">

    <!-- Fixed navbar -->
    <?php include "NavMod.php"; ?>

    <!-- Begin page content -->
    <div class="container-fluid">
        <div class="row-fluid grid-set" style="margin-bottom: 30px">
            <div class="col-md-4 col-sm-4 col-xs-4" >
                <div class="box" style="height:750px;">
                    <div class="header">
                        <h4>
                            <!-- The number in parenthesis is the number of new unread messages -->
                            Inbox
                        </h4>
                    </div>

                    <div class="content">

                        <table class="table table-condensed margin-reset" style="overflow-y: scroll">
                            <tbody style="overflow-y: scroll" id="convo-area">

                            <!-- On click should run an ajax request to update message page to reflect
                                who the user would like to communicate with-->
							
                            </tbody>
                        </table>

                    </div>
                </div>

                </div>
                <div class="col-md-8 col-sm-8 col-xs-8" >
                    <div class="box" style="height: 750px">
                        <div class="header">
                            <!-- Who the current user is talking to -->
                            <h4><?php echo $userTalkingTo->username; ?></h4>
                        </div>

                        <div class="content">
                            <ul class="messages-layout" style="overflow-y: scroll;height:677px; " id="chat-area">

                            </ul>

                            <!-- Enter Message field -->
                            <span class="session_time">Online</span>
                            <span id="sample"></span>
                            <form action="Messaging.php" method="POST" class="form-inline pull-right" name="sendie" id="sendie">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="sendie" placeholder="Message" style="width:750px" name="content">
                                        <input type="hidden" id="sendie" name="receiverID" value="<?php echo $_GET['UserID']; ?>">
										<input type="hidden" id="sendie" name="submit" value="1">
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary" id="sendie" name="submit" value="Send">

                            </form>
							<script type="text/javascript">
								$(function() {
                                    $('#sendie').submit(function(e) {
										e.preventDefault(); //STOP default action
										$.ajax({
											url: $(this).attr("action"),
											type: "GET",
											data: $(this).serialize(),
											success: function(data) {
												$('#chat-area').append(data);
												$('#chat-area').scrollTop($('#chat-area')[0].scrollHeight);
											}
										});
										$('#sendie').find('input:text').val(''); 
										return false;
									});
								});
                            </script>

                        </div>



                        </div>
                    </div>
                </div>
            </div>

        </div>
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
