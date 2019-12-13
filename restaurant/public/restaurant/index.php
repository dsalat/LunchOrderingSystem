<?php session_start();
require_once __DIR__ . '/../guards/admin.php';
require_once __DIR__ . '/../../lib/Restaurant.php';

if(isset($_POST['name'])){
    $name = $_POST['name'];
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';

    $r = new Restaurant();
    $r->name = $name;
    $r->address = $address;
    $r->telephone = $telephone;
    $r->create();
    $_SESSION['createdRestaurant'] = true;
    header("Location: ".SITE_ROOT.'/index.php');
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
  <?php include __DIR__ . '/../partials/nav.php'; ?>

  <div class="container mt-5">
      <?php if(isset($success)): ?>
        <div class="alert alert-success">Added new meal to menu</div>
      <?php endif; ?>
      <h1 class="mb-4">Add Restaurant</h1>
      <form method="POST">
          <div class="form-group">
              <label>Name</label>
              <input class="form-control" type="text" name="name" placeholder="Restaurant" required />
          </div>
          <div class="form-group">
              <label>Address</label>
              <input  type="text" class="form-control" name="address" placeholder="Address" required />
          </div>
          <div class="form-group">
              <label>Telephone</label>
              <input type="tel" class="form-control" name="telephone" placeholder="Telephone" required />
          </div>
          <button class="btn btn-primary">Add Restaurant</button>
      </form>
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

