<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';
include '../includes/header.php';

// Fetch children with babysitter name
$sql = "SELECT c.*, u.first_name AS babysitter_fname, u.last_name AS babysitter_lname 
        FROM children c 
        LEFT JOIN users u ON c.teacher_id = u.user_id";
if ($_SESSION['role'] === 'babysitter') {
    // Babysitter sees only their assigned children
    $sql .= " WHERE c.teacher_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
} else {
    $stmt = $pdo->query($sql);
}

?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Children Management</h1>
    <?php if ($_SESSION['role'] !== 'parent'): ?>
        <a href="add_child.php" class="btn btn-primary mb-3">Add Child</a>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Child Name</th>
                <th>Parent</th>
                <th>Date of Birth</th>
                <th>Babysitter</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $auto_id = 1;
            while($row = $stmt->fetch()): ?>
            <tr>
                <td><?php echo $auto_id++; ?></td>
                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td>
                    <?php
                    // Fetch parent name
                    $parent = $pdo->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
                    $parent->execute([$row['parent_id']]);
                    $p = $parent->fetch();
                    echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']);
                    ?>
                </td>
                <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
                <td>
                    <?php
                    if ($row['babysitter_fname']) {
                        echo htmlspecialchars($row['babysitter_fname'] . ' ' . $row['babysitter_lname']);
                    } else {
                        echo '<span class="text-muted">N/A</span>';
                    }
                    ?>
                </td>
                <td>
                    <a href="view_child.php?id=<?php echo $row['child_id']; ?>" class="btn btn-sm btn-info">View</a>
                    <?php if ($_SESSION['role'] !== 'parent' && $_SESSION['role'] !== 'babysitter'): ?>
                        <a href="edit_child.php?id=<?php echo $row['child_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_child.php?id=<?php echo $row['child_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this child?');">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
