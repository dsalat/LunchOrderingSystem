<?php session_start();
require_once __DIR__ . "/../../config.php";
if(isset($_SESSION['user'])){
    header("Location: " . SITE_ROOT);
}
require_once __DIR__ . '/../../lib/User.php';

if($_POST){
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';
    $errors = [];
    // Validate
    $user = User::findByEmail($email);
    if($user->unhashPass($pass)){
        $_SESSION['user'] = ['id'=>$user->id, 'role'=>$user->role];
        header("Location: " . SITE_ROOT);
    }else{
        $errors[] = 'Username password mismatch.';
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
                <?php if(isset($_SESSION['createdAccount'])){ unset($_SESSION['createdAccount']); ?>
                    <div class="alert alert-success">Created new account, you can now login.</div>
                <?php } ?>
                <?php if(isset($_SESSION['madeAdmin'])){ unset($_SESSION['madeAdmin']); ?>
                    <div class="alert alert-success">You are now admin.</div>
                <?php } ?>
                <form class="form" method="POST">
                    <?php if(isset($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php foreach($errors as $err): ?>
                            <div><?php echo $err; ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <h1 class="mb-4">Login</h1>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required placeholder="joe@gmail.com" />
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required placeholder="Password" />
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Login</button>
                    </div>
                    <div>
                        <a href="<?php echo SITE_ROOT . '/auth/register.php'; ?>">Not a user? Register now.</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>