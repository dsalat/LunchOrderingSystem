<?php session_start();
require_once __DIR__ .'/../guards/admin.php';
require_once __DIR__ .'/../../lib/Meal.php';

if($_GET['id']){
    $m = new Meal($_GET['id']);
    $m->remove();
    $_SESSION['removeMeal'] = true;
    header("Location: ".SITE_ROOT);
}
