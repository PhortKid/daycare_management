<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: login.php');
    exit();
}
require_once '../config/config.php';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssss', $first_name, $last_name, $email, $phone, $password, $role, $status);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'User added successfully!';
    } else {
        $error = 'Error adding user: ' . mysqli_error($conn);
    }
}
?>
<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add User</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <option value="admin">Admin</option>
                    <?php endif; ?>
                 
                    <option value="headteacher">Headteacher</option>
                    <option value="babysitter">Babysitter</option>
                    <option value="parent">Parent</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
