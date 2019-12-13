<?php session_start();
require_once __DIR__ . '/guards/user.php';
require_once __DIR__ . '/../lib/Order.php';
require_once __DIR__ . '/../lib/Restaurant.php';
require_once __DIR__ . '/../lib/Ticket.php';

$id = isset($_GET['id']) ?  $_GET['id'] : '' ;
$user_id = $_SESSION['user']['id'];

if(Order::orderedToday($user_id)){
    header("Location: ".SITE_ROOT);
}

$ticket = Ticket::getLastTicket($user_id);

if($_POST){
    $o = new Order();
    $o->user_id = $user_id;
    $o->meal_id = $_POST['meal'];
    $o->drink = $_POST['drink'];
    $o->dessert = $_POST['dessert'];
    $o->department = $_POST['department'];
    if($ticket){
        $o->ticket_id = $ticket->id;
        $ticket->avail();
    }
    if($o->create()){
        $_SESSION['orderPlaced'] = true;
    }else{
        $_SESSION['orderFailed.'] = true;
    }
    header("Location: ".SITE_ROOT.'/orders');
}
if(is_numeric($id)){
    $restaurant = Restaurant::find($_GET['id']);
    $menu = $restaurant->getMenu();
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
</head>

<body>
<!--[if IE]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h1>Checkout
            <?php if($_SESSION['user']['role'] === '1'): ?>
                <a class="btn btn-sm btn-light" href="<?= SITE_ROOT; ?>/meal">Add to Menu</a>
            <?php endif; ?>
            </h1>
        </div>
    </div>
    <div class="row">
        <?php if(isset($restaurant)): ?>
        <div class="col-12 col-md-6">
            <?php if($ticket): ?>
                <h6 class="alert alert-info">This order will be used using your coupon.</h6>
            <?php endif; ?>
            <form method="POST" action="<?= SITE_ROOT ?>/checkout.php?id=<?= $_GET['id'] ?>">
                <div class="list-group">
                    <?php foreach ($menu as $meal): ?>
                    <label class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <input class="mr-1" type="radio" name="meal" value="<?= $meal['id'] ?>" required />
                            <?php
                                $m = "{$meal['course1']}, {$meal['course2']}, {$meal['course3']}";
                                if($meal['dessert']|| $meal['drink']){
                                    if($meal['dessert']){
                                        $m .= " with {$meal['dessert']}";
                                    }
                                    if($meal['drink']){
                                        if($meal['dessert']){
                                            $m .= " and";
                                        }else{
                                            $m .= " with";
                                        }
                                        $m .= " {$meal['drink']}";
                                    }
                                }
                                echo $m;
                            ?>
                        </div>
                        <span class="badge badge-primary">$<?= $meal['price']; ?></span>
                    </label class="list-group-item list-group-item">
                    <?php endforeach; ?>
                </div>
                <div class="form-group">
                    <label class="mt-2">Department</label>
                    <select class="form-control" name="department">
                        <option value="Math">Math</option>
                        <option value="Science Lab">Science Lab</option>
                        <option value="Staff">Staff</option>
						<option value="English">English</option>
						<option value="Cafe">Cafe</option>
						<option value="Lounge">Lounge</option>
                    </select>
                </div>
                <label class="mt-2"><b>Optional</b></label>
                <div class="row">
                    <div class="form-group col-12 col-md-6">
                        <label class="w-100">
                            Would you like a drink with this?
                            <input class="form-control" type="text" name="drink" placeholder="Leave blank if not" />
                        </label>
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label class="w-100">
                            Would you like a desserts with this?
                            <input class="form-control" type="text" name="dessert" placeholder="Leave blank if not" />
                        </label>
                    </div>
                </div>
                <button class="btn btn-primary">Confirm Order</button>
            </form>
        </div>
        <?php else: ?>
        <div class="col">
            <div class="jumbotron text-center">
                No Restaurant found.<br>
                <a href="<?= SITE_ROOT ?>" class="btn btn-sm btn-secondary mt-4">Go home</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="js/vendor/modernizr-3.8.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-3.4.1.min.js"><\/script>')</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>

<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
<script>
    window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
    ga('create', 'UA-XXXXX-Y', 'auto'); ga('set','transport','beacon'); ga('send', 'pageview')
</script>
<script src="https://www.google-analytics.com/analytics.js" async></script>
</body>

</html>
