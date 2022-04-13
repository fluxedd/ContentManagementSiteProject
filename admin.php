<?php 
    require('connect.php');

    session_start();

    require('warning.php');

    require('search_function.php');

    $query = "SELECT username FROM users";

    $statement = $db->prepare($query);

    $statement->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <?php require('navbar.php') ?>
    <div class="py-3">
        <div class="container">
        <p class="display-4">Users</p>
            <ul class="list-group">
            <?php while($row = $statement->fetch()) : ?>
                <li class="list-group-item" style="width: 350px;"><?= $row['username'] ?></li>
            <?php endwhile ?>  
            </ul>
        </div>
    </div>
</body>
</html>
