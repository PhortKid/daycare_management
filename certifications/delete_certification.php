<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','headteacher','babysitter'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/config.php';

if (isset($_GET['id'])) {
    $certification_id = intval($_GET['id']);
    $sql = "DELETE FROM certifications WHERE certification_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $certification_id);
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
