<?php

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

if (!isset($_SESSION['userid']))
	die;

//requires POST['receiverID'] and POST['content']
//This is called from AJAX so we don't need to bother with the rest of the page
if (isset($_POST['submit']))
{
	$outmessage = new message();
	$outmessage->send($_POST);
	die;
}

require_once 'php/user_class.php';
require_once 'php/message_lister.php';

if (!isset($_GET['UserID']))
	die;
	
$convoUsers = array();
$convoUsers = getConversationList();

$oldMessages = array();
$oldMessages = getReadMessages($_GET['UserID']);

$newMessages = array();
$newMessages = getUnreadMessages($_GET['UserID']);

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
            min-height: 100% !important;
            height: auto !important;
            height: 100% !important;
            /* Negative indent footer by it's height */
            margin: -90px auto 90px !important;
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

    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">



    <script type="text/javascript">

        function loadInbox() {

            var inbox = <?php echo $convoUsers ?>;
            var i;

            for(i = 0; inbox.length ; i++ )
            {
                //html to append
                var inboxWrapper = '' +
                    '<!-- link stores user id of both users that will be messaging -->' +
                    '<tr onclick="window.document.location='Messaging.php?UserID1=&&UserID2=';">' +
                    '<td>' +
                    '<img src="images/UserStock.png" style="height:75px;width:auto">' +
                    '</td>' +
                    '<td>' +
                    '<span class="userNames">' + inbox[i] + '</span>' +
                    '<br>' +
                    '<span class="status">' +
                    'First few characters of message...' +
                    '</span>' +
                    '</td>' +
                    '</tr>';

                $("#appendTarget").append(inboxWrapper);

            }
        }

    </script>


</head>

<body>


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
                            Inbox(2)
                        </h4>
                    </div>

                    <div class="content">

                        <table class="table table-condensed margin-reset" style="overflow-y: scroll">
                            <tbody>

                            <!-- On click should run an ajax request to update message page to reflect
                                who the user would like to communicate with-->
							<?php
							foreach ($convoUsers as $user)
							{
							?>
                            <tr onclick="#">
                                <td>
                                    <img src="<?php echo $user->getAvatarURL(); ?>" style="height:75px;width:auto">
                                </td>
                                <td>
                                    <span class="userNames"><?php echo $user->username; ?></span>
                                    <br>
                                        <span class="status">
                                           First few characters of message...
                                        </span>
                                </td>
                            </tr>
							<?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>

                </div>
                <div class="col-md-8 col-sm-8 col-xs-8" >
                    <div class="box" style="height: 750px">
                        <div class="header">
                            <!-- Who the current user is talking to -->
                            <h4>John Doe</h4>
                        </div>

                        <div class="content">
                            <ul class="messages-layout" style="overflow-y: hidden;height:677px;" id="chat-area">

                                <!-- Each new message is a new li -->

                                <!-- Message by other user -->
                                <li class="client">
                                    <!-- links to UserProfile.php?id={userid} -->
                                    <a href="#" title>
                                        <!-- Use php to change alt="" to show actual username -->
                                        <img src="images/UserStock.png" alt="username" height="35px" width="auto">
                                    </a>
                                    <div class="message-area">
                                        <span class="pointer"></span>
                                        <div class="info-row">
                                            <span class="user-name">
                                                <!-- Should also link to UserProfile.php?id= -->
                                                <a href="#">
                                                    <!-- Username or first name of user -->
                                                    <strong>Anna</strong>
                                                </a>
                                                says:
                                            </span>
                                            <!-- Time message was sent -->
                                            <span class="time">
                                                August 1, 2015 9:15 AM
                                            </span>
                                            <div class="clear"></div>
                                        </div>
                                        <!-- User message -->
                                        <p>Message goes here</p>
                                    </div>
                                </li>

                                <!-- Message from current user -->
                                <li class="server">
                                    <!-- links to UserProfile.php?id={userid} -->
                                    <a href="#" title>
                                        <!-- Use php to change alt="" to show actual username -->
                                        <img src="images/UserStock.png" alt="username" height="35px" width="auto">
                                    </a>
                                    <div class="message-area">
                                        <span class="pointer"></span>
                                        <div class="info-row">
                                            <span class="user-name">
                                                <!-- Should also link to UserProfile.php?id= -->
                                                <a href="#">
                                                    <!-- Username or first name of user -->
                                                    <strong>John</strong>
                                                </a>
                                                says:
                                            </span>
                                            <!-- Time message was sent -->
                                            <span class="time">
                                                August 1, 2015 9:15 AM
                                            </span>
                                            <div class="clear"></div>
                                        </div>
                                        <!-- User message -->
                                        <p>Message goes here</p>
                                    </div>
                                </li>
                            </ul>

                            <!-- Enter Message field -->
                            <span class="session_time">Online</span>
                            <span id="sample"></span>
                            <form class="form-inline pull-right">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="sendie" placeholder="Message" style="width:750px">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Send</button>
                            </form>

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
