<?php
    require('connect.php');

    session_start();

    if(!isset($_SESSION['loggedin']))
    {
        echo "<script>alert('You must be logged in to do this!'); 
        window.location.href='index.php';</script>";
    } else if (($_GET['username'] != $_SESSION['username']) && $_SESSION['user_type'] != 1) {
        echo "<script>alert('You can only delete your own posts!'); 
        window.location.href='index.php';</script>";
    } else {
        if($_GET['reviewID'] && is_numeric($_GET['reviewID']) && is_numeric($_GET['animeID']))
        { 
            $id = filter_input(INPUT_GET, 'reviewID', FILTER_SANITIZE_NUMBER_INT);
            $animeID = filter_input(INPUT_GET, 'animeID', FILTER_SANITIZE_NUMBER_INT);
    
            $query = "DELETE FROM review WHERE reviewID = :id";
            $statement = $db->prepare($query);
    
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
            if($statement->execute())
            {
                header("Location:review_list.php?animeID=" . $animeID);
                exit();
            }
        } else {
            header("Location: index.php");
        }
    }
?>