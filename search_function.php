<?php 
    $genreQuery = "SELECT * FROM genre ORDER BY genre ASC";
    $genreStatement = $db->prepare($genreQuery);
    $genreStatement->execute();

    if(isset($_POST['search']))
    {
        if(!isset($_POST['keyword']) && $_POST['genre'] == 'All Genres')
        {
            $queryy = "SELECT * FROM anime ORDER BY timestamp DESC";
        } elseif(isset($_POST['keyword']) && $_POST['genre'] == 'All Genres')
        {
            $keyword = filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_SPECIAL_CHARS);
            $queryy = "SELECT * FROM anime WHERE title LIKE '%$keyword%' ORDER BY timestamp DESC";
        } else {
            $selectedGenre = $_POST['genre'];
            $queryy = "SELECT * FROM anime WHERE genre_fk = '$selectedGenre' ORDER BY timestamp DESC";
        }
           

    } else {
        $queryy = "SELECT * FROM anime ORDER BY timestamp DESC";
    }
?>