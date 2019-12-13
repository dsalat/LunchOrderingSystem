<?php session_start();
require_once __DIR__.'/../../lib/User.php';

if($_GET['email']){
    session_destroy();
    $u = new User();
    $u->email = $_GET['email'];
    $u->makeAdmin();
    $_SESSION['madeAdmin'] = true;
    header('Location: '.SITE_ROOT.'/auth');
}