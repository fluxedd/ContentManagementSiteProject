<?php 
    require('authenticate.php');
    require('connect.php');

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        .body {
            margin-left: 300px;
            margin-right: 300px;
            margin-top: 20px;
        }

        .card-img-top {
            width: 390px;
        }
    </style>
    <title>AniLogger</title>
</head>
<body class="body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a href="index.php" class="navbar-brand">AniLogger - Admin View</a> 
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Anime</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Genres</a>
            </li>
            <li class="nav-item">
                <a href="admin.php" class="nav-link">Admin</a>
            </li>
        </ul>
        <form action="" class="form-inline my-2 my-lg-0">
            <input type="search" class="form-control mr-sm-2" placeholder="Search anime..." aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </nav>
    <div class="py-3">
        <div class="container">
            <ul class="list-group">
            <?php while($row = $statement->fetch()) : ?>
                <li class="list-group-item" style="width: 350px;"><a href="#"><?= $row['title'] ?></a></li>
            <?php endwhile ?>  
            </ul>
            <a class="btn btn-dark my-3" href="create.php">Add Anime</a>
        </div>
    </div>
</body>
</html>
