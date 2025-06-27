<?php
// config.php

// Enable error reporting (only during development)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set your timezone
date_default_timezone_set('Africa/Nairobi');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'daycare_management');
define('DB_USER', 'root');
define('DB_PASS', ''); // Set your password here

// DSN for PDO
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

// Create database connection using PDO
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
        PDO::ATTR_EMULATE_PREPARES => false               
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Optional: Define site-wide constants
define('SITE_NAME', 'Daycare Management System');
define('BASE_URL', 'http://localhost/daycare'); 


//or i use conn

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
