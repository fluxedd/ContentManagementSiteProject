<?php 
    require_once('connect.php');

    $good_username = $good_password = $good_confirm_password = "";
    $bad_username = $bad_password = $bad_confirm_password = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if(empty(trim($_POST["username"])))
        {
            $bad_username = "Username cannot be blank.";
        } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"])))
        {
            $bad_username = "Username can only have alphanumeric characters and underscores.";
        } else {
            $query = "SELECT userID FROM users WHERE username = :username";

            if($statement = $db->prepare($query))
            {
                $statement->bindParam(":username", $param_username, PDO::PARAM_STR);
                $param_username = trim($_POST["username"]);

                $statement->execute();

                $good_username = trim($_POST["username"]);

                if($statement->execute())
                {
                    if($statement->rowCount() == 1)
                    {
                        $bad_username = "Username is taken.";
                    } else {
                        $good_username = trim($_POST["username"]);
                    }
                } else {
                    echo "Something went wrong.";
                }

                unset($statement);
            }
        }
        
        if(empty(trim($_POST["password"])))
        {
            $bad_password = "Please enter a password.";
        } elseif (strlen(trim($_POST["password"])) < 8) 
        {
            $bad_password = "Password must be at least 8 characters.";
        } else {
            $good_password = trim($_POST["password"]);
        }

        if(empty(trim($_POST["confirmPassword"]))) 
        {
            $bad_confirm_password = "Please confirm password.";
        } else {
            $good_confirm_password = trim($_POST["confirmPassword"]);
            if(empty($bad_password) && ($good_password != $good_confirm_password))
            {
                $bad_confirm_password = "Password must match";
            }
        }

        if(empty($bad_username) && empty($bad_password) && empty($bad_confirm_password))
        {
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

            if($statement = $db->prepare($sql))
            {
                $statement->bindParam(":username", $param_username, PDO::PARAM_STR);
                $statement->bindParam(":password", $param_password, PDO::PARAM_STR);

                $param_username = $good_username;
                $param_password = password_hash($good_password, PASSWORD_DEFAULT);

                if($statement->execute())
                {
                    header("Location: index.php");
                } else {
                    echo "Something went wrong.";
                }

                unset($statement);
            }
        }
        
        unset($db);
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
                    <button class="btn btn-primary btn-block btn-lg text-body">Register</button>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>