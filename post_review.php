<?php 
    require('connect.php');
    session_start();
    require('search_function.php');
    require('vendor/autoload.php');

    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache.DefinitionImpl', null);
    $config->set('HTML.AllowedElements', 'strong, em, span');
    $config->set('HTML.AllowedAttributes', 'style');

    $purifier = new HTMLPurifier($config);
    
    if(!isset($_SESSION['loggedin']))
    {
        echo "<script>alert('You must be logged in to do this!'); 
        window.location.href='index.php';</script>";
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if($_POST && !empty($_POST['review']))
        {
            $review = $purifier->purify($_POST['review']);
            $id = filter_input(INPUT_GET, 'animeID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $rating = $_POST['rating'];
            $query = "INSERT INTO review (review, satisfactoryRating, animeID, username) VALUES (:review, :rating, :animeID, :username)";

            $statement = $db->prepare($query);

            $statement->bindValue(':review', $review);
            $statement->bindValue(':rating', $rating);
            $statement->bindValue(':animeID', $id);
            $statement->bindValue(':username', $_SESSION['username']);

            if($statement->execute())
            {
                header("Location: review_list.php?animeID=" . $id);
                exit();
            }
        }
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
    </style>
    <script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
    tinymce.init({
        selector: 'textarea',
        menubar: false,
        plugins: 'code',
        toolbar: 'bold italic underline code'
    });
    </script>
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
    <div class="py-3">
        <div class="container">
            <p class="display-4">Add Review</p>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) . '?animeID=' . $_GET['animeID'] ?>" method="post">
                <div class="form-group">
                    <textarea name="review" id="review" rows="10" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="rating" class="font-weight-bold">Satisfactory Rating</label>
                    <select name="rating" id="rating" class="form-control">
                        <option value="10">10</option>
                        <option value="9">9</option>
                        <option value="8">8</option>
                        <option value="7">7</option>
                        <option value="6">6</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                        <option value="0">0</option>
                    </select>
                </div>
                <button class="btn btn-dark" name="command" value="Add Review">Submit Review</button>
            </form>
        </div>
    </div>
</body>
</html>
