<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','babysitter'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $child_id = intval($_POST['child_id']);
    $babysitter_id = $_SESSION['user_id'];
    $report_date = $_POST['report_date'];
    $meals = trim($_POST['meals']);
    $nap_duration = trim($_POST['nap_duration']);
    $sleep_quality = intval($_POST['sleep_quality']);
    $activities = trim($_POST['activities']);
    $mood = $_POST['mood'];
    $health_notes = trim($_POST['health_notes']);

    $sql = "INSERT INTO daily_reports (child_id, babysitter_id, report_date, meals, nap_duration, sleep_quality, activities, mood, health_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iisssisss', $child_id, $babysitter_id, $report_date, $meals, $nap_duration, $sleep_quality, $activities, $mood, $health_notes);
    if (mysqli_stmt_execute($stmt)) {
        // Fetch child and parent info for SMS
        $child_sql = "SELECT c.first_name AS child_name, u.phone AS parent_phone FROM children c JOIN users u ON c.parent_id = u.user_id WHERE c.child_id = ?";
        $child_stmt = mysqli_prepare($conn, $child_sql);
        mysqli_stmt_bind_param($child_stmt, 'i', $child_id);
        mysqli_stmt_execute($child_stmt);
        $child_result = mysqli_stmt_get_result($child_stmt);
        $child_info = mysqli_fetch_assoc($child_result);

        if ($child_info && !empty($child_info['parent_phone'])) {
            // Prepare SMS message
            $sms_message ="The daily report for your child, ".$child_info['child_name'].", has been successfully submitted. Thank you for staying connected!" ;
            // Call SMS API
            $sms_api_url = 'https://verifytra.site';
            $sms_payload = json_encode([
                "token" => "dms-friday-2025",
                "phonenumber" => $child_info['parent_phone'],
                "message" => $sms_message
            ]);
            $ch = curl_init($sms_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $sms_payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);
        }

        $success = 'Daily report added successfully!';
    } else {
        $error = 'Error adding report: ' . mysqli_error($conn);
    }
}
// Fetch children for dropdown
$children = mysqli_query($conn, "SELECT child_id, first_name, last_name FROM children");
?>
<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add Daily Report</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Child</label>
                <select name="child_id" class="form-control" required>
                    <?php while($c = mysqli_fetch_assoc($children)): ?>
                        <option value="<?php echo $c['child_id']; ?>"><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Date</label>
                <input type="date" name="report_date" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Meals</label>
                <textarea name="meals" class="form-control"></textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label>Nap Duration</label>
                <input type="text" name="nap_duration" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Sleep Quality (1-5)</label>
                <input type="number" name="sleep_quality" class="form-control" min="1" max="5" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Activities</label>
                <textarea name="activities" class="form-control"></textarea>
            </div>
            <div class="col-md-4 mb-3">
                <label>Mood</label>
                <select name="mood" class="form-control" required>
                    <option value="happy">Happy</option>
                    <option value="playful">Playful</option>
                    <option value="calm">Calm</option>
                    <option value="sleepy">Sleepy</option>
                    <option value="sad">Sad</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label>Health Notes</label>
            <textarea name="health_notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Report</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
