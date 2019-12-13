<?php

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="<?= SITE_ROOT ?>">Dexter's Labs</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto">
                <a class="nav-item nav-link" href="<?= SITE_ROOT ?>">Home</a>
                <a class="nav-item nav-link" href="<?= SITE_ROOT ?>/orders">My Orders</a>
                <?php if($_SESSION['user']['role'] === '1'): ?>
                <a class="nav-item nav-link" href="<?= SITE_ROOT; ?>/admin/orders.php">All Orders</a>
                <?php endif; ?>
                <span class="nav-item nav-link" href="#">
                      <a href="<?= SITE_ROOT ?>/auth/logout.php" class="mr-2">
                        <button class="btn btn-outline-light btn-sm">Logout</button>
                      </a>
                  </span>
                </a>
            </div>
        </div>
    </div>
</nav>
