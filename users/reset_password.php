
<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$user_id = intval($_GET['id']);
$new_password = password_hash('Mpya@2025', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
if ($stmt->execute([$new_password, $user_id])) {
    $_SESSION['success'] = "Password reset to Mpya@2025.";
} else {
    $_SESSION['error'] = "Failed to reset password.";
}

header('Location: index.php');
exit();
?>