<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','babysitter'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $babysitter_id = $_SESSION['role'] === 'babysitter' ? intval($_SESSION['user_id']) : intval($_POST['babysitter_id']);
    $name = trim($_POST['name']);
    $issuing_organization = trim($_POST['issuing_organization']);
    $issue_date = $_POST['issue_date'];
    $expiry_date = $_POST['expiry_date'];

    $sql = "INSERT INTO certifications (babysitter_id, name, issuing_organization, issue_date, expiry_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'issss', $babysitter_id, $name, $issuing_organization, $issue_date, $expiry_date);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Certification added successfully!';
    } else {
        $error = 'Error adding certification: ' . mysqli_error($conn);
    }
}
// Fetch babysitters for dropdown
$babysitters = mysqli_query($conn, "SELECT user_id, first_name, last_name FROM users WHERE role='babysitter'");
?>
<?php include '../includes/header.php'; ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add Certification</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <?php if ($_SESSION['role'] !== 'babysitter'): ?>
        <div class="mb-3">
            <label>Babysitter</label>
            <select name="babysitter_id" class="form-control" required>
                <?php while($b = mysqli_fetch_assoc($babysitters)): ?>
                    <option value="<?php echo $b['user_id']; ?>"><?php echo htmlspecialchars($b['first_name'] . ' ' . $b['last_name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <?php endif; ?>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Issuing Organization</label>
            <input type="text" name="issuing_organization" class="form-control" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Issue Date</label>
                <input type="date" name="issue_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Certification</button>
        <a href="certifications.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
