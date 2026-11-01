<?php
require 'config/database.php';
require 'includes/functions.php';
$pageTitle = 'Our Menu | Ghar Ka Tiffin';
$assetPrefix = '';
$meals = $pdo->query("SELECT * FROM meals WHERE is_available = 1 ORDER BY category, name")->fetchAll();
require 'includes/header.php';
?>
<section class="section container">
  <div class="section-heading"><span class="eyebrow">FRESH FROM OUR KITCHEN</span><h2>Our complete menu</h2><p>Choose your favourite homemade meals.</p></div>
  <div class="filters"><input type="search" id="menuSearch" placeholder="Search meals..." aria-label="Search meals"></div>
  <div class="menu-grid" id="menuGrid">
    <?php foreach ($meals as $meal): ?>
      <article class="meal-card searchable-meal" data-search="<?= e(strtolower($meal['name'].' '.$meal['description'].' '.$meal['category'])) ?>">
        <div class="meal-image"><?= e($meal['emoji']) ?></div>
        <div class="meal-info">
          <div class="meal-title"><h3><?= e($meal['name']) ?></h3><strong>₹<?= number_format($meal['price'], 2) ?></strong></div>
          <p><?= e($meal['description']) ?></p>
          <form method="post" action="cart.php">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="meal_id" value="<?= (int)$meal['id'] ?>">
            <button class="add-button" type="submit" name="add_to_cart">Add to cart +</button>
          </form>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
  <p id="noMeals" class="empty-cart hidden">No matching meals found.</p>
</section>
<?php require 'includes/footer.php'; ?>
