<?php session_start();
require_once __DIR__ . '/../../lib/Meal.php';
require_once __DIR__ . '/../../lib/Restaurant.php';

$restaurants = Restaurant::findAll();

if($_POST){
   $restaurant_id = isset($_POST['restaurant']) ? trim($_POST['restaurant']) : '';
   $course1 = isset($_POST['course1']) ? trim($_POST['course1']) : '';
   $course2 = isset($_POST['course2']) ? trim($_POST['course2']) : '';
   $course3 = isset($_POST['course3']) ? trim($_POST['course3']) : '';
   $dessert = isset($_POST['dessert']) ? trim($_POST['dessert']) : '';
   $drink = isset($_POST['drink']) ? trim($_POST['drink']) : '';
   $price = isset($_POST['price']) ? trim($_POST['price']) : '';

   $meal = new Meal();
   $meal->restaurant_id = $restaurant_id;
   $meal->course1 = $course1;
   $meal->course2 = $course2;
   $meal->course3 = $course3;
   $meal->dessert = $dessert;
   $meal->drink = $drink;
   $meal->price = $price;

   $success = $meal->create();
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
      <div class="row">
          <div class="col">

              <?php if(isset($success)): ?>
                  <div class="alert alert-success">Added new meal to menu</div>
              <?php endif; ?>
              <?php if(!empty($restaurants)): ?>
                  <h1 class="mb-4">Create Meal</h1>
                  <form method="POST">
                      <div class="form-group">
                          <label>Restaurant</label>
                          <select class="form-control" name="restaurant">
                              <?php foreach($restaurants as $r): ?>
                              <option value="<?= $r->id?>"><?= $r->name ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                      <div class="form-group">
                          <label>Course 1(Protein)</label>
                          <input class="form-control" type="text" name="course1" placeholder="Beef" required />
                      </div>
                      <div class="form-group">
                          <label>Course 2(Carbs)</label>
                          <input  type="text" class="form-control" name="course2" placeholder="Rice" required />
                      </div>
                      <div class="form-group">
                          <label>Course 3(Salad)</label>
                          <input type="text" class="form-control" name="course3" placeholder="Soup" required />
                      </div>
                      <div class="form-group">
                          <label>Dessert</label>
                          <input type="text" class="form-control" name="dessert" placeholder="Vanilla Ice Cream" />
                      </div>
                      <div class="form-group">
                          <label>Drink</label>
                          <input type="text" class="form-control" name="drink" placeholder="Coke" />
                      </div>
                      <div class="form-group">
                          <label>Price</label>
                          <input type="number" class="form-control" min="1" max="12" name="price" placeholder="12" required />
                      </div>
                      <button class="btn btn-primary">Create Meal</button>
                  </form>
              <?php else: ?>
              <div class="text-center jumbotron">
                  No restaurant to create meal for.<br>
                  <a href="<?= SITE_ROOT ?>/restaurant" class="btn btn-secondary btn-sm mt-4">Create Restaurant</a>
              </div>
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
