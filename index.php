<?php
session_start();
require 'functions.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'header.php';
?>

<div class="container">
    <?php if(!isset($_SESSION['user'])): ?>
    <div class="jumbotron">
        <h1>Introduce cool blog system!</h1>
        <p>It's very cool and fun, try it!</p>
        <p><a class="btn btn-primary" href="signup.php">Start using!</a> or <a href="login.php" class="btn btn-primary">Log in</a></p>
    </div>
    <?php endif ?>


    <h2>List of bloggers</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Количество записей</th>
                <th>Количество фото</th>
            </tr>
        </thead>
        <?php $bloggers = getBloggers(); ?>
        <?php foreach($bloggers as $blogger): ?>
        <tbody>
            <tr>
                <td>
                    <a href="blog.php?userId=<?php echo $blogger['userId']?>"><?php echo $blogger['name']?></a>
                </td>
                <td>
                    <?php echo $blogger['images']?>
                </td>
                <td>
                    <?php echo $blogger['posts']?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
<?php 
require 'footer.php';
?>