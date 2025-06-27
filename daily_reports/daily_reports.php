<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','babysitter','parent'])) {
    header('Location: ../login.php');
    exit();
}
include '../includes/header.php';
include '../includes/sidebar.php';
require_once '../config/config.php';

$where = '';
if ($_SESSION['role'] === 'babysitter') {
    $babysitter_id = intval($_SESSION['user_id']);
    $where = "WHERE dr.babysitter_id = $babysitter_id";
    $children_sql = "SELECT c.child_id, c.first_name, c.last_name FROM children c WHERE c.teacher_id = $babysitter_id";
    $children_result = $pdo->query($children_sql);
} elseif ($_SESSION['role'] === 'parent') {
    $parent_id = intval($_SESSION['user_id']);
    $where = "JOIN children c2 ON dr.child_id = c2.child_id WHERE c2.parent_id = $parent_id";
}
$sql = "SELECT dr.*, c.first_name AS child_first, c.last_name AS child_last, u.first_name AS babysitter_first, u.last_name AS babysitter_last FROM daily_reports dr JOIN children c ON dr.child_id = c.child_id JOIN users u ON dr.babysitter_id = u.user_id $where ORDER BY dr.report_date DESC";
$result = $pdo->query($sql);
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Daily Reports</h1>
    <?php if ($_SESSION['role'] === 'babysitter'): ?>
        <a href="add_daily_report.php" class="btn btn-primary mb-3">Add Daily Report</a>
        <h5>My Assigned Children:</h5>
        <ul>
            <?php foreach($children_result as $child): ?>
                <li><?php echo htmlspecialchars($child['first_name'] . ' ' . $child['last_name']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (in_array($_SESSION['role'], ['admin','headteacher'])): ?>
        <a href="add_daily_report.php" class="btn btn-primary mb-3">Add Daily Report</a>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Child</th>
                <th>Babysitter</th>
                <th>Meals</th>
                <th>Nap Duration</th>
                <th>Sleep Quality</th>
                <th>Activities</th>
                <th>Mood</th>
                <th>Health Notes</th>
                <?php if (in_array($_SESSION['role'], ['admin','headteacher','babysitter'])): ?><th>Actions</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($result as $row): ?>
            <tr>
                <td><?php echo $row['report_date']; ?></td>
                <td><?php echo htmlspecialchars($row['child_first'] . ' ' . $row['child_last']); ?></td>
                <td><?php echo htmlspecialchars($row['babysitter_first'] . ' ' . $row['babysitter_last']); ?></td>
                <td><?php echo htmlspecialchars($row['meals']); ?></td>
                <td><?php echo htmlspecialchars($row['nap_duration']); ?></td>
                <td><?php echo $row['sleep_quality']; ?></td>
                <td><?php echo htmlspecialchars($row['activities']); ?></td>
                <td><?php echo ucfirst($row['mood']); ?></td>
                <td><?php echo htmlspecialchars($row['health_notes']); ?></td>
                <?php if (in_array($_SESSION['role'], ['admin','headteacher','babysitter'])): ?>
                <td>
                    <a href="edit_daily_report.php?id=<?php echo $row['report_id']; ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="delete_daily_report.php?id=<?php echo $row['report_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this report?');">Delete</a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
