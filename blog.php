<?php
session_start();
require 'functions.php';
$postsPerPage = 4;

if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
    $currentPage = $_GET['page'];

} else {
    $currentPage = 1;
}

if (!isset($_GET['userId'])) {
    header("Location: index.php");
} else {
    $userPosts = listPosts($_GET['userId'], $postsPerPage, $currentPage);
}
if ($totalPosts = getPostCountByUserId($_GET['userId'])) {
    $totalPages = ceil( $totalPosts/ $postsPerPage);
}


require 'header.php';
?>

    <div class="container">

        <div class="post">
            <?php if ($userPosts) {
                foreach ($userPosts as $key => $value) { ?>
                    <h1 class="post-header"><?php echo $value['title']; ?></h1>
                    <hr/>
                    <?php echo isset($value['image']) ? '<img src="img/' . $value['image'] . '" />' : '' ?>
                    <p class="post-content">
                        <?php echo $value['content']; ?>
                    </p>
                    <hr/>
                    <p class="post-when-by">
                        <?php echo $value['createdAt']; ?>
                    </p>
                <?php }
            } ?>
        </div>


        <nav>
            <?php if ($pagination = renderPagination($totalPages, $currentPage)) { ?>
                <ul class="pagination">
                    <?php foreach ($pagination as $value) {
                        if ($value['isActive']) { ?>
                            <li>
                                <a href="<?php echo '?userId=' . $_GET['userId'] . '&page=' . $value['page'] ?>">
                                    <span aria-hidden="true"><?php echo $value['text']; ?></span>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="disabled">
                                <span aria-hidden="true"><?php echo $value['text']; ?></span>
                            </li>
                        <?php }
                    } ?>
                </ul>
            <?php } ?>


        </nav>

    </div>
<?php
require 'footer.php';
?>