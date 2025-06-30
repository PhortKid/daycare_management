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

// Only allow parent to view their own child's details
if ($_SESSION['role'] === 'parent') {
    $sql = "SELECT c.*, u.first_name AS parent_fname, u.last_name AS parent_lname
            FROM children c
            JOIN users u ON c.parent_id = u.user_id
            WHERE c.child_id = ? AND c.parent_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$child_id, $_SESSION['user_id']]);
} elseif ($_SESSION['role'] === 'babysitter') {
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
        <tr><th>Medical Notes</th><td><?php echo htmlspecialchars($child['medical_notes']); ?></td></tr>
        <tr><th>Allergies</th><td><?php echo htmlspecialchars($child['allergies']); ?></td></tr>
        <tr><th>Enrollment Date</th><td><?php echo htmlspecialchars($child['enrollment_date']); ?></td></tr>
        <tr><th>Parent Name</th><td><?php echo htmlspecialchars($child['parent_fname'] . ' ' . $child['parent_lname']); ?></td></tr>
        <tr><th>Parent Phone</th>
            <td>
                <?php
                // Fetch parent phone
                $parent_phone = '';
                if (!empty($child['parent_fname']) && !empty($child['parent_lname'])) {
                    $parent_stmt = $pdo->prepare("SELECT phone FROM users WHERE first_name = ? AND last_name = ? LIMIT 1");
                    $parent_stmt->execute([$child['parent_fname'], $child['parent_lname']]);
                    $parent = $parent_stmt->fetch();
                    if ($parent) {
                        $parent_phone = $parent['phone'];
                    }
                }
                echo htmlspecialchars($parent_phone);
                ?>
            </td>
        </tr>
    </table>
    <a href="index.php" class="btn btn-secondary">Back</a>
</div>
<?php include '../includes/footer.php'; ?>