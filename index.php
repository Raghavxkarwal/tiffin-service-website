<?php
require 'config/database.php';
require 'includes/functions.php';
$pageTitle = 'Ghar Ka Tiffin | Homemade Meals';
$assetPrefix = '';
$featured = $pdo->query("SELECT * FROM meals WHERE is_available = 1 ORDER BY id DESC LIMIT 3")->fetchAll();
require 'includes/header.php';
?>
<section class="hero">
  <div class="container hero-content">
    <div class="hero-copy">
      <span class="eyebrow">FRESH • HOMEMADE • DELIVERED</span>
      <h1>Ghar jaisa khana,<br><span>har din.</span></h1>
      <p>Enjoy fresh, delicious and hygienic homemade meals without the daily cooking stress.</p>
      <div class="hero-actions">
        <a class="button button-primary" href="menu.php">Explore Menu</a>
        <a class="button button-light" href="register.php">Join Us</a>
      </div>
      <div class="trust-line">✓ Freshly prepared &nbsp; ✓ Affordable plans &nbsp; ✓ Daily delivery</div>
    </div>
    <div class="hero-art"><div class="food-emoji">🍛</div><div class="fresh-tag">Made with care ❤️</div></div>
  </div>
</section>
<section class="section container">
  <div class="section-heading"><span class="eyebrow">WHAT'S COOKING?</span><h2>Popular meals</h2><p>Simple, satisfying meals made fresh every day.</p></div>
  <div class="menu-grid">
    <?php foreach ($featured as $meal): ?>
      <article class="meal-card">
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
  <div class="center"><a class="button button-primary" href="menu.php">View full menu</a></div>
</section>
<section class="plans-section" id="plans"><div class="container">
  <div class="section-heading"><span class="eyebrow">SAVE MORE</span><h2>Simple meal plans</h2><p>Ask us about weekly and monthly subscriptions.</p></div>
  <div class="plans-grid">
    <article class="plan-card"><h3>Weekly Plan</h3><p class="plan-price">₹599 <small>/ 7 meals</small></p><p>One fresh meal each day for a week.</p><a class="button button-outline" href="menu.php">Explore meals</a></article>
    <article class="plan-card featured"><span class="popular">POPULAR</span><h3>Monthly Plan</h3><p class="plan-price">₹2,499 <small>/ 30 meals</small></p><p>Daily homemade meals at a better price.</p><a class="button button-primary" href="menu.php">Explore meals</a></article>
    <article class="plan-card"><h3>Custom Plan</h3><p class="plan-price">Let's talk</p><p>Need lunch and dinner? Contact us.</p><a class="button button-outline" href="menu.php">Explore meals</a></article>
  </div><p class="fine-print">Sample plan prices. Update them to match your business.</p>
</div></section>
<section class="section container about-section" id="about"><div class="about-icon">👩‍🍳</div><div><span class="eyebrow">ABOUT US</span><h2>Food made with homely love</h2><p>We believe everyday food should be fresh, comforting and affordable. Our meals are prepared with care to make your busy days easier.</p></div></section>
<?php require 'includes/footer.php'; ?>
