  <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Task Master</a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li ><a href="CreateTask.php">Create Task</a></li>
                    <li><a href="#">View Tasks</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="UserRegistration.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                    <!--
                    <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    -->

                    <li>
                        <div class="dropdown">
    <button type="button" class="btn btn-default navbar-btn" data-toggle="dropdown">Login</button>

    <div class="dropdown-menu" style="padding: 10px; background: #ddd">
        <form action="" role="form">
            <div class="form-group">
                <label for="user">User</label>
                <input type="text" class="form-control" id="user" placeholder="User" />
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" />
            </div>
            <div class="checkbox">

                <label>
                    <input type="checkbox" id="RememberMe"> Remember Me
                </label>
            </div>
            <button type="submit" class="btn btn-default">Sign in</button>
        </form>
    </div>
</div>
                    </li>

                </ul>
            </div>
        </div>

        <!-- Search bar-->
        <div class="container">
            <div class="row">

                <!--
                <div class="col-md-1">
                    <select title="search in">
                        <option>Food</option>
                        <option>Car</option>
                        <option>Lawn</option>
                    </select>
                </div>
                -->
                <div id="custom-search-input">

                    <div class="input-group col-md-12">

                        <div class="input-group-btn input-group-btn-static">
                            <button type="button" class="btn btn-default" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu" role="menu" id="drop">
                                <li><a href="#">Select Category</a></li>
                            </ul>
                        </div>

                        <input type="text" class="  search-query form-control" placeholder="Search" />
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button">
                                        <span class=" glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                    </div>
                </div>
            </div>
        </div>
</nav>