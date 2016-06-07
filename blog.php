<?php
session_start();
require 'functions.php';
if (!isset($_GET['userId'])) {
    header("Location: index.php");
} 
else {
    $userPosts = listPosts($_GET['userId']);
}
//if (isset($_SESSION['user']) && isset($_SESSION['userId'])) {
//    $userPosts = listPosts($_SESSION['userId']);
//
//}

require 'header.php';
?>

<div class="container">

    <div class="post">
        <?php if($userPosts) {
            foreach($userPosts as $key => $value) { ?>
        <h1 class="post-header"><?php echo $value['title']; ?></h1>
        <hr />
        <?php echo isset($value['image']) ? '<img src="img/' . $value['image'] .'" />' : '' ?>
        <p class="post-content">
            <?php echo $value['content']; ?>
        </p>
        <hr />
        <p class="post-when-by">
            <?php echo $value['createdAt']; ?>
        </p>
            <?php }
         } ?>
    </div>



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