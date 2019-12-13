<?php

require_once __DIR__ . '/../../config.php';

if(!isset($_SESSION['user'])){
    header("Location: ".SITE_ROOT . "/auth/index.php");
}
