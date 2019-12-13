<?php session_start();
require_once __DIR__ . "/../../config.php";
if(isset($_SESSION['user'])){
    header("Location: " . SITE_ROOT);
}
require_once __DIR__ . '/../../lib/User.php';

if($_POST){
    // Validate input
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';
    $pass2 = isset($_POST['password2']) ? $_POST['password2'] : '';
    $valid = true;
    if($valid){
        $user = new User();
        $user->email = $email;
        $user->password = $pass;
        if($user->create()){
            $_SESSION['createdAccount'] = true;
            header("Location: ".SITE_ROOT.'/auth');
        }

    }
}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <meta name="theme-color" content="#fafafa">
    <style>

    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <form class="form" method="POST">
                <h1 class="mb-4">Register</h1>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required placeholder="joe@gmail.com" />
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required placeholder="Password"  />
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="password2" required placeholder="Confirm Password"  />
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>