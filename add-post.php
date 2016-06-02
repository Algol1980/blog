<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'header.php';
?>
<div class="container">

    <div class="col-lg-8 col-lg-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>Add Post</h2>
            </div>
            <div class="panel-body">
                <form>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea class="form-control" name="content" rows="5"></textarea>
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