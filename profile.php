<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once 'config/config.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user) {
    echo "User not found.";
    exit();
}
?>
<?php include 'includes/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">My Profile</div>
                <div class="card-body">
                    <table class="table">
                        <tr><th>First Name</th><td><?php echo htmlspecialchars($user['first_name']); ?></td></tr>
                        <tr><th>Last Name</th><td><?php echo htmlspecialchars($user['last_name']); ?></td></tr>
                        <tr><th>Email</th><td><?php echo htmlspecialchars($user['email']); ?></td></tr>
                        <tr><th>Phone</th><td><?php echo htmlspecialchars($user['phone']); ?></td></tr>
                        <tr><th>Role</th><td><?php echo ucfirst($user['role']); ?></td></tr>
                        <tr><th>Status</th><td><?php echo ucfirst($user['status']); ?></td></tr>
                        <tr><th>Created At</th><td><?php echo $user['created_at']; ?></td></tr>
                    </table>
                    <a href="change_password.php" class="btn btn-warning">Change Password</a>
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
