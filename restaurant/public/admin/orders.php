<?php session_start();
require_once __DIR__ . '/../guards/admin.php';
require_once __DIR__ . '/../../lib/Restaurant.php';
require_once __DIR__ . '/../../lib/Order.php';

$restaurant = Restaurant::find(1);
$orders = Order::findAll();

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
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h1 class="mb-4">Orders</h1>
            <?php if(isset($_SESSION['markedComplete'])){ unset($_SESSION['markedComplete']); ?>
                <div class="alert alert-success">Marked order as complete</div>
            <?php } ?>
            <?php if($orders): ?>
            <table class="table table-dark">
                <thead>
                <tr scope="col">
                    <td>UserId</td>
                    <td>Course</td>
                    <td>Department</td>
                    <td>Price</td>
                    <td>Date</td>
                    <td></td>
                </tr>
                </thead>
                <?php foreach($orders as $meal): ?>
                    <tr>
                        <td><?= $meal['user_id'] ?></td>
                        <td>
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
                        </td>
                        <td><?php echo $meal['department'] ?></td>
                        <td>$<?php echo $meal['price'] ?></td>
                        <td><?= (new DateTime($meal['created']))->format("d M y") ?></td>
                        <td>
                            <?php if($meal['status'] != '1'): ?>
                                <a class="mr-2" href="<?= SITE_ROOT . '/meal/remove_order.php?id=' . $meal['id'] ?>">
                                    <button class="btn btn-sm btn-danger">X</button>
                                </a>
                                <?php if($_SESSION['user']['role'] === '1'): ?>
                                <a href="<?= SITE_ROOT . '/orders/complete.php?id=' . $meal['id'] ?>">
                                    <button class="btn btn-sm btn-success">✓</button>
                                </a>
                                <?php endif; ?>
                            <?php else: ?>
                            <?= $meal['status'] === '1' ? 'Complete' : 'Cancelled' ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <div class="text-center jumbotron">You have no orders.</div>
            <?php endif; ?>
        </div>
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
