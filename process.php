<?php 
    require('connect.php');

    use Gumlet\ImageResize;

    require ('\xampp\htdocs\a\php-image-resize-master\lib\ImageResize.php');
    require ('\xampp\htdocs\a\php-image-resize-master\lib\ImageResizeException.php');
    
    session_start();

    if($_POST['command'] == 'Add Anime')
    {   
        if($_POST && !empty($_POST['title']) && !empty($_POST['genre_fk']) && !empty($_POST['episodeCount']) && !empty($_POST['studio']))
        {
            function file_upload_path($original_filename, $upload_subfloder_name = 'uploads')
            {
                $current_folder = dirname(__FILE__);
                $path_segments = [$current_folder, $upload_subfloder_name, basename($original_filename)];
                return join(DIRECTORY_SEPARATOR, $path_segments);
            }

            function check_mime_type($temp_path, $new_path)
            {
                $allowed_mime_types = ['image/gif', 'image/jpeg', 'image/png'];
                $allowed_file_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                
                $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
                $actual_mime_type = mime_content_type($temp_path);

                $file_extension_valid = in_array($actual_file_extension, $allowed_file_extensions);
                $mime_type_valid = in_array($actual_mime_type, $allowed_mime_types);

                return $file_extension_valid && $mime_type_valid;
            }

            $file_upload_detected = isset($_FILES['uploadImage']) && ($_FILES['uploadImage']['error'] === 0);
            $upload_error_detected = isset($_FILES['uploadImage']) && ($_FILES['uploadImage']['error'] > 0);

            if($file_upload_detected) 
            {
                $file_filename = microtime() . $_FILES['uploadImage']['name'];
                $temporary_file_path = $_FILES['uploadImage']['tmp_name'];
                $new_file_path = file_upload_path($file_filename);

                if(mime_content_type($temporary_file_path) == 'image/jpeg' || mime_content_type($temporary_file_path) == 'image/png')
                {
                    $resizedImage = new ImageResize($temporary_file_path);
                    $resizedImage->resize(700, 500, $allow_enlarge = true);
                    $resized_new_file_path = file_upload_path($resizedImage->save('uploads\resized_' . $file_filename));
                }

                if(check_mime_type($temporary_file_path, $new_file_path))
                {
                    move_uploaded_file($temporary_file_path, $new_file_path);    
                } else {
                    $file_filename = null;
                }
            }

            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_input(INPUT_POST, 'genre_fk', FILTER_SANITIZE_SPECIAL_CHARS);
            $episodeCount = filter_input(INPUT_POST, 'episodeCount', FILTER_VALIDATE_INT);
            $studio = filter_input(INPUT_POST, 'studio', FILTER_SANITIZE_SPECIAL_CHARS);

            $query = " INSERT INTO anime (title, genre_fk, episodeCount, studio, image, username) VALUES (:title, :genre, :episodeCount, :studio, :image, :username) ";

            $statement = $db->prepare($query);  

            $statement->bindValue(":title", $title);
            $statement->bindValue(":genre", $genre);
            $statement->bindValue(":episodeCount", $episodeCount);
            $statement->bindValue(":studio", $studio);
            $statement->bindValue(":image", $file_filename);
            $statement->bindValue(":username", $_SESSION['username']);

            if($statement->execute())
            {
                header("Location:index.php");
                exit();
            }
        } else {
            $error = "There was something wrong with your submission form.";
        }

        unset($statement);
    }

    if($_POST['command'] == 'Add Genre')
    {
        if($_POST && !empty($_POST['genre']))
        {
            $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS);

            $query = " INSERT INTO genre (genre) VALUES (:genre) ";

            $statement = $db->prepare($query);  

            $statement->bindValue(":genre", $genre);

            if($statement->execute())
            {
                header("Location:genre_list.php");
                exit();
            }
        } else {
            $error = "There was something wrong with your submission form.";
        }

        unset($statement);
    }

    if($_POST['command'] == 'Edit Anime')
    {
        if($_POST && !empty($_POST['title']) && !empty($_POST['genre_fk']) && !empty($_POST['episodeCount']) && !empty($_POST['studio']) && isset($_POST['animeID']))
        {
            // function file_upload_path($original_filename, $upload_subfloder_name = 'uploads')
            // {
            //     $current_folder = dirname(__FILE__);
            //     $path_segments = [$current_folder, $upload_subfloder_name, basename($original_filename)];
            //     return join(DIRECTORY_SEPARATOR, $path_segments);
            // }

            // function check_mime_type($temp_path, $new_path)
            // {
            //     $allowed_mime_types = ['image/gif', 'image/jpeg', 'image/png'];
            //     $allowed_file_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                
            //     $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
            //     $actual_mime_type = mime_content_type($temp_path);

            //     $file_extension_valid = in_array($actual_file_extension, $allowed_file_extensions);
            //     $mime_type_valid = in_array($actual_mime_type, $allowed_mime_types);

            //     return $file_extension_valid && $mime_type_valid;
            // }

            // $file_upload_detected = isset($_FILES['uploadImage']) && ($_FILES['uploadImage']['error'] === 0);
            // $upload_error_detected = isset($_FILES['uploadImage']) && ($_FILES['uploadImage']['error'] > 0);

            // if($file_upload_detected) 
            // {
            //     $file_filename = $_FILES['uploadImage']['name'];
            //     $temporary_file_path = $_FILES['uploadImage']['tmp_name'];
            //     $new_file_path = file_upload_path($file_filename);

            //     if(mime_content_type($temporary_file_path) == 'image/jpeg' || mime_content_type($temporary_file_path) == 'image/png')
            //     {
            //         $resizedImage = new ImageResize($temporary_file_path);
            //         $resizedImage->resize(700, 500, $allow_enlarge = true);
            //         $resized_new_file_path = file_upload_path($resizedImage->save('uploads\resized_' . $file_filename));
            //     }

            //     if(check_mime_type($temporary_file_path, $new_file_path))
            //     {
            //         move_uploaded_file($temporary_file_path, $new_file_path);    
            //     } else {
            //         $file_filename = null;
            //     }
            // }

            $id = filter_input(INPUT_POST, 'animeID', FILTER_SANITIZE_NUMBER_INT);
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_input(INPUT_POST, 'genre_fk', FILTER_SANITIZE_SPECIAL_CHARS);
            $episodeCount = filter_input(INPUT_POST, 'episodeCount', FILTER_VALIDATE_INT);
            $studio = filter_input(INPUT_POST, 'studio', FILTER_SANITIZE_SPECIAL_CHARS);

            $query = "UPDATE anime 
            SET title = :title, 
            genre_fk = :genre, 
            episodeCount = :episodeCount, 
            studio = :studio
            WHERE animeID = :animeID";
            
            $statement = $db->prepare($query);

            $statement->bindValue(":title", $title);
            $statement->bindValue(":genre", $genre);
            $statement->bindValue(":episodeCount", $episodeCount);
            $statement->bindValue(":studio", $studio);
            // $statement->bindValue(":image", $file_filename);
            $statement->bindValue(":animeID", $id);

            if($statement->execute())
            {
                header("Location:view_anime.php?animeID=" . $id);
                exit();
            }
        } else {
            $error = "There was something wrong with your submission form.";
        }

        unset($statement);
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
            margin-left: 300px;
            margin-right: 300px;
            margin-top: 20px;
        }

        .card-img-top {
            width: 350px;
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
    <div class="alert alert-warning mt-3" role="alert"> <?= $error ?> </div>
</body>