<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: login.php');
    exit();
}
require_once '../config/config.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $sql = "DELETE FROM users WHERE user_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: users.php?msg=deleted');
        exit();
    } else {
        header('Location: users.php?msg=error');
        exit();
    }
} else {
    header('Location: users.php');
    exit();
}
