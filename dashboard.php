<?php
require '../config/database.php';
require '../includes/functions.php';
require_admin();
$totalOrders=(int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pendingOrders=(int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status='Pending'")->fetchColumn();
$totalUsers=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
$pageTitle='Admin Dashboard'; $assetPrefix='../';
require '../includes/header.php';
?>
<section class="section container"><span class="eyebrow">STORE MANAGEMENT</span><h2 class="page-heading">Admin dashboard</h2>
<div class="stats-grid"><article><span>Total orders</span><strong><?= $totalOrders ?></strong></article><article><span>Pending orders</span><strong><?= $pendingOrders ?></strong></article><article><span>Customers</span><strong><?= $totalUsers ?></strong></article></div>
<div class="admin-links"><a class="button button-primary" href="manage-menu.php">Manage menu</a><a class="button button-primary" href="manage-orders.php">Manage orders</a></div></section>
<?php require '../includes/footer.php'; ?>
