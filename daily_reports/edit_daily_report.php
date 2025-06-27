<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','babysitter'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    header('Location: daily_reports.php');
    exit();
}
$report_id = intval($_GET['id']);
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $child_id = intval($_POST['child_id']);
    $report_date = $_POST['report_date'];
    $meals = trim($_POST['meals']);
    $nap_duration = trim($_POST['nap_duration']);
    $sleep_quality = intval($_POST['sleep_quality']);
    $activities = trim($_POST['activities']);
    $mood = $_POST['mood'];
    $health_notes = trim($_POST['health_notes']);

    $sql = "UPDATE daily_reports SET child_id=?, report_date=?, meals=?, nap_duration=?, sleep_quality=?, activities=?, mood=?, health_notes=? WHERE report_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'issssissi', $child_id, $report_date, $meals, $nap_duration, $sleep_quality, $activities, $mood, $health_notes, $report_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Daily report updated successfully!';
    } else {
        $error = 'Error updating report: ' . mysqli_error($conn);
    }
}
// Fetch report data
$sql = "SELECT * FROM daily_reports WHERE report_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $report_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$report = mysqli_fetch_assoc($result);
if (!$report) {
    header('Location: daily_reports.php');
    exit();
}
$children = mysqli_query($conn, "SELECT child_id, first_name, last_name FROM children");
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Daily Report</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Child</label>
                <select name="child_id" class="form-control" required>
                    <?php while($c = mysqli_fetch_assoc($children)): ?>
                        <option value="<?php echo $c['child_id']; ?>" <?php if($report['child_id']==$c['child_id']) echo 'selected'; ?>><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Date</label>
                <input type="date" name="report_date" class="form-control" value="<?php echo $report['report_date']; ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Meals</label>
                <textarea name="meals" class="form-control"><?php echo htmlspecialchars($report['meals']); ?></textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label>Nap Duration</label>
                <input type="text" name="nap_duration" class="form-control" value="<?php echo htmlspecialchars($report['nap_duration']); ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Sleep Quality (1-5)</label>
                <input type="number" name="sleep_quality" class="form-control" min="1" max="5" value="<?php echo $report['sleep_quality']; ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Activities</label>
                <textarea name="activities" class="form-control"><?php echo htmlspecialchars($report['activities']); ?></textarea>
            </div>
            <div class="col-md-4 mb-3">
                <label>Mood</label>
                <select name="mood" class="form-control" required>
                    <option value="happy" <?php if($report['mood']=='happy') echo 'selected'; ?>>Happy</option>
                    <option value="playful" <?php if($report['mood']=='playful') echo 'selected'; ?>>Playful</option>
                    <option value="calm" <?php if($report['mood']=='calm') echo 'selected'; ?>>Calm</option>
                    <option value="sleepy" <?php if($report['mood']=='sleepy') echo 'selected'; ?>>Sleepy</option>
                    <option value="sad" <?php if($report['mood']=='sad') echo 'selected'; ?>>Sad</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label>Health Notes</label>
            <textarea name="health_notes" class="form-control"><?php echo htmlspecialchars($report['health_notes']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Report</button>
        <a href="daily_reports.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
