<?php 
    require('connect.php');

    session_start();

    require('search_function.php');

    if(is_numeric($_GET['animeID']))
    {
        $id = filter_input(INPUT_GET, 'animeID', FILTER_SANITIZE_NUMBER_INT);
        
        $reviewsQuery = "SELECT *, r.timestamp, r.username
        FROM review r
        JOIN anime a ON r.animeID = a.animeID
        WHERE r.animeID = :animeID
        ORDER BY r.timestamp
        DESC";

        $reviewsStmt = $db->prepare($reviewsQuery);
        $reviewsStmt->bindValue(':animeID', $id);
        $reviewsStmt->execute();
    } else {
        header('Location: index.php');
    }

    if($_GET && is_numeric($_GET['animeID']))
    {
        $query = "SELECT *
        FROM Anime
        WHERE animeID = :animeID 
        LIMIT 1";

        $statement = $db->prepare($query);

        $id = filter_input(INPUT_GET, 'animeID', FILTER_SANITIZE_NUMBER_INT);

        $statement->bindValue('animeID', $id, PDO::PARAM_INT);
        $statement->execute();

        $animeRow = $statement->fetch();
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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?php require('navbar.php') ?>
    <div class="py-3">
        <div class="container">
        <p class="display-4">Reviews for <?= $animeRow['title'] ?></p>
            <ul class="list-group">
            <?php while($row = $reviewsStmt->fetch()) : ?>
                <li class="list-group-item" style="width: 500px;">
                    <p><?= $row['review'] ?></p>
                    <p class="text-success font-weight-bold"><?= $row['satisfactoryRating'] . '/10' ?></p>
                    <p>
                        <small class="text-muted">Posted on: <?= $row['timestamp'] ?> by <span class="text-primary"><?= $row['username'] ?></span></small>     
                    </p>
                    <?php if(isset($_SESSION["loggedin"]) && $_SESSION['user_type'] == 1) : ?>
                    <button class="btn btn-danger mt-3" type="button" data-toggle="modal" data-target="#confirmDelete<?= $row['reviewID'] ?>">Delete</button>
                        <div class="modal" id="confirmDelete<?= $row['reviewID'] ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm delete?</h5>
                                    </div>
                                    <div class="modal-body">Are you sure you want to delete?</div>
                                    <div class="modal-footer">
                                        <button class="btn-btn-secondary" data-dismiss="modal" type="button">Close</button>
                                        <a href="delete_review.php?reviewID=<?= $row['reviewID'] ?>&animeID=<?= $row['animeID'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>     
                    <?php endif ?>
                </li>
            <?php endwhile ?>  
            </ul>
        </div>
    </div>
</body>
</html>
