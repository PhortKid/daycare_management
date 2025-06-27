<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';
include '../includes/header.php';


$parent_id = intval($_SESSION['user_id']);
$sql = "SELECT dr.*, c.first_name AS child_first, c.last_name AS child_last, u.first_name AS babysitter_first, u.last_name AS babysitter_last
        FROM daily_reports dr
        JOIN children c ON dr.child_id = c.child_id
        JOIN users u ON dr.babysitter_id = u.user_id
        WHERE c.parent_id = ?
        ORDER BY dr.report_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$parent_id]);
$reports = $stmt->fetchAll();
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">My Child Daily Reports</h1>
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
            </tr>
        </thead>
        <tbody>
            <?php foreach($reports as $row): ?>
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
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
