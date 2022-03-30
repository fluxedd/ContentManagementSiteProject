<?php 
    require('connect.php');

    $bad_username = '';
    $bad_password = '';

    $bad_login = '';

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
    {
        header('Location: index.php');
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if(empty($_POST['username']))
        {
            $bad_username = 'Enter a username.';
        }

        if(empty($_POST['password']))
        {
            $bad_password = 'Enter your password.';
        }

        if(!empty($_POST['username']) && !empty($_POST['password']))
        {
            $good_username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $good_password = $_POST['password'];

            $query = "SELECT username, password, user_type FROM users WHERE username = :username LIMIT 1";
            $statement = $db->prepare($query);

            $statement->bindvalue(':username', $good_username, PDO::PARAM_STR);

            $statement->execute();
            $count = $statement->rowCount();
            $row = $statement->fetch();
        
            if($count == 1 && !empty($row))
            {
                if(password_verify($good_password, $row['password']))
                {
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['user_type'] = $row['user_type'];
                    header('Location: index.php');
                } else {
                    $bad_login = 'Invalid username or password.';
                }

            } else 
            {
                $bad_login = 'Invalid username or password.';
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
    <div class="container w-50">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a href="index.php" class="navbar-brand">AniLogger</a>
        </nav>
        <div class="card">
            <div class="card-body p-5">
                <h2 class="text-uppercase text-center mb-5">Login</h2>
                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <div class="form-outline mb-3">
                        <input type="text" class="form-control" id="username" name="username">
                        <label for="username" class="form-label">Username</label>
                        <span class="text-danger"><?= $bad_username ?></span>
                    </div>
                    <div class="form-outline mb-3">
                        <input type="password" class="form-control" id="password" name="password">
                        <label for="password" class="form-label">Password</label>
                        <span class="text-danger"><?= $bad_password ?></span>
                    </div>
                    <span class="text-danger"><?= $bad_login ?></span>
                    <input type="submit" class="btn btn-primary btn-block btn-lg text-body" value="Login">
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>