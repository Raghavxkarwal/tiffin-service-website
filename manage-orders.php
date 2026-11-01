<?php
require '../config/database.php';
require '../includes/functions.php';
require_admin();
$statuses=['Pending','Confirmed','Preparing','Out for delivery','Delivered','Cancelled'];
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();$id=(int)($_POST['order_id']??0);$status=$_POST['status']??'';
  if(in_array($status,$statuses,true)){$stmt=$pdo->prepare("UPDATE orders SET status=? WHERE id=?");$stmt->execute([$status,$id]);}
  redirect('manage-orders.php');
}
$orders=$pdo->query("SELECT o.*,u.name,u.email FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC")->fetchAll();
$pageTitle='Manage Orders';$assetPrefix='../';require '../includes/header.php';
?>
<section class="section container"><h2 class="page-heading">Manage customer orders</h2>
<?php if(!$orders): ?><p>No orders yet.</p><?php endif; ?>
<?php foreach($orders as $order): $q=$pdo->prepare("SELECT * FROM order_items WHERE order_id=?");$q->execute([$order['id']]);$items=$q->fetchAll(); ?>
<article class="order-card"><div class="meal-title"><h3>Order #<?= (int)$order['id'] ?> — <?= e($order['name']) ?></h3><strong>₹<?= number_format($order['total'],2) ?></strong></div>
<p><?= e($order['email']) ?> · <?= e($order['phone']) ?></p><p><?= e($order['delivery_address']) ?></p>
<p><?php foreach($items as $item): ?><?= e($item['meal_name']) ?> × <?= (int)$item['quantity'] ?>; <?php endforeach; ?></p>
<form method="post" class="status-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>"><select name="status"><?php foreach($statuses as $status): ?><option <?= $order['status']===$status?'selected':'' ?>><?= e($status) ?></option><?php endforeach; ?></select><button class="button button-primary">Update status</button></form></article>
<?php endforeach; ?></section>
<?php require '../includes/footer.php'; ?>
