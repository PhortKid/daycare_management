<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (isset($_GET['id'])) {
    $payment_id = intval($_GET['id']);
    $sql = "DELETE FROM payments WHERE payment_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $payment_id);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: payments.php?msg=deleted');
        exit();
    } else {
        header('Location: payments.php?msg=error');
        exit();
    }
} else {
    header('Location: payments.php');
    exit();
}
