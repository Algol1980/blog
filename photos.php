<?php
session_start();
require 'BlogDB.php';
$imagesPerPage = 5;


if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
    $currentPage = $_GET['page'];

} else {
    $currentPage = 1;
}

if (!isset($_GET['userId'])) {
    header("Location: index.php");
} else {
    $userImages = BlogDB::getPhotosByUser($_GET['userId'], $imagesPerPage, $currentPage);
}
if ($totalImages = BlogDB::getPhotosCount($_GET['userId'])) {
    $totalImagePages = ceil($totalImages / $imagesPerPage);
}


require 'header.php';
?>

?>

<div class="container">
    <?php if ($userImages) {
        foreach ($userImages as $value) { ?>
            <div class="photo">
                <img src="<?php echo 'img/' . $value; ?>"/>
            </div>
        <?php }
    } ?>
    <div class="clearfix clear"></div>


    <nav>
        <?php if ($pagination = BlogDB::renderPagination($totalImagePages, $currentPage)) { ?>
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
</body>
</html>