<?php
// auth/logout.php
session_start();
session_destroy();
header("Location: ../index.php");
exit();
?>

<?php
// actions/add_child.php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_POST) {
    include_once '../config/database.php';
    include_once '../classes/Child.php';
    
    $database = new Database();
    $db = $database->getConnection();
    $child = new Child($db);
    
    $data = [
        ':parent_id' => 1, // You'll need to implement parent selection
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':date_of_birth' => $_POST['date_of_birth'],
        ':gender' => $_POST['gender'],
        ':medical_notes' => $_POST['medical_notes'],
        ':allergies' => $_POST['allergies'],
        ':enrollment_date' => $_POST['enrollment_date'],
        ':classroom' => $_POST['classroom']
    ];
    
    if ($child->create($data)) {
        $_SESSION['success_message'] = "Child added successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to add child!";
    }
}

header("Location: ../dashboard.php");
exit();
?>