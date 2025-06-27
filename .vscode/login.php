<?php
session_start();
require_once 'db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check by username (e.g. CH-2024-0001, AD-0001, BS/0001, HT/0001)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :username LIMIT 1"); // or use 'username' if you renamed it
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Password must start with "Daycare@" + special characters + numbers
        if (password_verify($password, $user['password_hash'])) {
            // Role-based login
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['first_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $username;

            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 flex items-center justify-center h-screen">
    <div class="bg-white p-10 rounded-xl shadow-md w-96">
        <h2 class="text-2xl font-bold mb-5 text-pink-600 text-center">Login</h2>
        <?php if ($message): ?>
            <div class="text-red-500 text-sm mb-4"><?= $message ?></div>
        <?php endif; ?>
        <a href="login.php"> </a>
        <form method="post">
            <label class="block mb-2">Login ID</label>
            <input type="text" name="username" placeholder="e.g. AD-0001" required class="w-full mb-4 p-2 border rounded">

            <label class="block mb-2">Password</label>
            <input type="password" name="password" placeholder="e.g. Daycare@#2024" required class="w-full mb-4 p-2 border rounded">

            <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700">Login</button>
        </form>
    </div>
</body>
</html>
