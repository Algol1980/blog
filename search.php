<?php
session_start();
require 'functions.php';

$postsPerPage = 5;

if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
    $currentPage = $_GET['page'];

} else {
    $currentPage = 1;
}

if (!isset($_GET['userId'])) {
    $totalSearchResults = totalSearchPostCount($_GET['search']);
    $totalSearchPages = ceil($totalSearchResults / $postsPerPage);
} elseif ($totalSearchResults = getSearchPostCount($_GET['userId'], $_GET['search'])) {
    $totalSearchPages = ceil($totalSearchResults / $postsPerPage);
}

require 'header.php';
?>
    <div class="container">
        <?php if (isset($_GET['userId']) && isset($_GET['search'])):
            $posts = searchByUser($_GET['userId'], $postsPerPage, $_GET['search'], $_GET['page']);
            foreach ($posts as $post): ?>

                <div class="post">
                    <h1 class="post-header"><?php echo $post['title'] ?></h1>
                    <?php if ($post['image']) { ?>
                        <hr/>
                        <img src="img/<?php echo $post['image']; ?>"/>
                    <?php } ?>
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
        <?php else:
            $posts = totalSearchPosts($_GET['search'], $postsPerPage, $_GET['page']);
            foreach ($posts as $key => $blog):
                foreach ($blog as $post):?>

                    <div class="post">
                        <h1 class="post-header"><?php echo $post['title'] ?>
                            <?php $currentUser = getUserById($key) ?>
                            <?php $postPage = ceil (getPostPosition($key, $post, $postsPerPage) / $postsPerPage) ?>
                            <a class="small pull-right" href="blog.php?userId=<?php echo $key . '&page=' . $postPage ?>">
                                <?php echo $currentUser['firstName'] . " " . $currentUser['lastName']; ?></a></h1>
                        <?php if ($post['image']) { ?>
                            <hr/>
                            <img src="img/<?php echo $post['image']; ?>"/>
                        <?php } ?>
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
            <?php endforeach ?>
        <?php endif ?>


        <nav>
            <?php if ($pagination = renderPagination($totalSearchPages, $currentPage)) { ?>
                <ul class="pagination">
                    <?php foreach ($pagination as $value) {
                        if ($value['isActive']) { ?>
                            <li>
                                <a href="?<?php echo 'page=' . $value['page'] . '&search=' . $_GET['search'] . (isset($_GET['userId']) ? '&userId=' . $_GET['userId'] : '') ?>">
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