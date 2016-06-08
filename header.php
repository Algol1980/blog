<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$rightScripts = ['blog.php', 'photos.php', 'posts.php'];
?> 


<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="css/bootstrap.min.css" />-->
    <script type="text/javascript" src="js/jquery-2.2.2.min.js" ></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap-simplex.min.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <title>Blog</title>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">
                <?php if (isset($_GET['userId']) && in_array(basename($_SERVER['SCRIPT_NAME']), $rightScripts)) {
                    if ($currentUser = getUserById($_GET['userId'])) {
                        echo $currentUser['firstName'] . " " . $currentUser['lastName'];
                    }
                }
                else {
                    echo 'Blog System';
                }
                ?>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if(isset($currentUser)) { ?>
                    <?php //TODO: implement active li ?>
                    <li><a href="blog.php?userId=<?php echo $currentUser['userId'] ?>">Posts <span class="sr-only">(current)</span></a></li>
                    <li><a href="photos.php?userId=<?php echo $currentUser['userId'] ?>">Photos</a></li>
                <?php } ?>
            </ul>
            <form class="navbar-form navbar-left" role="search" method="get" action="search.php">
                <div class="form-group">
                    <!--<input type="text" class="form-control" placeholder="Search">-->
                    <?php if(isset($currentUser)):  ?>
                        <input type="hidden" value="<?php echo $currentUser['userId']; ?>" name="userId">
                    <?php endif ?>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
                    </div><!-- /input-group -->
                </div>
                <!--<button type="submit" class="btn btn-default">Submit</button>-->
            </form>
            <ul class="nav navbar-nav navbar-right">
                <?php if (!isset($_SESSION['user'])) { ?>
                <li><a href="login.php">Log in</a></li>
                <li><a href="signup.php">Sign up</a></li>
                <?php } else { ?>
                <li class="dropdown">
                    <a href="blog.php?userId=<?php echo isset($_SESSION['userId']) ? $_SESSION['userId'] : ''; ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION['firstName'] . " " . $_SESSION['lastName']; ?>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="add-post.php">Add post</a></li>
                        <li><a href="#">Edit profile</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php  } ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
