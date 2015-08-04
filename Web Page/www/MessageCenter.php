<?php

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if (!isset($_SESSION['userid']))
    die;


require_once 'php/user_class.php';
require_once 'php/message_lister.php';



$convoUsers = array();
$convoUsers = getConversationList();

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
    <!-- <link rel="stylesheet" href="css/reset.css">-->
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
            height: 60px !important;
        }
        #footer {
            background-color: #222 !important;
        }

        /* Lastly, apply responsive CSS fixes as necessary */
        @media (max-width: 767px) {
            #footer {
                margin-left: -20px !important;
                margin-right: -20px !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }

        @import url('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');

        .date-form { margin: 10px; }
        label.cont        rol-label span { cursor: pointer; }

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
			vertical-align: center;
		}

    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
		
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->




</head>

<body>

<script>
$(function()
{
	getConvos();

	setInterval(getConvos, 1000);

});

function getConvos() 
{
    $.get("Messaging.php", {getConvos: "1"}, function(data) 
	{
		if (data != "") //only scroll if there is a new message
		{
			$('#appendTarget').html(data);
			$('#appendTarget').scrollTop(0);
		}
    });
}
</script>


<!-- Part 1: Wrap all page content here -->
<div id="wrap">

    <!-- Fixed navbar -->
    <?php include "NavMod.php"; ?>

    <!-- Begin page content -->
    <div class="container">
        <div class="row-fluid grid-set">
            <div class="span6">
                <div class="box">
                    <div class="header">
                        <h4>
                            <!-- The number in parenthesis is the number of new unread messages -->
                            Inbox
                        </h4>
                    </div>

                    <div class="content" style="height: 750px">

                        <table class="table table-condensed margin-reset" style="overflow-y: scroll">
                            <tbody id="appendTarget">

                                <!-- On click should run an ajax request to update message page to reflect
                                   who the user would like to communicate with-->
                                

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>

			<!--
            <div class="span6">
                <div class="box">
                    <div class="header">
                        <h4>Recommended Users</h4>
                    </div>
                    <div class="content" style="height: 750px">

                        <table class="table table-condensed margin-reset">
                            <tbody>

                            <tr onclick="window.document.location='Messaging.php?id=';">
                                <td>
                                    <img src="images/UserStock.png" style="height:75px;width:auto">
                                </td>
                                <td>
                                    <span class="userNames">Bob</span>
                                    <br>
                                        <span class="status">
                                            Associated Task
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="images/UserStock.png" style="height:75px;width:auto">
                                </td>
                                <td>
                                    <span class="userNames">Sally</span>
                                    <br>
                                        <span class="status">
                                            Associated Task
                                        </span>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <img src="images/UserStock.png" style="height:75px;width:auto">
                                </td>
                                <td>
                                    <span class="userNames">Jill</span>
                                    <br>
                                        <span class="status">
                                            Associated Task
                                        </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> -->

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
