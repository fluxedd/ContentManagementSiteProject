<?php 
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
    {
        header("Location: index.php");
        exit;
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
                        <input type="text" class="form-control <?= (!empty($bad_username)) ? 'invalid' : ''; ?>" id="username" value="<?= $good_username ?>" name="username">
                        <label for="username" class="form-label">Username</label>
                        <span class="text-danger"><?= $bad_username ?></span>
                    </div>
                    <div class="form-outline mb-3">
                        <input type="password" class="form-control <?= (!empty($bad_password)) ? 'invalid' : ''; ?>" id="password" value="<?= $good_password ?>" name="password">
                        <label for="password" class="form-label">Password</label>
                        <span class="text-danger"><?= $bad_password ?></span>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg text-body">Register</button>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>