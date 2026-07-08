<?php

/**
 * AJAX endpoint for running installation steps
 */
session_start();

header('Content-Type: application/json');

// Define base paths
define('BASE_PATH', dirname(dirname(__DIR__)));
define('PUBLIC_PATH', dirname(__DIR__));
define('INSTALLER_PATH', __DIR__);

require_once INSTALLER_PATH.'/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$step = isset($_POST['step']) ? $_POST['step'] : '';

if (empty($step)) {
    echo json_encode(['success' => false, 'message' => 'No step specified']);
    exit;
}

// Run the installation step
$result = runInstallationStep($step);

echo json_encode($result);
