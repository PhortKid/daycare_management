<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','parent'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (isset($_GET['id'])) {
    $child_id = intval($_GET['id']);
    $sql = "DELETE FROM children WHERE child_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $child_id);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: children.php?msg=deleted');
        exit();
    } else {
        header('Location: children.php?msg=error');
        exit();
    }
} else {
    header('Location: children.php');
    exit();
}
