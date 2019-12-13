<?php session_start();
require_once __DIR__ . '/../guards/user.php';
require_once __DIR__ . '/../../lib/Order.php';

if(isset($_POST['meal'])){
    $o = new Order();
    $o->meal_id = $_POST['meal'];
    $o->user_id = $_SESSION['user']['id'];
    $o->create();
    $_SESSION['orderAdd'] = true;
    header("Location: ".SITE_ROOT.'/index.php');
}else{
    header("Location: ".SITE_ROOT);
}

