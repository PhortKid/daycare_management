<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
} 
include 'includes/header.php';
require_once 'config/config.php';

// Prepare data for charts (only for non-parent roles)
$childrenPerMonth = array_fill(1, 12, 0);
$reportsPerMonth = array_fill(1, 12, 0);

if ($_SESSION['role'] !== 'parent') {
    // Children enrolled per month
    $stmt = $pdo->query("SELECT MONTH(enrollment_date) as month, COUNT(*) as count FROM children WHERE enrollment_date IS NOT NULL GROUP BY month");
    while ($row = $stmt->fetch()) {
        $childrenPerMonth[(int)$row['month']] = (int)$row['count'];
    }
    // Daily reports per month
    $stmt2 = $pdo->query("SELECT MONTH(report_date) as month, COUNT(*) as count FROM daily_reports GROUP BY month");
    while ($row = $stmt2->fetch()) {
        $reportsPerMonth[(int)$row['month']] = (int)$row['count'];
    }
}
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="profile.php" class="btn btn-info">View Profile</a>
        <a href="change_password.php" class="btn btn-warning">Change Password</a>
    </div>
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</p>
    <!-- Add dashboard widgets or stats here -->

    <?php if ($_SESSION['role'] !== 'parent'): ?>
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Children Enrolled Per Month</h6>
                </div>
                <div class="card-body">
                    <canvas id="childrenChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Reports Per Month</h6>
                </div>
                <div class="card-body">
                    <canvas id="reportsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script src="dashboard_assets/vendor/chart.js/Chart.min.js"></script>
    <script>
    const monthLabels = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const childrenData = <?php echo json_encode(array_values($childrenPerMonth)); ?>;
    const reportsData = <?php echo json_encode(array_values($reportsPerMonth)); ?>;

    // Children Chart
    new Chart(document.getElementById('childrenChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Children Enrolled',
                data: childrenData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } }
        }
    });
    // Reports Chart
    new Chart(document.getElementById('reportsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Daily Reports',
                data: reportsData,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } }
        }
    });
    </script>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'parent'): ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">My Child's Payments</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Child</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                    <th>Method</th>
                                    <th>Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            require_once 'config/config.php';
                            $parent_id = $_SESSION['user_id'];
                            $sql = "SELECT p.*, c.first_name AS child_first, c.last_name AS child_last FROM payments p JOIN children c ON p.child_id = c.child_id WHERE p.parent_id = ? ORDER BY p.due_date DESC";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$parent_id]);
                            while ($row = $stmt->fetch()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['child_first'] . ' ' . $row['child_last']); ?></td>
                                    <td><?php echo number_format($row['amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                                    <td><?php echo ucfirst($row['status']); ?></td>
                                    <td><?php echo $row['payment_date'] ? htmlspecialchars($row['payment_date']) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                    <td><?php echo htmlspecialchars($row['invoice_number']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
