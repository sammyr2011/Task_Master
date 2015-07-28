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
                <img src="images/UserStock.png" style="height:150px;width:auto;float:none;display:inline-block;vertical-align:middle;">
            </div>
            <!-- Username and avg ratings -->
            <div class="col-md-4 col-sm-4 col-xs-4">
                <h2>Username</h2>
                <h4>Avgerage Lister Rating</h4>
                <div id="AVGL" data-score="4.5"></div>
                <script>
                    $('#AVGL').raty({
                        readOnly: true,
                        score: function() {
                            return $(this).attr('data-score');
                        }
                    });
                </script>
                <h4>Average Doer Rating</h4>
                <div id="AVGD" data-score="5"></div>
                <script>
                    $('#AVGD').raty({
                        readOnly: true,
                        score: function() {
                            return $(this).attr('data-score');
                        }
                    });
                </script>
            </div>
            <!-- Message me button -->
            <div class="col-md-4 col-sm-4 col-xs-4">
                <input type="submit" name="messageuser" class="btn btn-primary btn-lg raised" value="Message Me">
            </div>
        </div>

        <!-- User Ratings as Lister -->
        <div class="row" style="border-top: thin lightgray">
            <legend>Lister(number of tasks as a lister)</legend>

            <!-- A div per task -->
            <div class="col-md-4 col-sm-4 col-xs-4">
                <!--Increment raing number in loop-->
                <p>Task Title</p>
                <!--
                     change the id of the div and the value in the JS script at the same time to have
                     variable number of ratings on a page.
                 -->
                <div id="score-callback1" data-score="1"></div>
                <blockquote>Comment left with rating</blockquote>

                <script>
                    $('#score-callback1').raty({
                        readOnly: true,
                        score: function() {
                            return $(this).attr('data-score');
                        }
                    });
                </script>
            </div>


        </div>

        <!-- User Ratings as task doer -->
        <div class="row">
            <legend>Doer(number of tasks as a lister)</legend>

            <!-- A div per task -->
            <div class="col-md-4 col-sm-4 col-xs-4">
                <!--Increment raing number in loop-->
                <p>Task Title</p>

                <!--
                     change the id of the div and the value in the JS script at the same time to have
                     variable number of ratings on a page.
                 -->
                <div id="doer-callback1" data-score="1"></div>
                <blockquote>Comment left with rating</blockquote>

                <script>
                    $('#doer-callback1').raty({
                        readOnly: true,
                        score: function() {
                            return $(this).attr('data-score');
                        }
                    });
                </script>

                <blockquote>Comment left with rating</blockquote>
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
