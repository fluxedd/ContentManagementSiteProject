<?php 
    require('connect.php');
    
    session_start();

    if(!isset($_SESSION['loggedin']))
    {
        echo "<script>alert('You must be logged in to do this!'); 
        window.location.href='index.php';</script>";
    }

    $query = "SELECT * FROM genre ORDER BY genre ASC";

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

    label.title {
            font-style: bold;
        }

    </style>
    <title>AniLogger</title>
</head>
<body class="body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a href="index.php" class="navbar-brand">AniLogger - Add</a> 
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
            <p class="display-4">New Anime Entry</p>
            <form action="process.php" method="post" enctype='multipart/form-data'>
                <div class="form-group">
                    <label for="title" class="font-weight-bold">Title</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="form-group">
                    <label for="genre" class="font-weight-bold">Genre</label>
                    <select name="genre_fk" id="genre" class="form-control">
                        <option value="" disabled selected>Select a genre</option>
                        <?php while($row = $statement->fetch()) : ?>
                        <option value="<?=$row['genreID'] ?>"><?=$row['genre'] ?></option>
                        <?php endwhile ?>
                    </select>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-6 mb-6">
                        <label for="episodeCount" class="font-weight-bold">Number of Episodes</label>
                        <input type="number" class="form-control" id="episodeCount" name="episodeCount">
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="studio" class="font-weight-bold">Studio</label>
                        <input type="text" class="form-control" id="studio" name="studio">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uploadImage" class="font-weight-bold">Upload Image</label>
                    <input type="file" class="form-control-file" id="uploadImage" name="uploadImage">
                </div>
                <button class="btn btn-dark" name="command" value="Add Anime">Add Anime</button>
            </form>
        </div>
    </div>
</body>
</html>
