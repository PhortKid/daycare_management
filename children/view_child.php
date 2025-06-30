<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$child_id = intval($_GET['id']);

// Only allow babysitter to view their assigned children
if ($_SESSION['role'] === 'babysitter') {
    $sql = "SELECT c.*, u.first_name AS parent_fname, u.last_name AS parent_lname
            FROM children c
            JOIN users u ON c.parent_id = u.user_id
            WHERE c.child_id = ? AND c.teacher_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$child_id, $_SESSION['user_id']]);
} else {
    $sql = "SELECT c.*, u.first_name AS parent_fname, u.last_name AS parent_lname
            FROM children c
            JOIN users u ON c.parent_id = u.user_id
            WHERE c.child_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$child_id]);
}

$child = $stmt->fetch();
if (!$child) {
    echo "<div class='alert alert-danger'>Child not found or access denied.</div>";
    exit();
}
include '../includes/header.php';
?>
<div class="container mt-4">
    <h3>Child Information</h3>
    <table class="table table-bordered">
        <tr><th>Name</th><td><?php echo htmlspecialchars($child['first_name'] . ' ' . $child['last_name']); ?></td></tr>
        <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($child['date_of_birth']); ?></td></tr>
        <tr><th>Gender</th><td><?php echo htmlspecialchars($child['gender']); ?></td></tr>
        <tr><th>Parent</th><td><?php echo htmlspecialchars($child['parent_fname'] . ' ' . $child['parent_lname']); ?></td></tr>
        <tr><th>Medical Notes</th><td><?php echo htmlspecialchars($child['medical_notes']); ?></td></tr>
        <tr><th>Allergies</th><td><?php echo htmlspecialchars($child['allergies']); ?></td></tr>
        <tr><th>Enrollment Date</th><td><?php echo htmlspecialchars($child['enrollment_date']); ?></td></tr>
    </table>
    <a href="index.php" class="btn btn-secondary">Back</a>
</div>
<?php include '../includes/footer.php'; ?>