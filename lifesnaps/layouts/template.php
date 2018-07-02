<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php pageTitle(); ?> | <?php siteName(); ?></title>

    <link rel="stylesheet" href="assets/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-dark text-white">
  <header class="container text-center header bg-secondary">
    <div class="row">
      <div class="col col-xs-1 text-left">
        <a href="?page=settings"><i class="fas fa-cog"></i></a>
      </div>
      <div class="col col-xs text-center">
        <strong><a href="?page=home"><?php siteName(); ?></a></strong>
      </div>
      <div class="col col-xs-1 text-right">
        <a href="?page=login"><i class="fas fa-sign-out-alt"></i></a>
      </div>
    </div>
  </header>

  <div class="container body">
    <!-- <h3><?php pageTitle(); ?></h3> -->
    <?php pageContent(); ?>
  </div>

  <footer class="container text-center footer bg-secondary">
    <div class="row">
      <div class="col">
        <a href="?page=home"><i class="fas fa-home fa-fw"></i></a>
      </div>
      <div class="col">
        <a href="?page=users"><i class="fas fa-users fa-fw"></i></a>
      </div>
      <div class="col">
        <a href="?page=upload"><i class="fas fa-camera-retro fa-lg fa-fw"></i></a>
      </div>
      <div class="col">
        <a href="?page=profile&id=<?php echo $_SESSION['user_id'] ?>"><i class="fas fa-user-circle fa-fw"></i></a>
      </div>
    </div>
  </footer>

  <!-- <div class="container text-center">
    <footer><small>&copy;<?php echo date('Y'); ?> <?php siteName(); ?> <?php siteVersion(); ?>. All rights reserved.</small></footer>
  </div> -->

  <script src="assets/js/jquery-3.3.1.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
