<?php 
    require('connect.php');

    session_start();

    require('search_function.php');

    if($_GET && is_numeric($_GET['animeID']))
    {
        $query = "SELECT b.genre, a.*
        FROM Anime AS a
        JOIN Genre AS b ON b.genreID = a.genre_fk
        WHERE a.animeID = :animeID 
        LIMIT 1";

        $statement = $db->prepare($query);

        $id = filter_input(INPUT_GET, 'animeID', FILTER_SANITIZE_NUMBER_INT);

        $statement->bindValue('animeID', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();
    } else {
        header("Location:index.php");
        exit();
    }
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
            margin-left: 200px;
            margin-right: 200px;
            margin-top: 20px;
        }

        .card-img-top {
            width: 70%;
        }

    </style>
    <title>AniLogger</title>
</head>
<body class="body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a href="index.php" class="navbar-brand">AniLogger</a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="anime_list.php">Anime</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="genre_list.php">Genres</a>
            </li>
            <?php if(isset($_SESSION["loggedin"]) && $_SESSION['user_type'] == 1) : ?>
                <li class="nav-item">
                    <a href="admin.php" class="nav-link">Admin-View</a>
                </li>
            <?php endif ?>
        </ul>

        <ul class="navbar-nav mr-auto">
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) : ?>
            <li class="nav-item">
                <span class="navbar-text">Welcome, <?= $_SESSION['username'] ?></span>
            </li>
        <?php else : ?>
            <li class="nav-item">
                <a href="register_page.php" class="nav-link">Register</a>
            </li>
            <li class="nav-item">
                <a href="login.php" class="nav-link">Login</a>
            </li>
        <?php endif ?>
            <li class="nav-item pl-4">
                <a href="logout.php" class="nav-link">Logout</a>
            </li>
        </ul>
        <form action="index.php" class="form-inline my-2 my-lg-0" method="post">
            <input type="text" class="form-control mr-sm-2" name="keyword" placeholder="Search anime..." aria-label="Search">
            <select name="genre" id="genre" class="form-control mr-sm-2">
                <option value="All Genres">All Genres</option>
                <?php while($genreRow = $genreStatement->fetch()) : ?>
                    <option value="<?= $genreRow['genreID'] ?>"><?= $genreRow['genre'] ?></option>
                <?php endwhile ?>
            </select>
            <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="search" value="Search"/>
        </form>
    </nav>
    <div class="card mb-3" >
        <img src="<?= (isset($row['image'])) ? 'uploads/' . $row['image'] : 'uploads/no_image.png' ?>"  class="card-img-top rounded mx-auto d-block">
        <div class="card-body">
            <h2 class="card-title font-weight-bold display-4"><?= $row['title'] ?></h2>
            <ul class="list-group">
                <li class="list-group-item"><span class="font-weight-bold">Genre:</span> <?= $row['genre'] ?></li>
                <li class="list-group-item"><span class="font-weight-bold">Number of Episodes:</span> <?= $row['episodeCount'] ?></li>
                <li class="list-group-item"><span class="font-weight-bold">Studio:</span> <?= $row['studio'] ?></li>
                <li class="list-group-item">
                    <a href="edit_anime.php?animeID=<?= $row['animeID'] ?>&username=<?= $row['username'] ?>" class="btn btn-dark">Edit</a>
                    <a href="delete_anime.php?animeID=<?= $row['animeID'] ?>&username=<?= $row['username'] ?>" class="btn btn-danger">Delete</a>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <small class="text-muted">Date added: <?= $row['timestamp'] ?></small> 
        </div>
    </div>
</body>
</html>