<?php

require_once __DIR__ . '/../../config.php';

if(!isset($_SESSION['user']) && $_SESSION['user']['role'] != 1){
    header("Location: ".SITE_ROOT . "/auth/index.php");
}
