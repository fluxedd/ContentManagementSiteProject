<?php 
    require('connect.php');

    session_start();

    require('search_function.php');

    $statement = $db->prepare($queryy);

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
                        <p class="card-text pl-3"><small class="text-muted">Added by: <span class="text-primary"><?= $row['username'] ?></span></small></p>
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