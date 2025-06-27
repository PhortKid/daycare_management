<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','babysitter','parent'])) {
    header('Location: ../login.php');
    exit();
}
include '../includes/header.php';

require_once '../config/config.php';

// Fetch children (admins/headteachers see all, parents see their own, babysitters see assigned)
$where = '';
if ($_SESSION['role'] === 'parent') {
    $parent_id = intval($_SESSION['user_id']);
    $where = "WHERE c.parent_id = $parent_id";
} elseif ($_SESSION['role'] === 'babysitter') {
    $babysitter_id = intval($_SESSION['user_id']);
    $where = "WHERE c.teacher_id = $babysitter_id";
}
$sql = "SELECT c.*, u.first_name AS parent_first, u.last_name AS parent_last FROM children c JOIN users u ON c.parent_id = u.user_id $where";
$result = mysqli_query($conn, $sql);
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Children Management</h1>
    <?php if (in_array($_SESSION['role'], ['admin','headteacher','parent'])): ?>
    <a href="children/add_child.php" class="btn btn-primary mb-3">Add Child</a>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Parent</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Teacher</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['child_id']; ?></td>
                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['parent_first'] . ' ' . $row['parent_last']); ?></td>
                <td><?php echo $row['date_of_birth']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['teacher_id']; ?></td>
                <td>
                    <a href="edit_child.php?id=<?php echo $row['child_id']; ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="delete_child.php?id=<?php echo $row['child_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this child?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
