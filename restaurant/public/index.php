<?php session_start();
require_once __DIR__ . '/guards/user.php';
require_once __DIR__ . '/../lib/Restaurant.php';
require_once __DIR__ . '/../lib/Order.php';
require_once __DIR__ . '/../lib/Ticket.php';

$user_id = $_SESSION['user']['id'];
$todayHours = (new DateTime())->format('H');
$orderedToday = Order::orderedToday($user_id);
$lunchHour = 11;
$orderWindowClosed = $todayHours > $lunchHour;
$tickets = Ticket::findByUser($user_id);

echo (new DateTime())->format('Y-m-d H:i:s');


if($orderWindowClosed && !$orderedToday){
    // if didn't get ticket today
    $userTickets = new Ticket();
    $userTickets->user_id = $user_id;
    if($userTickets->gotTicketToday()){
        $_SESSION['gotTicket'] = true;
    }else{
        $userTickets->awardTicket();
        $_SESSION['awardTicket'] = true;
    }
}else if($orderWindowClosed){
    $_SESSION['orderWindowClosed'] = true;
}else{
    // unset if set
    unset($_SESSION['orderWindowClosed']);
    unset($_SESSION['awardTicket']);
    unset($_SESSION['gotTicket']);
    $restaurant = Restaurant::findAll();
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
      .restaurant{
          -webkit-transition: all 0.2s ease-in-out;
          -moz-transition: all 0.2s ease-in-out;
          -ms-transition: all 0.2s ease-in-out;
          -o-transition: all 0.2s ease-in-out;
          transition: all 0.2s ease-in-out;
      }
      .restaurant:hover{
          box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
      }
  </style>
</head>

<body>
  <!--[if IE]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->
  <?php include __DIR__ . '/partials/nav.php'; ?>

  <div class="container mt-5">
      <div class="row">
          <div class="col">
              <?php if(isset($_SESSION['removeMeal'])){ unset($_SESSION['removeMeal']); ?>
                  <div class="alert alert-success">Meal removed from menu.</div>
              <?php } ?>
              <?php if(isset($_SESSION['orderAdd'])){ unset($_SESSION['orderAdd']); ?>
                  <div class="alert alert-success">Your order is confirmed.</div>
              <?php } ?>
              <?php if(isset($_SESSION['orderRemove'])){ unset($_SESSION['orderRemove']); ?>
                  <div class="alert alert-success">Your order was cancelled.</div>
              <?php } ?>
              <?php if(isset($_SESSION['createdRestaurant'])){ unset($_SESSION['createdRestaurant']); ?>
                  <div class="alert alert-success">New restaurant added.</div>
              <?php } ?>
              <h1 class="mb-0">Restaurants
              <?php if($_SESSION['user']['role'] === '1'): ?>
                  <a class="btn btn-sm btn-light" href="<?= SITE_ROOT; ?>/restaurant/index.php">Add Restaurant</a>
              <?php endif; ?>
              </h1>
              <h6 class="mb-4">
                  <b>Free Tickets</b>
                  <span class="badge badge-pill badge-info ml-1"><?= count($tickets) ?></span>
              </h6>
          </div>
      </div>
      <div class="row">
          <?php if($orderedToday): ?>
          <div class="col">
              <div class="jumbotron text-center">Your meal for today has been ordered. :)</div>
          </div>
          <?php elseif($orderWindowClosed): ?>
              <div class="col">
                  <div class="jumbotron text-center">
                      Order window is closed for today.
                      <?= isset($_SESSION['awardTicket']) ? 'You have been awarded with a free meal ticket. :)' : '' ?>
                  </div>
              </div>
          <?php elseif($orderWindowClosed): ?>
              <div class="col">
                  <div class="jumbotron text-center">Order window is closed for today.</div>
              </div>
          <?php else: ?>
          <?php foreach($restaurant as $r): ?>
          <div class="col-12 col-md-6 col-lg-4">
              <div class="restaurant card shadow-sm">
                  <div class="card-body">
                      <h3><?= $r->name ?></h3>
                      <label class="mb-0"><b>Address</b></label>
                      <div class="mb-2"><?= $r->address ?></div>
                      <label class="mb-0"><b>Telephone</b></label>
                      <div class="mb-2"><a href="tel:<?= str_replace([' ', '-'], '', $r->telephone) ?>"><?= $r->telephone ?></a></div>
                      <a href="<?= SITE_ROOT ?>/checkout.php?id=<?= $r->id ?>">Visit</a>
                  </div>
              </div>
          </div>
          <?php endforeach; ?>
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
