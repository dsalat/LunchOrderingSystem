<?php session_start();

require_once __DIR__ . '/../guards/admin.php';
require_once __DIR__ . '/../../lib/Order.php';


if(isset($_GET['id'])){
    $order = new Order($_GET['id']);
    $order->markAsComplete();
    $_SESSION['markedComplete'] = true;
    header("Location: ".SITE_ROOT.'/admin/orders.php');
}