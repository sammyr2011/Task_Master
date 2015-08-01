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

    <!-- Resources for box design -->
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/user_css.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">

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
                            <tr onclick="#">
                                <td>
                                    <img src="images/UserStock.png" style="height:75px;width:auto">
                                </td>
                                <td>
                                    <span class="userNames">Bob</span>
                                    <br>
                                        <span class="status">
                                           First few characters of message...
                                        </span>
                                </td>
                            </tr>
                            <tr onclick="#">
                                <td>
                                    <img src="images/UserStock.png" style="height:75px;width:auto">
                                </td>
                                <td>
                                    <span class="userNames">Sally</span>
                                    <br>
                                        <span class="status">
                                            First few characters of message...
                                        </span>
                                </td>
                            </tr>

                            <tr onclick="#">
                                <td>
                                    <img src="images/UserStock.png" style="height:75px;width:auto">
                                </td>
                                <td>
                                    <span class="userNames">Jill</span>
                                    <br>
                                        <span class="status">
                                           First few characters of message...
                                        </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
            <div class="col-md-8 col-sm-8 col-xs-8" >
                <div class="box" style="border-style:solid;border-color:lightgrey;height:750px">
                    <legend>Username(other user)</legend>

                    <!-- Storing messages in striped rows for contrast

                    php instructions:
                        When a new message from the other user is received the message should float to the left
                        whereas when the input is from the current user it will float to the right.

                    -->
                    <div class="content">

                        <table class="table table-striped margin-reset" style="overflow-y: scroll">
                            <tbody>

                            <!-- Floats left e.g. message from other user -->
                            <tr onclick="#">
                                <td>
                                    <img src="images/UserStock.png" style="height:75px;width:auto">
                                </td>
                                <td>
                                    <div style="border-style:solid;border-color:blue;height:150px;">
                                        <br>
                                            <span class="status">
                                               First few characters of message...
                                            </span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Floats right -->
                            <tr onclick="#">
                                <td>
                                    <div style="border-style:solid;border-color:blue;height:150px;">
                                        <br>
                                            <span class="status">
                                               First few characters of message...
                                            </span>
                                    </div>

                                </td>
                                <td>
                                    <img src="images/UserStock.png" style="height:75px;width:auto">
                                </td>
                            </tr>


                            </tbody>
                        </table>

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
