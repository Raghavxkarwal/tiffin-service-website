<?php
require 'config/database.php';
require 'includes/functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = 'Enter your name, a valid email and a password with at least 8 characters.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash) VALUES (?,?,?)");
            $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['role'] = 'customer';
            redirect('index.php');
        } catch (PDOException $e) { $error = 'This email may already be registered.'; }
    }
}
$pageTitle='Register | Ghar Ka Tiffin'; $assetPrefix='';
require 'includes/header.php';
?>
<section class="section container narrow"><div class="section-heading"><span class="eyebrow">JOIN OUR FAMILY</span><h2>Create account</h2></div>
<?php if($error): ?><p class="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="form-card"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Full name<input name="name" required value="<?= e($_POST['name'] ?? '') ?>"></label>
<label>Email address<input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></label>
<label>Password<input type="password" name="password" minlength="8" required></label>
<button class="button button-primary full">Register</button><p>Already registered? <a href="login.php">Login here</a>.</p></form></section>
<?php require 'includes/footer.php'; ?>
