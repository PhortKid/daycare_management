<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    header('Location: payments.php');
    exit();
}
$payment_id = intval($_GET['id']);
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

    $sql = "UPDATE payments SET parent_id=?, child_id=?, amount=?, due_date=?, status=?, payment_date=?, payment_method=?, invoice_number=? WHERE payment_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iissssssi', $parent_id, $child_id, $amount, $due_date, $status, $payment_date, $payment_method, $invoice_number, $payment_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Payment updated successfully!';
    } else {
        $error = 'Error updating payment: ' . mysqli_error($conn);
    }
}
// Fetch payment data
$sql = "SELECT * FROM payments WHERE payment_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $payment_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$payment = mysqli_fetch_assoc($result);
if (!$payment) {
    header('Location: payments.php');
    exit();
}
$parents = mysqli_query($conn, "SELECT user_id, first_name, last_name FROM users WHERE role='parent'");
$children = mysqli_query($conn, "SELECT child_id, first_name, last_name FROM children");
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Payment</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Parent</label>
                <select name="parent_id" class="form-control" required>
                    <?php while($p = mysqli_fetch_assoc($parents)): ?>
                        <option value="<?php echo $p['user_id']; ?>" <?php if($payment['parent_id']==$p['user_id']) echo 'selected'; ?>><?php echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Child</label>
                <select name="child_id" class="form-control" required>
                    <?php while($c = mysqli_fetch_assoc($children)): ?>
                        <option value="<?php echo $c['child_id']; ?>" <?php if($payment['child_id']==$c['child_id']) echo 'selected'; ?>><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control" value="<?php echo $payment['amount']; ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" value="<?php echo $payment['due_date']; ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="pending" <?php if($payment['status']=='pending') echo 'selected'; ?>>Pending</option>
                    <option value="paid" <?php if($payment['status']=='paid') echo 'selected'; ?>>Paid</option>
                    <option value="overdue" <?php if($payment['status']=='overdue') echo 'selected'; ?>>Overdue</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Payment Date</label>
                <input type="date" name="payment_date" class="form-control" value="<?php echo $payment['payment_date']; ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label>Payment Method</label>
                <input type="text" name="payment_method" class="form-control" value="<?php echo htmlspecialchars($payment['payment_method']); ?>">
            </div>
        </div>
        <div class="mb-3">
            <label>Invoice Number</label>
            <input type="text" name="invoice_number" class="form-control" value="<?php echo htmlspecialchars($payment['invoice_number']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Payment</button>
        <a href="payments.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
