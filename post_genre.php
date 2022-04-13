<?php 
    require('connect.php');
    
    session_start();

    require('warning.php');

    require('search_function.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('navbar.php') ?>
    <div class="py-3">
        <div class="container">
            <p class="display-4">New Genre Entry</p>
            <form action="process.php" method="post">
                <div class="form-group">
                    <label for="genre" class="font-weight-bold">Genre</label>
                    <input type="text" class="form-control" id="genre" name="genre" style="width: 200px;">  
                </div>
                <button class="btn btn-dark" name="command" value="Add Genre">Add Genre</button>
            </form>
        </div>
    </div>
</body>
</html>
