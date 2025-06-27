<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: login.php');
    exit();
}
require_once 'config/config.php';

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit();
}
$user_id = intval($_GET['id']);
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    $status = $_POST['status'];
    $sql = "UPDATE users SET first_name=?, last_name=?, email=?, phone=?, role=?, status=? WHERE user_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssssi', $first_name, $last_name, $email, $phone, $role, $status, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'User updated successfully!';
    } else {
        $error = 'Error updating user: ' . mysqli_error($conn);
    }
}

// Fetch user data
$sql = "SELECT * FROM users WHERE user_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    header('Location: users.php');
    exit();
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit User</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
                    <option value="headteacher" <?php if($user['role']=='headteacher') echo 'selected'; ?>>Headteacher</option>
                    <option value="babysitter" <?php if($user['role']=='babysitter') echo 'selected'; ?>>Babysitter</option>
                    <option value="parent" <?php if($user['role']=='parent') echo 'selected'; ?>>Parent</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="active" <?php if($user['status']=='active') echo 'selected'; ?>>Active</option>
                    <option value="pending" <?php if($user['status']=='pending') echo 'selected'; ?>>Pending</option>
                    <option value="inactive" <?php if($user['status']=='inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="users.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
