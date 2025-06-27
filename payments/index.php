<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','parent'])) {
    header('Location: ../login.php');
    exit();
}
include '../includes/header.php';

require_once '../config/config.php';

// Role-based filter
$where = '';
if ($_SESSION['role'] === 'parent') {
    $parent_id = intval($_SESSION['user_id']);
    $where = "WHERE p.parent_id = $parent_id";
}
$sql = "SELECT p.*, c.first_name AS child_first, c.last_name AS child_last, u.first_name AS parent_first, u.last_name AS parent_last FROM payments p JOIN children c ON p.child_id = c.child_id JOIN users u ON p.parent_id = u.user_id $where ORDER BY p.due_date DESC";
$result = mysqli_query($conn, $sql);
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Payments</h1>
    <?php if (in_array($_SESSION['role'], ['admin','headteacher'])): ?>
    <a href="payments/add_payment.php" class="btn btn-primary mb-3">Add Payment</a>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Parent</th>
                <th>Child</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Payment Date</th>
                <th>Method</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['invoice_number']); ?></td>
                <td><?php echo htmlspecialchars($row['parent_first'] . ' ' . $row['parent_last']); ?></td>
                <td><?php echo htmlspecialchars($row['child_first'] . ' ' . $row['child_last']); ?></td>
                <td><?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo $row['due_date']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td><?php echo $row['payment_date']; ?></td>
                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                <td>
                    <?php if (in_array($_SESSION['role'], ['admin','headteacher'])): ?>
                    <a href="payments/edit_payment.php?id=<?php echo $row['payment_id']; ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="payments/delete_payment.php?id=<?php echo $row['payment_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this payment?');">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
