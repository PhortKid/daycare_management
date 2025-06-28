<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Delete children first
    $sql_children = "DELETE FROM children WHERE parent_id=?";
    $stmt_children = mysqli_prepare($conn, $sql_children);
    mysqli_stmt_bind_param($stmt_children, 'i', $user_id);
    mysqli_stmt_execute($stmt_children);

    // Then delete parent
    $sql = "DELETE FROM users WHERE user_id=? AND role='parent'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php?msg=deleted');
        exit();
    } else {
        header('Location: index.php?msg=error');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
