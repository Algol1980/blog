<?php
session_start();
 require 'functions.php';
require 'header.php';
?>
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
            <a class="navbar-brand" href="blog.html">Андрей Иванов</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="blog.html">Posts <span class="sr-only">(current)</span></a></li>
                <li><a href="blog.html">Photos</a></li>
            </ul>
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <!--<input type="text" class="form-control" placeholder="Search">-->
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
                <li><a href="login.html">Log in</a></li>
                <li><a href="signup.html">Sign up</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mike Smith <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="add-post.html">Add post</a></li>
                        <li><a href="#">Edit profile</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container">
    <?php if(isset($_GET['userId']) && isset($_GET['search'])):
        $posts = searchByUser($_GET['userId'], $_GET['search'], $_GET['page']);
    foreach ($posts as $post ): ?>

    <div class="post">
        <h1 class="post-header"><?php echo $post['title'] ?></h1>
        <hr />
        <p class="post-content">
            <?php echo $post['content'] ?>
        </p>
        <hr />
        <p class="post-when-by">
            <?php echo $post['createdAt'] ?>
        </p>
    </div>
        <?php endforeach  ?>
        <?php endif  ?>

    <nav>
        <ul class="pagination">
            <li>
                <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li>
                <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

</div>
<?php
require 'footer.php';
?>