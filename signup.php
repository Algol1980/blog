<?php 
session_start();

require 'functions.php';

$required = ['email', 'firstName', 'lastName', 'password', 'confirmPassword'];

if (!empty($_POST)) {
    foreach ($required as $value) {
        if (!isset($value, $_POST) || empty($_POST[$value])) {
            $errorArray[] = 'Incorrect ' . $value;
        }
    }
if ($_POST['password'] != $_POST['confirmPassword']) {
    $errorArray[] = 'Incorrect Password';
}
    if (isset($errorArray)) {
        $errorMessage = listErrors($errorArray);
    }
    else {
        if(!isUserExist($_POST['email'])) {
            $user = (addUser($_POST['email'], $_POST['firstName'], $_POST['lastName'], $_POST['password']));
             if ($user) {
                $_SESSION['user'] = true;
                $_SESSION['userId'] = $user['userId'];
                $_SESSION['firstName'] = $user['firstName'];
                $_SESSION['lastName'] = $user['lastName'];
                header("Location: index.php");
            }
        }
    }
}
require 'header.php';
?>
<div class="container">

    <div class="col-lg-6 col-lg-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>Sign up</h2>
            </div>
            <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="firstName" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lastName" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirmPassword" class="form-control" />
                    </div>
                    <div class="form-group">
                        <input type="submit" name="signUp" class="btn btn-primary form-control" />
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<?php 
require 'footer.php';
?>