<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','babysitter'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    header('Location: certifications.php');
    exit();
}
$certification_id = intval($_GET['id']);
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $issuing_organization = trim($_POST['issuing_organization']);
    $issue_date = $_POST['issue_date'];
    $expiry_date = $_POST['expiry_date'];
    $sql = "UPDATE certifications SET name=?, issuing_organization=?, issue_date=?, expiry_date=? WHERE certification_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $name, $issuing_organization, $issue_date, $expiry_date, $certification_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Certification updated successfully!';
    } else {
        $error = 'Error updating certification: ' . mysqli_error($conn);
    }
}
// Fetch certification data
$sql = "SELECT * FROM certifications WHERE certification_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $certification_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$cert = mysqli_fetch_assoc($result);
if (!$cert) {
    header('Location: certifications.php');
    exit();
}
?>
<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Certification</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($cert['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Issuing Organization</label>
            <input type="text" name="issuing_organization" class="form-control" value="<?php echo htmlspecialchars($cert['issuing_organization']); ?>" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Issue Date</label>
                <input type="date" name="issue_date" class="form-control" value="<?php echo $cert['issue_date']; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control" value="<?php echo $cert['expiry_date']; ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Certification</button>
        <a href="certifications.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
