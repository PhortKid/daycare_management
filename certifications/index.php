<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','babysitter'])) {
    header('Location: ../login.php');
    exit();
}
include '../includes/header.php';

require_once '../config/config.php';

// Babysitters see only their certifications
$where = '';
if ($_SESSION['role'] === 'babysitter') {
    $babysitter_id = intval($_SESSION['user_id']);
    $where = "WHERE babysitter_id = $babysitter_id";
}
$sql = "SELECT * FROM certifications $where ORDER BY issue_date DESC";
$result = mysqli_query($conn, $sql);
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Certifications</h1>
    <a href="certifications/add_certification.php" class="btn btn-primary mb-3">Add Certification</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Issuing Organization</th>
                <th>Issue Date</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['issuing_organization']); ?></td>
                <td><?php echo $row['issue_date']; ?></td>
                <td><?php echo $row['expiry_date']; ?></td>
                <td>
                    <a href="certifications/edit_certification.php?id=<?php echo $row['certification_id']; ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="certifications/delete_certification.php?id=<?php echo $row['certification_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this certification?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
