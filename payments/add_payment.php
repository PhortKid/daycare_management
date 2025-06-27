<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parent_id = intval($_POST['parent_id']);
    $child_id = intval($_POST['child_id']);
    $amount = floatval($_POST['amount']);
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    $payment_date = $_POST['payment_date'] ? $_POST['payment_date'] : null;
    $payment_method = trim($_POST['payment_method']);
    $invoice_number = trim($_POST['invoice_number']);

    $sql = "INSERT INTO payments (parent_id, child_id, amount, due_date, status, payment_date, payment_method, invoice_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iissssss', $parent_id, $child_id, $amount, $due_date, $status, $payment_date, $payment_method, $invoice_number);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Payment added successfully!';
    } else {
        $error = 'Error adding payment: ' . mysqli_error($conn);
    }
}
// Fetch parents and children for dropdowns
$parents = mysqli_query($conn, "SELECT user_id, first_name, last_name FROM users WHERE role='parent'");
$children = mysqli_query($conn, "SELECT child_id, first_name, last_name FROM children");
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add Payment</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Parent</label>
                <select name="parent_id" class="form-control" required>
                    <?php while($p = mysqli_fetch_assoc($parents)): ?>
                        <option value="<?php echo $p['user_id']; ?>"><?php echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Child</label>
                <select name="child_id" class="form-control" required>
                    <?php while($c = mysqli_fetch_assoc($children)): ?>
                        <option value="<?php echo $c['child_id']; ?>"><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Payment Date</label>
                <input type="date" name="payment_date" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>Payment Method</label>
                <input type="text" name="payment_method" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label>Invoice Number</label>
            <input type="text" name="invoice_number" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Payment</button>
        <a href="payments.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
