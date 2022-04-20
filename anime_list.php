<?php 
    require('connect.php');

    session_start();

    require('search_function.php');

    $query = "SELECT * FROM anime ORDER BY title DESC ";

    $statement = $db->prepare($query);

    $statement->execute();
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
            <p class="display-4">Anime</p>
            <ul class="list-group">
            <?php while($row = $statement->fetch()) : ?>
                <li class="list-group-item" style="width: 350px;"><?= $row['title'] ?></li>
            <?php endwhile ?>  
            </ul>
            <a class="btn btn-dark my-3" href="post_anime.php">Add Anime</a>
        </div>
    </div>
</body>
</html>
