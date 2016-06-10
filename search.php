<?php
session_start();
require 'functions.php';
require 'header.php';
$postsPerPage = 5;

if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
    $currentPage = $_GET['page'];

} else {
    $currentPage = 1;
}

if (!isset($_GET['userId'])) {
    header("Location: index.php");
}
if ($totalSearchResults = getSearchPostCount($_GET['userId'], $_GET['search'])) {
    $totalSearchPages = ceil($totalSearchResults / $postsPerPage);
}


?>
<div class="container">
    <?php if (isset($_GET['userId']) && isset($_GET['search'])):
        $posts = searchByUser($_GET['userId'], $postsPerPage, $_GET['search'], $_GET['page']);
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
        <?php if ($pagination = renderPagination($totalSearchPages, $currentPage)) { ?>
            <ul class="pagination">
                <?php foreach ($pagination as $value) {
                    if ($value['isActive']) { ?>
                        <li>
                            <a href="<?php echo '?userId=' . $_GET['userId'] . '&page=' . $value['page'] . '&search=' . $_GET['search']?>">
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