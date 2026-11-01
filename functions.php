<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function redirect($url) {
    header("Location: $url");
    exit;
}
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            exit('Invalid request token. Please go back and try again.');
        }
    }
}
function is_logged_in() {
    return !empty($_SESSION['user_id']);
}
function require_login() {
    if (!is_logged_in()) redirect('login.php');
}
function is_admin() {
    return ($_SESSION['role'] ?? '') === 'admin';
}
function require_admin() {
    if (!is_admin()) redirect('../login.php');
}
function cart_count() {
    return array_sum($_SESSION['cart'] ?? []);
}
