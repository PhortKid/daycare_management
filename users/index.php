<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';
include '../includes/header.php';

// Handle filter
$filter_role = isset($_GET['role']) ? $_GET['role'] : '';
$where = '';
$params = [];
if ($filter_role && in_array($filter_role, ['admin','headteacher','babysitter','parent'])) {
    $where = "WHERE role = ?";
    $params[] = $filter_role;
}

$sql = "SELECT * FROM users $where ORDER BY user_id DESC";
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param('s', $params[0]);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">User Management</h1>
    <a href="/users/add_user.php" class="btn btn-primary mb-3">Add User</a>
    <form method="get" class="mb-3">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="role"  class="form-control">
                    <option value="">-- Filter by Role --</option>
                    <option value="admin" <?php if($filter_role=='admin') echo 'selected'; ?>>Admin</option>
                    <option value="headteacher" <?php if($filter_role=='headteacher') echo 'selected'; ?>>Headteacher</option>
                    <option value="babysitter" <?php if($filter_role=='babysitter') echo 'selected'; ?>>Babysitter</option>
                    <option value="parent" <?php if($filter_role=='parent') echo 'selected'; ?>>Parent</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="index.php" class="btn btn-light">Reset</a>
            </div>
        </div>
    </form>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $auto_id = 1;
            while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $auto_id++; ?></td>
                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo ucfirst($row['role']); ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td>
                    <a href="/users/edit_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="/users/delete_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    <a href="/users/reset_password.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-warning" onclick="return confirm('Reset password for this user to Mpya@2025?');">Reset Password</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
