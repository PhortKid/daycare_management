
<?php
session_start();
require_once 'config/config.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate random 6-character password
        $new_pass = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6);
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        // Update password in DB
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        if ($update->execute([$hashed_pass, $user['user_id']])) {
            // Send SMS
            $sms_api_url = 'https://verifytra.site';
            $sms_payload = json_encode([
                "token" => "dms-friday-2025",
                "phonenumber" => $user['phone'],
                "message" => "Your new Daycare password is: $new_pass"
            ]);
            $ch = curl_init($sms_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $sms_payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);

            $success = "A new password has been sent to your phone.";
        } else {
            $error = "Failed to reset password. Try again.";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="mb-4 text-center">Forgot Password</h4>
                    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Email address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send New Password</button>
                        <a href="login.php" class="btn btn-link w-100 mt-2">Back to Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>