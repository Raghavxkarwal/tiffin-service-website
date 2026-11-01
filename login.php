<?php
require 'config/database.php';
require 'includes/functions.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $email=strtolower(trim($_POST['email'] ?? '')); $password=$_POST['password'] ?? '';
    $stmt=$pdo->prepare("SELECT id,name,email,password_hash,role FROM users WHERE email=?");
    $stmt->execute([$email]); $user=$stmt->fetch();
    if ($user && password_verify($password,$user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']=(int)$user['id']; $_SESSION['user_name']=$user['name']; $_SESSION['role']=$user['role'];
        redirect(is_admin()?'admin/dashboard.php':'index.php');
    } else $error='Incorrect email or password.';
}
$pageTitle='Login | Ghar Ka Tiffin'; $assetPrefix='';
require 'includes/header.php';
?>
<section class="section container narrow"><div class="section-heading"><span class="eyebrow">WELCOME BACK</span><h2>Login</h2></div>
<?php if($error): ?><p class="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="form-card"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Email address<input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></label>
<label>Password<input type="password" name="password" required></label>
<button class="button button-primary full">Login</button><p>New customer? <a href="register.php">Create an account</a>.</p></form></section>
<?php require 'includes/footer.php'; ?>
