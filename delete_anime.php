<?php 
    require('connect.php');

    session_start();

    if(!isset($_SESSION['loggedin']))
    {
        echo "<script>alert('You must be logged in to do this!'); 
        window.location.href='index.php';</script>";
    } else if (($_GET['username'] != $_SESSION['username'])) {
        echo "<script>alert('You can only delete your own posts!'); 
        window.location.href='index.php';</script>";
    } else {
        if($_GET['animeID'] && is_numeric($_GET['animeID']))
        { 
            $id = filter_input(INPUT_GET, 'animeID', FILTER_SANITIZE_NUMBER_INT);
    
            $query = "DELETE FROM anime WHERE animeID = :id";
            $statement = $db->prepare($query);
    
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
            if($statement->execute())
            {
                header("Location:index.php");
                exit();
            }
        } else {
            header("Location: index.php");
        }
    }
?>
