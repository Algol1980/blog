<?php
session_start();

require 'functions.php';
if (!empty($_POST)) {
    if(!empty($_POST['title']) && !empty($_POST['content'])) {
        if(addPost($_SESSION['userId'], $_POST['title'], $_POST['content'], $_FILES['image']['tmp_name'])) {
            header("Location: blog.php");
        }
    }

}
require 'header.php';
?>
<div class="container">

    <div class="col-lg-8 col-lg-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>Add Post</h2>
            </div>
            <div class="panel-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo isset($_POST['title']) ? $_POST['title'] : ''; ?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea class="form-control" name="content" rows="5"><?php echo isset($_POST['content']) ? $_POST['content'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Picture (optional)</label>
                        <input type="file" name="image" class="form-control" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary form-control" />
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<?php 
require 'footer.php';
?>