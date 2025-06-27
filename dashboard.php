<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
} 
include 'includes/header.php';

?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="profile.php" class="btn btn-info">View Profile</a>
        <a href="change_password.php" class="btn btn-warning">Change Password</a>
    </div>
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</p>
    <!-- Add dashboard widgets or stats here -->
</div>
<?php include 'includes/footer.php'; ?>
