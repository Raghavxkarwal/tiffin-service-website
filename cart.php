<?php
require 'config/database.php';
require 'includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (isset($_POST['add_to_cart'])) {
        $id = (int)($_POST['meal_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT id FROM meals WHERE id = ? AND is_available = 1");
        $stmt->execute([$id]);
        if ($stmt->fetch()) $_SESSION['cart'][$id] = min(20, ($_SESSION['cart'][$id] ?? 0) + 1);
        redirect('cart.php');
    }
    if (isset($_POST['update_cart'])) {
        foreach (($_POST['quantity'] ?? []) as $id => $qty) {
            $id = (int)$id; $qty = max(0, min(20, (int)$qty));
            if ($qty === 0) unset($_SESSION['cart'][$id]);
            else $_SESSION['cart'][$id] = $qty;
        }
        redirect('cart.php');
    }
    if (isset($_POST['clear_cart'])) {
        unset($_SESSION['cart']);
        redirect('cart.php');
    }
}
$pageTitle = 'Your Cart | Ghar Ka Tiffin';
$assetPrefix = '';
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
if ($cart) {
    $ids = array_map('intval', array_keys($cart));
    $marks = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM meals WHERE is_available = 1 AND id IN ($marks)");
    $stmt->execute($ids);
    $items = $stmt->fetchAll();
    foreach ($items as &$item) {
        $item['quantity'] = $cart[$item['id']] ?? 0;
        $item['subtotal'] = $item['price'] * $item['quantity'];
        $total += $item['subtotal'];
    }
    unset($item);
}
require 'includes/header.php';
?>
<section class="section container narrow">
  <div class="section-heading"><span class="eyebrow">YOUR SELECTION</span><h2>Shopping cart</h2></div>
  <?php if (!$items): ?><p class="empty-cart">Your cart is empty. <a href="menu.php">Browse the menu</a>.</p>
  <?php else: ?>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <div class="table-wrap"><table><thead><tr><th>Meal</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr></thead><tbody>
      <?php foreach ($items as $item): ?><tr>
        <td><?= e($item['emoji'].' '.$item['name']) ?></td><td>₹<?= number_format($item['price'],2) ?></td>
        <td><input class="qty-input" type="number" min="0" max="20" name="quantity[<?= (int)$item['id'] ?>]" value="<?= (int)$item['quantity'] ?>"></td>
        <td>₹<?= number_format($item['subtotal'],2) ?></td>
      </tr><?php endforeach; ?>
      </tbody></table></div>
      <h3 class="cart-total">Total: ₹<?= number_format($total,2) ?></h3>
      <div class="hero-actions"><button class="button button-light" name="update_cart">Update cart</button><button class="button button-primary" formaction="checkout.php" formmethod="get">Proceed to checkout</button></div>
      <button class="text-button" name="clear_cart" value="1">Clear cart</button>
    </form>
  <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
