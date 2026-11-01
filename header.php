<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Fresh homemade tiffin meals delivered to your doorstep.">
  <title><?= e($pageTitle ?? 'Ghar Ka Tiffin') ?></title>
  <link rel="stylesheet" href="<?= e($assetPrefix ?? '') ?>assets/css/style.css">
  <script defer src="<?= e($assetPrefix ?? '') ?>assets/js/script.js"></script>
</head>
<body>
<header class="site-header">
  <nav class="nav container">
    <a class="logo" href="<?= e($assetPrefix ?? '') ?>index.php">🍱 Ghar Ka Tiffin</a>
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">☰</button>
    <div class="nav-links" id="navLinks">
      <a href="<?= e($assetPrefix ?? '') ?>index.php">Home</a>
      <a href="<?= e($assetPrefix ?? '') ?>menu.php">Menu</a>
      <a href="<?= e($assetPrefix ?? '') ?>cart.php">Cart (<?= cart_count() ?>)</a>
      <?php if (is_logged_in()): ?>
        <a href="<?= e($assetPrefix ?? '') ?>orders.php">My Orders</a>
        <?php if (is_admin()): ?><a href="<?= e($assetPrefix ?? '') ?>admin/dashboard.php">Admin</a><?php endif; ?>
        <a class="nav-order" href="<?= e($assetPrefix ?? '') ?>logout.php">Logout</a>
      <?php else: ?>
        <a href="<?= e($assetPrefix ?? '') ?>login.php">Login</a>
        <a class="nav-order" href="<?= e($assetPrefix ?? '') ?>register.php">Register</a>
      <?php endif; ?>
    </div>
  </nav>
</header>
<main>
