<?php
session_start();
/*
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
} */
include 'includes/header.php';

?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</p>
    <!-- Add dashboard widgets or stats here -->
</div>
<?php include 'includes/footer.php'; ?>
