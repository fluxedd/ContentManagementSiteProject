<?php 
    require('connect.php');
    session_start();
    require __DIR__ . '/vendor/autoload.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if($_POST && $_POST['command'] == 'Promote User')
        {
            if($_POST && !empty($_POST['usersRadio']) && isset($_POST['usersRadio']))
            {
                $user = filter_input(INPUT_POST, 'usersRadio', FILTER_SANITIZE_SPECIAL_CHARS);
                $query = "UPDATE users SET user_type = 1 WHERE username = :username";

                $statement = $db->prepare($query);
                $statement->bindValue(':username', $user);

                if($statement->execute())
                {
                    header('Location: admin.php');
                }
            } else {
                header('Location: index.php');
            }  
        } 

        if($_POST && $_POST['command'] == 'Delete User')
        {
            if($_POST && !empty($_POST['usersRadio']) && isset($_POST['usersRadio']))
            {
                $user = filter_input(INPUT_POST, 'usersRadio', FILTER_SANITIZE_SPECIAL_CHARS);
                $query = "DELETE FROM users WHERE username = :username";

                $statement = $db->prepare($query);
                $statement->bindValue(':username', $user);

                if($statement->execute())
                {
                    header('Location: admin.php');
                }
            }
        }
    }
?>