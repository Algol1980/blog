<?php
session_start();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'header.php';
?>

<div class="container">

    <div class="jumbotron">
        <h1>Introduce cool blog system!</h1>
        <p>It's very cool and fun, try it!</p>
        <p><a class="btn btn-primary" href="signup.php">Start using!</a> or <a href="login.php" class="btn btn-primary">Log in</a></p>
    </div>

    <h2>List of bloggers</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Количество записей</th>
                <th>Количество фото</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <a href="blog.php">Андрей Петров</a>
                </td>
                <td>
                    2
                </td>
                <td>
                    1
                </td>
            </tr>
            <tr>
                <td>
                    <a href="blog.php">Петр Андреев</a>
                </td>
                <td>
                    2
                </td>
                <td>
                    1
                </td>
            </tr>
            <tr>
                <td>
                    <a href="blog.php">Михаил Горький</a>
                </td>
                <td>
                    2
                </td>
                <td>
                    1
                </td>
            </tr>
        </tbody>
    </table>

</div>
<?php 
require 'footer.php';
?>