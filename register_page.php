<?php 
    require_once('connect.php');

    $good_email = '';
    $good_username = '';
    $good_password = ''; 
    $good_confirm_password = '';

    $bad_email = '';
    $bad_username = '';
    $bad_password = '';
    $bad_confirm_password = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        // Email validation.
        if(empty($_POST['email']))
        {
            $bad_email = 'Email must be included.';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        {
            $bad_email = 'Must be a valid email.';
        } else {
            $query = 'SELECT email FROM users WHERE email = :email';

            if($statement = $db->prepare($query))
            {
                $statement->bindParam(':email', $param_email, PDO::PARAM_STR);
                $param_email = $_POST['email'];

                $statement->execute();

                $good_email = $_POST['email'];

                if($statement->execute())
                {
                    if($statement->rowCount() == 1)
                    {
                        $bad_email = 'Email is taken.';
                    } else {
                        $good_email = $_POST['email'];
                    }
                } 

                unset($statement);
            }
        }

        // Username validation
        if(empty($_POST['username']))
        {
            $bad_username = 'Username must be included.';
        } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username']))
        {
            $bad_username = 'Username can only have alphanumeric characters and underscores.';
        } else {
            $query = 'SELECT userID FROM users WHERE username = :username';

            if($statement = $db->prepare($query))
            {
                $statement->bindParam(':username', $param_username, PDO::PARAM_STR);
                $param_username = $_POST['username'];

                $statement->execute();

                $good_username = $_POST['username'];

                if($statement->execute())
                {
                    if($statement->rowCount() == 1)
                    {
                        $bad_username = 'Username is taken.';
                    } else {
                        $good_username = $_POST['username'];
                    }
                } 

                unset($statement);
            }
        }
        
        // Password validation
        if(empty($_POST['password']))
        {
            $bad_password = 'Please enter a password.';
        } elseif (strlen(($_POST['password'])) < 8) 
        {
            $bad_password = 'Password must be at least 8 characters.';
        } else {
            $good_password = $_POST['password'];
        }

        // Confirm Password validation.
        // Checks if it matches with Password.
        if(empty(($_POST['confirmPassword']))) 
        {
            $bad_confirm_password = 'Please confirm password.';
        } else {
            $good_confirm_password = ($_POST['confirmPassword']);
            if(empty($bad_password) && ($good_password != $good_confirm_password))
            {
                $bad_confirm_password = 'Password must match';
            }
        }

        // Once eveything passes through, store user info in database.
        if(empty($bad_username) && empty($bad_password) && empty($bad_confirm_password) && empty($bad_email))
        {
            $query = 'INSERT INTO users (email, username, password) VALUES (:email, :username, :password)';

            if($statement = $db->prepare($query))
            {
                $statement->bindParam(':email', $param_email, PDO::PARAM_STR);
                $statement->bindParam(':username', $param_username, PDO::PARAM_STR);
                $statement->bindParam(':password', $param_password, PDO::PARAM_STR);

                $param_email = $good_email;
                $param_username = $good_username;
                $param_password = password_hash($good_password, PASSWORD_DEFAULT);

                if($statement->execute())
                {
                    header('Location: login.php');
                } 
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
                <h2 class="text-uppercase text-center mb-5">Register an account</h2>
                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <div class="form-outline mb-3">
                        <input type="email" class="form-control <?= (!empty($bad_email)) ? 'invalid' : ''; ?>" id="email" value="<?= $good_email ?>" name="email">
                        <label for="email" class="form-label">Email</label>
                        <span class="text-danger"><?= $bad_email ?></span>
                    </div>
                    <div class="form-outline mb-3">
                        <input type="text" class="form-control <?= (!empty($bad_username)) ? 'invalid' : ''; ?>" id="username" value="<?= $good_username ?>" name="username">
                        <label for="username" class="form-label">Username</label>
                        <span class="text-danger"><?= $bad_username ?></span>
                    </div>
                    <div class="form-outline mb-3">
                        <input type="password" class="form-control <?= (!empty($bad_password)) ? 'invalid' : ''; ?>" id="password" value="<?= $good_password ?>" name="password">
                        <label for="password" class="form-label">Password</label>
                        <span class="text-danger"><?= $bad_password ?></span>
                    </div>
                    <div class="form-outline mb-3">
                        <input type="password" class="form-control <?= (!empty($bad_confirm_password)) ? 'invalid' : ''; ?>" id="confirmPassword" value="<?= $good_confirm_password ?>" name="confirmPassword">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <span class="text-danger"><?= $bad_confirm_password ?></span>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg text-body" name="command" value="Register Account">Register</button>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
