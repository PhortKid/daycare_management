<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    header('Location: parent.php');
    exit();
}
$user_id = intval($_GET['id']);
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $status = $_POST['status'];
    $sql = "UPDATE users SET first_name=?, last_name=?, email=?, phone=?, status=? WHERE user_id=? AND role='parent'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssi', $first_name, $last_name, $email, $phone, $status, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Parent updated successfully!';
    } else {
        $error = 'Error updating parent: ' . mysqli_error($conn);
    }
}

// Fetch parent data
$sql = "SELECT * FROM users WHERE user_id=? AND role='parent'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$parent = mysqli_fetch_assoc($result);
if (!$parent) {
    header('Location: parent.php');
    exit();
}
?>
<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Parent</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($parent['first_name']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($parent['last_name']); ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($parent['email']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($parent['phone']); ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="active" <?php if($parent['status']=='active') echo 'selected'; ?>>Active</option>
                    <option value="pending" <?php if($parent['status']=='pending') echo 'selected'; ?>>Pending</option>
                    <option value="inactive" <?php if($parent['status']=='inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Parent</button>
        <a href="parent.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
