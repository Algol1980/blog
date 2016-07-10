<?php 
session_start();

require 'BlogDB.php';
if(!empty($_POST)) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $user = BlogDB::checkUser($_POST['email'], $_POST['password']);
        if($user) {
            $_SESSION['user'] = true;
            $_SESSION['userId'] = $user['userId'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['lastName'] = $user['lastName'];
            header("Location: index.php");
        }
        
    }
}
require 'header.php';
?>

<div class="container">

    <div class="col-lg-6 col-lg-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>Log in</h2>
            </div>
            <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" />
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