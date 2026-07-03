<?php

/**
 * AJAX endpoint for testing database connection
 */
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$host = isset($_POST['db_host']) ? trim($_POST['db_host']) : '';
$port = isset($_POST['db_port']) ? trim($_POST['db_port']) : '3306';
$database = isset($_POST['db_name']) ? trim($_POST['db_name']) : '';
$username = isset($_POST['db_user']) ? trim($_POST['db_user']) : '';
$password = isset($_POST['db_pass']) ? $_POST['db_pass'] : '';

if (empty($host) || empty($database) || empty($username)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

try {
    $dsn = "mysql:host={$host};port={$port}";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Try to create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Test connection to the specific database
    $dsn = "mysql:host={$host};port={$port};dbname={$database}";
    $pdo = new PDO($dsn, $username, $password);

    echo json_encode(['success' => true, 'message' => 'Connection successful! Database is ready.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: '.$e->getMessage()]);
}
