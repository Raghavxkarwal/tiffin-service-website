<?php
require 'config/database.php';
require 'includes/functions.php';
require_login();
$stmt=$pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]); $orders=$stmt->fetchAll();
$pageTitle='My Orders | Ghar Ka Tiffin'; $assetPrefix='';
require 'includes/header.php';
?>
<section class="section container narrow"><div class="section-heading"><span class="eyebrow">YOUR FOOD JOURNEY</span><h2>My orders</h2></div>
<?php if(isset($_GET['placed'])): ?><p class="success">Your order was placed successfully!</p><?php endif; ?>
<?php if(!$orders): ?><p class="empty-cart">You haven't placed an order yet. <a href="menu.php">Explore our menu</a>.</p>
<?php else: foreach($orders as $order):
  $lines=$pdo->prepare("SELECT * FROM order_items WHERE order_id=?"); $lines->execute([$order['id']]); $items=$lines->fetchAll(); ?>
  <article class="order-card"><div class="meal-title"><h3>Order #<?= (int)$order['id'] ?></h3><strong>₹<?= number_format($order['total'],2) ?></strong></div>
  <p>Status: <b><?= e($order['status']) ?></b> · <?= e($order['created_at']) ?></p>
  <?php foreach($items as $item): ?><p><?= e($item['meal_name']) ?> × <?= (int)$item['quantity'] ?></p><?php endforeach; ?>
  <small>Delivery address: <?= e($order['delivery_address']) ?></small></article>
<?php endforeach; endif; ?></section>
<?php require 'includes/footer.php'; ?>
