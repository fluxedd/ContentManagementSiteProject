<?php 
    require('connect.php');
    
    session_start();

    require('search_function.php');

    if(!isset($_SESSION['loggedin']))
    {
        echo "<script>alert('You must be logged in to do this!'); 
        window.location.href='index.php';</script>";
    } else if (($_GET['username'] != $_SESSION['username']) && $_SESSION['user_type'] != 1) {
        echo "<script>alert('You can only edit your own posts!'); 
        window.location.href='index.php';</script>"; 
    } else {
        $query = "SELECT * FROM genre ORDER BY genre ASC";

        $statement = $db->prepare($query);
    
        $statement->execute();
    
        if(isset($_GET['animeID']) && $_GET['animeID'] && is_numeric($_GET['animeID']) && ($_GET['username'] == $_SESSION['username'] || $_SESSION['user_type'] == 1))
        { 
            $id = filter_input(INPUT_GET, 'animeID', FILTER_SANITIZE_NUMBER_INT);
    
            $animeQuery = "SELECT * FROM anime WHERE animeID = :animeID LIMIT 1";
            $animeStmt = $db->prepare($animeQuery);
    
            $animeStmt->bindValue('animeID', $id, PDO::PARAM_INT);
            $animeStmt->execute();
    
            $fetch = $animeStmt->fetch();
        } else {
            $fetch = false;
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
    <?php require('navbar.php') ?>
    <div class="py-3">
        <div class="container">
            <p class="display-4">Edit Anime Entry</p>
            <form action="process.php" method="post" enctype='multipart/form-data'>
            <?php if($fetch) : ?> 
                <div class="form-group">
                    <label for="title" class="font-weight-bold">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $fetch['title'] ?>">
                </div>
                <div class="form-group">
                    <label for="genre" class="font-weight-bold">Genre</label>
                    <select name="genre_fk" id="genreList" class="form-control">
                        <option value="" disabled selected>Select a genre</option>
                        <?php while($row = $statement->fetch()) : ?>
                        <option value="<?=$row['genreID'] ?>"><?=$row['genre'] ?></option>
                        <?php endwhile ?>
                    </select>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-6 mb-6">
                        <label for="episodeCount" class="font-weight-bold">Number of Episodes</label>
                        <input type="number" class="form-control" id="episodeCount" name="episodeCount" value="<?= $fetch['episodeCount'] ?>">
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="studio" class="font-weight-bold">Studio</label>
                        <input type="text" class="form-control" id="studio" name="studio" value="<?= $fetch['studio'] ?>">
                    </div>
                </div>
                <input type="hidden" name="animeID" value="<?= $fetch['animeID'] ?>" />
                <button class="btn btn-dark" name="command" value="Edit Anime">Edit Anime</button>
            <?php endif ?>
            </form>
        </div>
    </div>
</body>
</html>
