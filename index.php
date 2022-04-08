<?php 
    require('connect.php');

    session_start();

    $query = "SELECT * FROM anime ORDER BY timestamp DESC";

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
                <span class="navbar-text">Welcome, <?= $_SESSION['username'] ?></a>
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
        <form action="" class="form-inline my-2 my-lg-0">
            <input type="search" class="form-control mr-sm-2" placeholder="Search anime..." aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </nav>
    <div class="py-3">
        <div class="container">
            <div class="row">
            <?php while($row = $statement->fetch()) : ?>
                <div class="col-md-4">
                    <div class="card mb-3" >
                        <img src="<?= (isset($row['image'])) ? 'uploads/resized_' . $row['image'] : 'uploads/no_image.png' ?>"  class="card-img-top">
                        <div class="card-body">
                            <h2 class="card-title"><a href="view_anime.php?animeID=<?= $row['animeID'] ?>"><?=$row['title'] ?></a></h2>
                        </div>
                        <p class="card-text pl-3">
                            <small class="text-muted">Last updated: 
                                <?php $input = $row['timestamp'] ?>
                                <?php $date = strtotime($input) ?>
                                <?= date('F d, Y @ g:i:s a', $date) ?>
                            </small>
                        </p>
                        <p class="card-text pl-3"><small class="text-muted">Added by: <?= $row['username'] ?></small></p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><a href="review_list.php?animeID=<?= $row['animeID'] ?>">Reviews List</a></li>
                            <li class="list-group-item"><a href="post_review.php?animeID=<?= $row['animeID'] ?>">Leave a Review</a></li>
                        </ul>      
                    </div>
                </div>
            <?php endwhile ?>  
            </div>
        </div>
    </div>
</body>
</html>