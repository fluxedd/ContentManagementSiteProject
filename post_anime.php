<?php 
    require('connect.php');
    
    session_start();

    require('search_function.php');

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
    <?php require('navbar.php') ?>
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
