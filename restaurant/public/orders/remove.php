<?php session_start();


require_once __DIR__ . '/../guards/user.php';
require_once __DIR__ . '/../../lib/Order.php';

if(isset($_GET['id'])){
    $o = Order::find($_GET['id']);
    $o->user_id = $_SESSION['user']['id'];
    $o->cancel();
    $_SESSION['orderRemove'] = true;
    header("Location: ".SITE_ROOT.'/index.php');
}else{
    header("Location: ".SITE_ROOT);
}

