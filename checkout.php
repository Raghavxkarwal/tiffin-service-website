<?php
require 'config/database.php';
require 'includes/functions.php';
require_login();
$cart = $_SESSION['cart'] ?? [];
if (!$cart) redirect('menu.php');
$ids = array_map('intval', array_keys($cart));
$marks = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM meals WHERE is_available = 1 AND id IN ($marks)");
$stmt->execute($ids);
$meals = $stmt->fetchAll();
$total = 0; $orderItems = [];
foreach ($meals as $meal) {
    $qty = (int)($cart[$meal['id']] ?? 0);
    if ($qty < 1) continue;
    $total += $meal['price'] * $qty;
    $orderItems[] = ['meal'=>$meal, 'quantity'=>$qty];
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if ($address === '' || !preg_match('/^[0-9+\-\s]{8,20}$/', $phone)) {
        $error = 'Enter a valid phone number and delivery address.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, delivery_address, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $total, $address, $phone]);
            $orderId = (int)$pdo->lastInsertId();
            $line = $pdo->prepare("INSERT INTO order_items (order_id, meal_id, meal_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
            foreach ($orderItems as $entry) {
                $m = $entry['meal'];
                $line->execute([$orderId, $m['id'], $m['name'], $m['price'], $entry['quantity']]);
            }
            $pdo->commit();
            unset($_SESSION['cart']);
            redirect('orders.php?placed=1');
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'Could not place your order. Please try again.';
        }
    }
}
$pageTitle = 'Checkout | Ghar Ka Tiffin'; $assetPrefix = '';
require 'includes/header.php';
?>
<section class="section container narrow">
  <div class="section-heading"><span class="eyebrow">ALMOST THERE</span><h2>Checkout</h2><p>Review your details and place your order.</p></div>
  <?php if ($error): ?><p class="alert"><?= e($error) ?></p><?php endif; ?>
  <div class="checkout-summary"><?php foreach ($orderItems as $entry): ?><p><?= e($entry['meal']['name']) ?> × <?= (int)$entry['quantity'] ?> <strong>₹<?= number_format($entry['meal']['price']*$entry['quantity'],2) ?></strong></p><?php endforeach; ?><h3>Total: ₹<?= number_format($total,2) ?></h3></div>
  <form method="post" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label>Phone number<input required name="phone" type="tel" value="<?= e($_POST['phone'] ?? '') ?>" placeholder="Your phone number"></label>
    <label>Delivery address<textarea required name="address" rows="4" placeholder="House number, street, area, city"><?= e($_POST['address'] ?? '') ?></textarea></label>
    <button class="button button-primary full">Place order</button>
  </form>
</section>
<?php require 'includes/footer.php'; ?>
