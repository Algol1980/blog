<?php
session_start();
require 'functions.php';
require 'header.php';

?>
<div class="container">
    <?php if (isset($_GET['userId']) && isset($_GET['search'])):
        $posts = searchByUser($_GET['userId'], $_GET['search'], $_GET['page']);
        foreach ($posts as $post): ?>

            <div class="post">
                <h1 class="post-header"><?php echo $post['title'] ?></h1>
                <hr/>
                <p class="post-content">
                    <?php echo $post['content'] ?>
                </p>
                <hr/>
                <p class="post-when-by">
                    <?php echo $post['createdAt'] ?>
                </p>
            </div>
        <?php endforeach ?>
    <?php endif ?>

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