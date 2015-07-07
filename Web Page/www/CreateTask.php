<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>



    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>




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

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <script>
        $(function() {
            $( "#datepicker" ).datetimepicker({minDate: 0});
        });

        $(function() {
            $( "#datepicker2" ).datetimepicker({minDate: 0});
        });
    </script>

</head>

<body>


<!-- Part 1: Wrap all page content here -->
<div id="wrap">

    <!-- Fixed navbar -->
    <?php include "NavMod.php"; ?>

    <!-- Begin page content -->
    <div class="container" style="border:black 9px">
        <form class="form-horizontal" >
            <fieldset>
                <div class="row">
                    <div class="col-md-8 col-sm-8" style="border: 1px solid #ddd; border-radius: 3px 3px 3px 3px;padding: 14px 26px 26px;box-shadow: 4px 4px 1px #c4c4c4;">

                        <!-- Form Name -->
                        <legend>Task Creation</legend>

                        <br>

                        <!-- Text input-->
                            <div class="control-group">
                                <label class="control-label" for="textinput">Text Input</label>
                                <div class="controls">
                                    <input id="textinput" name="textinput" type="text" placeholder="title" class="input-xlarge form-control">

                                </div>
                            </div>



                            <!-- Textarea -->
                            <div class="control-group">
                                <label class="control-label" for="description">Task Description</label>
                                <div class="controls">
                                    <textarea id="description" name="description" placeholder="Describe task..." class="form-control"></textarea>
                                </div>
                            </div>


                            <!-- Select Multiple -->
                            <div class="control-group">
                                <label class="control-label" for="selectcategory">Select Task Category</label>
                                <div class="controls">
                                    <select id="selectcategory" name="selectcategory" class="input-xlarge form-control" multiple="multiple">
                                        <option>Option one</option>
                                        <option>Option two</option>
                                        <option>Option three</option>
                                    </select>
                                </div>
                            </div>


                            <!-- input amount willing to pay-->
                            <br>
                            <p><b>Initial Task Pay</b></p>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" placeholder="US Dollar">
                                <span class="input-group-addon">.00</span>
                            </div>


                            <!-- Text input-->
<!--                            <div class="control-group">
                                <label class="control-label" for="tags">Related Keywords</label>
                                <div class="controls">
                                    <input id="tags" name="tags" type="text" placeholder="keywords" class="input-xlarge form-control" required="">

                                    <button type="button" class="btn btn-default" aria-label="Left Align" data-toggle="popover" data-placement="left" title data-content="Input words separated by spaces that relate to the task">
                                        <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>-->
                        

                        <br>



                        <div id="custom-search-input">
                            <label class="control-label">Related Keywords</label>
                            <div class="input-group col-md-12 col-sm-12">

                                <input type="text" class="  search-query form-control" placeholder="keywords" />
                                <span class="input-group-btn">
                                    <button class="btn" type="button">
                                        <span class=" glyphicon glyphicon-question-sign"></span>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <br>

                        <!--  Think about changing type to date rather than using text and the JQuery datepicker-->
                         <!-- Select End Date-->
                        <p><b>End Bidding Date:</b> <input type="text" id="datepicker" placeholder="Click Here" class="form-control"></p>

                        <!-- When to have the job done by-->
                        <p><b>Task Completion Date:</b> <input type="text" id="datepicker2" placeholder="Click Here" class="form-control"></p>

                         <!-- File Button -->
                        <div class="control-group">
                            <label class="control-label" for="imageinput">Select Images</label>
                            <div class="controls">
                                <input id="imageinput" name="imageinput" class="input-file" type="file" multiple="true" accept="image/gif, image/jpeg">
                            </div>
                        </div>

                        <br><br>

                        <div class="col-md-6 col-sm-6 text-right">
                            <button type="button" class="btn btn-primary btn-lg raised" onclick="#">Submit</button>
                        </div>

                        <div class="col-md-6 col-sm-6 text-left">
                            <button type="button" class="btn btn-primary btn-lg raised" onclick="#">Cancel</button>
                        </div>

                    </div>

                    <!-- Help side div-->
                    <div class="col-md-4 col-sm-4">
                        <p>Welcome to the Task Creation page.  Congratulations you have taken
                        the first step to having your task completed by a Task Master.</p><br>

                        <p>The <b>Text Input</b> field is used to title the task so others can acurrately choose
                        an applicable task.</p>
                        <p>The <b>Task Description</b> is a short anecdote of the task that you would like one our Task Masters to complete.</p>
                        <p>For the <b>Select Task Category</b> field choose the category that most closely relates to the task.</p>
                        <p>The <b>Initial Task Pay</b> field is used to set the amount you are willing to pay for someone
                        to do the task at hand.</p>
                        <p>The <b>Related Keywords</b> field asks that you input words that relate to the task. For example,
                        if the task were "Bake a cake", keywords that would apply would be "food, baking, bake, cake, sweets,..."</p>
                        <p>The <b>End Bidding Date</b> field allows you to select the date to end the bidding.</p>
                        <p>The <b>Task Completion Date</b> field allows you to select the date you would like to have the task completed by.</p>
                        <p>The <b>Select Images</b> button allows you to input any revalent images if any.</p>

                    </div>


                </div>

            </fieldset>
        </form>


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
