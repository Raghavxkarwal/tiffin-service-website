<?php
require '../config/database.php';
require '../includes/functions.php';
require_admin();
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();
  if(isset($_POST['add_meal'])){
    $name=trim($_POST['name']??'');$desc=trim($_POST['description']??'');$price=(float)($_POST['price']??0);$cat=trim($_POST['category']??'Lunch');$emoji=trim($_POST['emoji']??'🍱');
    if($name===''||$price<=0)$error='Enter a meal name and a price greater than zero.';
    else{$stmt=$pdo->prepare("INSERT INTO meals(name,description,price,category,emoji) VALUES(?,?,?,?,?)");$stmt->execute([$name,$desc,$price,$cat,$emoji]);redirect('manage-menu.php');}
  }
  if(isset($_POST['toggle_meal'])){$stmt=$pdo->prepare("UPDATE meals SET is_available=NOT is_available WHERE id=?");$stmt->execute([(int)$_POST['meal_id']]);redirect('manage-menu.php');}
}
$meals=$pdo->query("SELECT * FROM meals ORDER BY id DESC")->fetchAll();
$pageTitle='Manage Menu';$assetPrefix='../';require '../includes/header.php';
?>
<section class="section container"><h2 class="page-heading">Manage menu</h2>
<?php if($error): ?><p class="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="form-card admin-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><h3>Add a meal</h3>
<label>Meal name<input name="name" required></label><label>Description<textarea name="description"></textarea></label>
<label>Price (₹)<input name="price" type="number" min="1" step="0.01" required></label><label>Category<input name="category" value="Lunch"></label><label>Emoji<input name="emoji" value="🍱" maxlength="10"></label>
<button class="button button-primary" name="add_meal">Add meal</button></form>
<div class="table-wrap"><table><thead><tr><th>Meal</th><th>Price</th><th>Category</th><th>Availability</th><th>Action</th></tr></thead><tbody>
<?php foreach($meals as $meal): ?><tr><td><?= e($meal['emoji'].' '.$meal['name']) ?></td><td>₹<?= number_format($meal['price'],2) ?></td><td><?= e($meal['category']) ?></td><td><?= $meal['is_available']?'Available':'Hidden' ?></td><td><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="meal_id" value="<?= (int)$meal['id'] ?>"><button class="text-button" name="toggle_meal">Toggle</button></form></td></tr><?php endforeach; ?>
</tbody></table></div></section>
<?php require '../includes/footer.php'; ?>
