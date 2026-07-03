<?php

/**
 * Gego K12 - Visual Installation Wizard
 *
 * A standalone PHP installer that works without Laravel
 * Handles: Requirements check, Database setup, Primary configuration
 *
 * @version 1.1
 */
session_start();

// Define base paths
define('BASE_PATH', dirname(dirname(__DIR__)));
define('PUBLIC_PATH', dirname(__DIR__));
define('INSTALLER_PATH', __DIR__);

// Get current step
$step = isset($_GET['step']) ? (int) $_GET['step'] : 1;

// Check if already installed
$isInstalled = file_exists(BASE_PATH.'/storage/installed');
$allowCompleteScreen = ($step === 6 && ! empty($_SESSION['installer_finalized']));

if ($step === 6 && ! $isInstalled) {
    header('Location: ?step=1');
    exit;
}

if ($isInstalled && ! $allowCompleteScreen) {
    header('Location: ../');
    exit;
}
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once INSTALLER_PATH.'/includes/functions.php';

    switch ($step) {
        case 2:
            // Requirements check - just proceed
            header('Location: ?step=3');
            exit;
            break;

        case 3:
            // Database configuration
            $result = saveDatabaseConfig($_POST);
            if ($result['success']) {
                $_SESSION['db_configured'] = true;
                header('Location: ?step=4');
                exit;
            } else {
                $error = $result['message'];
            }
            break;

        case 4:
            // Admin setup
            $result = saveAdminConfig($_POST);
            if ($result['success']) {
                $_SESSION['admin_configured'] = true;

                // Create .env file immediately after saving config
                $envResult = createEnvFile();
                if (! $envResult['success']) {
                    $error = $envResult['message'];
                } else {
                    header('Location: ?step=5');
                    exit;
                }
            } else {
                $error = $result['message'];
            }
            break;

        case 5:
            // Automated installation handled via AJAX - no POST action needed here
            break;
    }
}

// Include the view
require_once INSTALLER_PATH.'/includes/header.php';

switch ($step) {
    case 1:
        require_once INSTALLER_PATH.'/views/welcome.php';
        break;
    case 2:
        require_once INSTALLER_PATH.'/includes/functions.php';
        require_once INSTALLER_PATH.'/views/requirements.php';
        break;
    case 3:
        require_once INSTALLER_PATH.'/views/database.php';
        break;
    case 4:
        require_once INSTALLER_PATH.'/views/admin.php';
        break;
    case 5:
        // Create .env file before showing the install page
        require_once INSTALLER_PATH.'/includes/functions.php';
        $envResult = createEnvFile();
        if (! $envResult['success']) {
            $error = $envResult['message'];
        }
        require_once INSTALLER_PATH.'/views/install.php';
        break;
    case 6:
        require_once INSTALLER_PATH.'/views/complete.php';
        unset($_SESSION['installer_finalized']);
        break;
    default:
        require_once INSTALLER_PATH.'/views/welcome.php';
}

require_once INSTALLER_PATH.'/includes/footer.php';
