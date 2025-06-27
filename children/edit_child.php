<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','parent'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    header('Location: children.php');
    exit();
}
$child_id = intval($_GET['id']);
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $medical_notes = trim($_POST['medical_notes']);
    $allergies = trim($_POST['allergies']);
    $enrollment_date = $_POST['enrollment_date'];
    $teacher_id = $_POST['teacher_id'] ? intval($_POST['teacher_id']) : null;
    $parent_id = $_SESSION['role'] === 'parent' ? intval($_SESSION['user_id']) : intval($_POST['parent_id']);

    $sql = "UPDATE children SET parent_id=?, first_name=?, last_name=?, date_of_birth=?, gender=?, medical_notes=?, allergies=?, enrollment_date=?, teacher_id=? WHERE child_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'issssssssi', $parent_id, $first_name, $last_name, $date_of_birth, $gender, $medical_notes, $allergies, $enrollment_date, $teacher_id, $child_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Child updated successfully!';
    } else {
        $error = 'Error updating child: ' . mysqli_error($conn);
    }
}
// Fetch child data
$sql = "SELECT * FROM children WHERE child_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $child_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$child = mysqli_fetch_assoc($result);
if (!$child) {
    header('Location: children.php');
    exit();
}
$parents = mysqli_query($conn, "SELECT user_id, first_name, last_name FROM users WHERE role='parent'");
$teachers = mysqli_query($conn, "SELECT user_id, first_name, last_name FROM users WHERE role='babysitter' OR role='headteacher'");
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Child</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($child['first_name']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($child['last_name']); ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" value="<?php echo $child['date_of_birth']; ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="male" <?php if($child['gender']=='male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if($child['gender']=='female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if($child['gender']=='other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Enrollment Date</label>
                <input type="date" name="enrollment_date" class="form-control" value="<?php echo $child['enrollment_date']; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Medical Notes</label>
                <textarea name="medical_notes" class="form-control"><?php echo htmlspecialchars($child['medical_notes']); ?></textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label>Allergies</label>
                <textarea name="allergies" class="form-control"><?php echo htmlspecialchars($child['allergies']); ?></textarea>
            </div>
        </div>
        <div class="row">
            <?php if ($_SESSION['role'] !== 'parent'): ?>
            <div class="col-md-6 mb-3">
                <label>Parent</label>
                <select name="parent_id" class="form-control" required>
                    <?php while($p = mysqli_fetch_assoc($parents)): ?>
                        <option value="<?php echo $p['user_id']; ?>" <?php if($child['parent_id']==$p['user_id']) echo 'selected'; ?>><?php echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-6 mb-3">
                <label>Teacher</label>
                <select name="teacher_id" class="form-control">
                    <option value="">-- None --</option>
                    <?php while($t = mysqli_fetch_assoc($teachers)): ?>
                        <option value="<?php echo $t['user_id']; ?>" <?php if($child['teacher_id']==$t['user_id']) echo 'selected'; ?>><?php echo htmlspecialchars($t['first_name'] . ' ' . $t['last_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Child</button>
        <a href="children.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
