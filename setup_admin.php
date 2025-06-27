<?php
// Run this ONCE to create an initial admin user, then delete or secure this file!
require_once 'config/config.php';

$first_name = 'Admin';
$last_name = 'User';
$email = 'admin@daycare.com';
$phone = '1234567890';
$password = password_hash('password123', PASSWORD_DEFAULT);
$role = 'admin';
$status = 'active';

// Check if admin already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo "Admin user already exists.";
    exit;
}

$sql = "INSERT INTO users (first_name, last_name, email, phone, password, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$first_name, $last_name, $email, $phone, $password, $role, $status])) {
    echo "Admin user created successfully!<br>Email: $email<br>Password: password123";
} else {
    echo "Error: Could not create admin user.";
}
?>
