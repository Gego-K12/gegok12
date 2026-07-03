<?php

/**
 * Installation Helper Functions
 */

/**
 * Check system requirements
 */
function checkRequirements()
{
    $requirements = [
        'php' => [
            'name' => 'PHP Version',
            'required' => '8.4.0',
            'current' => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, '8.4.0', '>='),
            'type' => 'php',
        ],
        'pdo' => [
            'name' => 'PDO Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('pdo') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('pdo'),
            'type' => 'extension',
        ],
        'pdo_mysql' => [
            'name' => 'PDO MySQL Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('pdo_mysql') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('pdo_mysql'),
            'type' => 'extension',
        ],
        'mbstring' => [
            'name' => 'Mbstring Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('mbstring') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('mbstring'),
            'type' => 'extension',
        ],
        'openssl' => [
            'name' => 'OpenSSL Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('openssl') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('openssl'),
            'type' => 'extension',
        ],
        'tokenizer' => [
            'name' => 'Tokenizer Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('tokenizer') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('tokenizer'),
            'type' => 'extension',
        ],
        'json' => [
            'name' => 'JSON Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('json') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('json'),
            'type' => 'extension',
        ],
        'curl' => [
            'name' => 'cURL Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('curl') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('curl'),
            'type' => 'extension',
        ],
        'fileinfo' => [
            'name' => 'Fileinfo Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('fileinfo') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('fileinfo'),
            'type' => 'extension',
        ],
        'gd' => [
            'name' => 'GD Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('gd') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('gd'),
            'type' => 'extension',
        ],
        'bcmath' => [
            'name' => 'BCMath Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('bcmath') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('bcmath'),
            'type' => 'extension',
        ],
        'xml' => [
            'name' => 'XML Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('xml') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('xml'),
            'type' => 'extension',
        ],
        'zip' => [
            'name' => 'Zip Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('zip') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('zip'),
            'type' => 'extension',
        ],
    ];

    return $requirements;
}

/**
 * Check folder permissions
 */
function checkPermissions()
{
    $folders = [
        'storage' => BASE_PATH.'/storage',
        'storage/app' => BASE_PATH.'/storage/app',
        'storage/framework' => BASE_PATH.'/storage/framework',
        'storage/framework/cache' => BASE_PATH.'/storage/framework/cache',
        'storage/framework/sessions' => BASE_PATH.'/storage/framework/sessions',
        'storage/framework/views' => BASE_PATH.'/storage/framework/views',
        'storage/logs' => BASE_PATH.'/storage/logs',
        'bootstrap/cache' => BASE_PATH.'/bootstrap/cache',
        'public/uploads' => PUBLIC_PATH.'/uploads',
    ];

    $permissions = [];
    foreach ($folders as $name => $path) {
        $permissions[$name] = [
            'name' => $name,
            'path' => $path,
            'required' => '775',
            'status' => is_writable($path),
            'current' => is_writable($path) ? 'Writable' : 'Not Writable',
        ];
    }

    return $permissions;
}

/**
 * Test database connection
 */
function testDatabaseConnection($host, $port, $database, $username, $password)
{
    try {
        $dsn = "mysql:host={$host};port={$port}";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Try to create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Test connection to the specific database
        $dsn = "mysql:host={$host};port={$port};dbname={$database}";
        $pdo = new PDO($dsn, $username, $password);

        return ['success' => true, 'message' => 'Connection successful'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Save database configuration to .env file
 */
function saveDatabaseConfig($data)
{
    // Validate inputs
    $required = ['db_host', 'db_port', 'db_name', 'db_user'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return ['success' => false, 'message' => "Field {$field} is required"];
        }
    }

    $host = trim($data['db_host']);
    $port = trim($data['db_port']);
    $database = trim($data['db_name']);
    $username = trim($data['db_user']);
    $password = isset($data['db_pass']) ? $data['db_pass'] : '';

    // Test connection
    $test = testDatabaseConnection($host, $port, $database, $username, $password);
    if (! $test['success']) {
        return ['success' => false, 'message' => 'Database connection failed: '.$test['message']];
    }

    // Store in session for later use
    $_SESSION['db_config'] = [
        'host' => $host,
        'port' => $port,
        'database' => $database,
        'username' => $username,
        'password' => $password,
    ];

    return ['success' => true, 'message' => 'Database configuration saved'];
}

/**
 * Save admin configuration
 */
function saveAdminConfig($data)
{
    $required = ['app_name', 'app_url', 'admin_email', 'admin_password', 'admin_password_confirm'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return ['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)).' is required'];
        }
    }

    // Validate email format
    $email = trim($data['admin_email']);
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Please enter a valid email address'];
    }

    // Validate password
    $password = $data['admin_password'];
    if (strlen($password) < 8) {
        return ['success' => false, 'message' => 'Password must be at least 8 characters long'];
    }

    // Check password confirmation
    if ($password !== $data['admin_password_confirm']) {
        return ['success' => false, 'message' => 'Passwords do not match'];
    }

    $_SESSION['admin_config'] = [
        'app_name' => trim($data['app_name']),
        'app_url' => rtrim(trim($data['app_url']), '/'),
        'timezone' => isset($data['timezone']) ? $data['timezone'] : 'UTC',
        'admin_email' => $email,
        'admin_password' => $password,
    ];

    return ['success' => true, 'message' => 'Application configuration saved'];
}

/**
 * Update school and admin user credentials after seeding
 * For single-school version: updates School info and SchoolAdmin (usergroup_id=3)
 */
function updateAdminCredentials()
{
    if (! isset($_SESSION['admin_config']['admin_email']) || ! isset($_SESSION['admin_config']['admin_password'])) {
        return ['success' => false, 'message' => 'Admin credentials not found in session'];
    }

    if (! isset($_SESSION['db_config'])) {
        return ['success' => false, 'message' => 'Database configuration not found'];
    }

    $db = $_SESSION['db_config'];
    $email = $_SESSION['admin_config']['admin_email'];
    $password = password_hash($_SESSION['admin_config']['admin_password'], PASSWORD_BCRYPT);
    $schoolName = $_SESSION['admin_config']['app_name'] ?? 'My School';

    try {
        $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}";
        $pdo = new PDO($dsn, $db['username'], $db['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Generate slug from school name
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $schoolName)));

        // 1. Update school with ID 1 (the seeded default school)
        $stmt = $pdo->prepare('UPDATE schools SET name = ?, email = ?, slug = ?, updated_at = NOW() WHERE id = 1');
        $stmt->execute([$schoolName, $email, $slug]);

        // 2. Update SchoolAdmin user (usergroup_id = 3, school_id = 1)
        $stmt = $pdo->prepare('UPDATE users SET email = ?, password = ?, name = ?, updated_at = NOW() WHERE school_id = 1 AND usergroup_id = 3');
        $stmt->execute([$email, $password, 'Admin']);

        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'School and admin credentials updated'];
        } else {
            return ['success' => false, 'message' => 'No school admin user found'];
        }

    } catch (PDOException $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Generate random key for Laravel
 */
function generateAppKey()
{
    return 'base64:'.base64_encode(random_bytes(32));
}

/**
 * Run the full installation
 */
function runInstallation()
{
    if (! isset($_SESSION['db_config']) || ! isset($_SESSION['admin_config'])) {
        return ['success' => false, 'message' => 'Configuration missing. Please start over.'];
    }

    $db = $_SESSION['db_config'];
    $admin = $_SESSION['admin_config'];

    try {
        // 1. Create .env file
        $envContent = createEnvContent($db, $admin);
        if (file_put_contents(BASE_PATH.'/.env', $envContent) === false) {
            return ['success' => false, 'message' => 'Could not write .env file'];
        }

        // 2. Create storage directories if they don't exist
        createStorageDirectories();

        // 3. Create installed marker file
        file_put_contents(BASE_PATH.'/storage/installed', date('Y-m-d H:i:s'));

        // Clear session
        unset($_SESSION['db_config']);
        unset($_SESSION['admin_config']);

        return ['success' => true, 'message' => 'Installation completed successfully'];

    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Installation failed: '.$e->getMessage()];
    }
}

/**
 * Create .env file only (for step 5, before automated install)
 */
function createEnvFile()
{
    if (! isset($_SESSION['db_config']) || ! isset($_SESSION['admin_config'])) {
        return ['success' => false, 'message' => 'Configuration missing. Please start over.'];
    }

    $db = $_SESSION['db_config'];
    $admin = $_SESSION['admin_config'];

    try {
        // Check if .env already exists
        if (file_exists(BASE_PATH.'/.env')) {
            return ['success' => true, 'message' => '.env file already exists'];
        }

        // Create .env file
        $envContent = createEnvContent($db, $admin);
        if (file_put_contents(BASE_PATH.'/.env', $envContent) === false) {
            return ['success' => false, 'message' => 'Could not write .env file. Check directory permissions.'];
        }

        // Create storage directories if they don't exist
        createStorageDirectories();

        return ['success' => true, 'message' => '.env file created successfully'];

    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to create .env: '.$e->getMessage()];
    }
}

/**
 * Create .env file content
 */
function createEnvContent($db, $admin)
{
    $appKey = generateAppKey();

    $env = <<<ENV
APP_NAME="{$admin['app_name']}"
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=false
APP_URL={$admin['app_url']}

LOG_CHANNEL=daily

DB_CONNECTION=mysql
DB_HOST={$db['host']}
DB_PORT={$db['port']}
DB_DATABASE={$db['database']}
DB_USERNAME={$db['username']}
DB_PASSWORD={$db['password']}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

REMINDER=mail

MAIL_FROM_ADDRESS=admin@school.com
MAIL_STATUS=on

SMS_GATEWAY=MSG91
REMINDER_API_KEY=
REMINDER_SENDER_ID=""
REMINDER_ROUTE_NO=4

SMS_STATUS=off

FCM_SERVER_KEY=
FCM_SENDER_ID=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"

FILESYSTEM_DRIVER=local

TIMEZONE={$admin['timezone']}

DEBUGBAR_ENABLED=false

AWS_KEY=
AWS_SECRET=
AWS_REGION=
AWS_BUCKET=
AWS_ENDPOINT=

GOOGLE_RECAPTCHA_KEY=
GOOGLE_RECAPTCHA_SECRET=

SCOUT_QUEUE=true

ALGOLIA_APP_ID=
ALGOLIA_SECRET=

TWILIO_SID=
TWILIO_TOKEN=
TWILIO_KEY=
TWILIO_SECRET=

FCM_TEACHER_SERVER_KEY=""
FCM_TEACHER_SENDER_ID=

CACHE_TIME=8400

SNOOZE_TIME=600
FIREBASE_CREDENTIALS=''

ADDON_API_URL=
ENV;

    return $env;
}

/**
 * Create necessary storage directories
 */
function createStorageDirectories()
{
    $directories = [
        BASE_PATH.'/storage',
        BASE_PATH.'/storage/app',
        BASE_PATH.'/storage/app/public',
        BASE_PATH.'/storage/framework',
        BASE_PATH.'/storage/framework/cache',
        BASE_PATH.'/storage/framework/cache/data',
        BASE_PATH.'/storage/framework/sessions',
        BASE_PATH.'/storage/framework/views',
        BASE_PATH.'/storage/logs',
        BASE_PATH.'/bootstrap/cache',
    ];

    foreach ($directories as $dir) {
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }
}

/**
 * Get common timezones
 */
function getTimezones()
{
    return [
        'UTC' => 'UTC',
        'Asia/Kolkata' => 'Asia/Kolkata (IST)',
        'Asia/Dubai' => 'Asia/Dubai (GST)',
        'Asia/Singapore' => 'Asia/Singapore (SGT)',
        'Asia/Tokyo' => 'Asia/Tokyo (JST)',
        'Asia/Shanghai' => 'Asia/Shanghai (CST)',
        'Europe/London' => 'Europe/London (GMT/BST)',
        'Europe/Paris' => 'Europe/Paris (CET)',
        'Europe/Berlin' => 'Europe/Berlin (CET)',
        'America/New_York' => 'America/New_York (EST)',
        'America/Chicago' => 'America/Chicago (CST)',
        'America/Denver' => 'America/Denver (MST)',
        'America/Los_Angeles' => 'America/Los_Angeles (PST)',
        'Australia/Sydney' => 'Australia/Sydney (AEST)',
        'Pacific/Auckland' => 'Pacific/Auckland (NZST)',
    ];
}

/**
 * Check if a command exists
 */
function commandExists($command)
{
    $whereIsCommand = PHP_OS_FAMILY === 'Windows' ? 'where' : 'which';
    $process = proc_open(
        "$whereIsCommand $command",
        [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ],
        $pipes
    );
    if ($process !== false) {
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return ! empty(trim($stdout));
    }

    return false;
}

/**
 * Check installation tools availability
 */
function checkInstallationTools()
{
    return [
        'composer' => [
            'name' => 'Composer',
            'available' => commandExists('composer'),
            'required' => true,
        ],
        'node' => [
            'name' => 'Node.js',
            'available' => commandExists('node'),
            'required' => false,
        ],
        'npm' => [
            'name' => 'NPM',
            'available' => commandExists('npm'),
            'required' => false,
        ],
        'php' => [
            'name' => 'PHP CLI',
            'available' => commandExists('php'),
            'required' => true,
        ],
    ];
}

/**
 * Run a shell command and return the output
 */
function runCommand($command, $cwd = null)
{
    $cwd = $cwd ?: BASE_PATH;

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open($command, $descriptors, $pipes, $cwd);

    if ($process === false) {
        return ['success' => false, 'output' => 'Failed to start process', 'error' => ''];
    }

    fclose($pipes[0]);

    $output = stream_get_contents($pipes[1]);
    $error = stream_get_contents($pipes[2]);

    fclose($pipes[1]);
    fclose($pipes[2]);

    $exitCode = proc_close($process);

    return [
        'success' => $exitCode === 0,
        'output' => $output,
        'error' => $error,
        'exit_code' => $exitCode,
    ];
}

/**
 * Run installation step
 */
function runInstallationStep($step)
{
    $result = ['success' => false, 'message' => '', 'output' => ''];

    switch ($step) {
        case 'composer':
            $result = runCommand('composer install --optimize-autoloader --no-interaction 2>&1');
            $result['message'] = $result['success'] ? 'Composer packages installed successfully' : 'Failed to install Composer packages';
            break;

        case 'npm':
            if (commandExists('npm')) {
                $result = runCommand('npm install 2>&1');
                $result['message'] = $result['success'] ? 'NPM packages installed successfully' : 'Failed to install NPM packages';
            } else {
                $result = ['success' => true, 'message' => 'NPM not available, skipping...', 'output' => ''];
            }
            break;

        case 'npm_build':
            if (commandExists('npm') && file_exists(BASE_PATH.'/node_modules')) {
                // Use full path to cross-env to avoid PATH issues
                // $result = runCommand('node_modules/.bin/cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js 2>&1');
                // new
                $result = runCommand('node_modules/.bin/cross-env NODE_ENV=production npm run production 2>&1');

                $result['message'] = $result['success'] ? 'Assets compiled successfully' : 'Failed to compile assets';
            } else {
                $result = ['success' => true, 'message' => 'NPM not available or node_modules missing, skipping...', 'output' => ''];
            }
            break;

        case 'key_generate':
            $result = runCommand('php artisan key:generate --force 2>&1');
            $result['message'] = $result['success'] ? 'Application key generated' : 'Failed to generate application key';
            break;

        case 'storage_link':
            $result = runCommand('php artisan storage:link 2>&1');
            $result['message'] = $result['success'] ? 'Storage link created' : 'Failed to create storage link';
            break;

        case 'migrate':
            // Clear any cached config first to ensure .env is read fresh
            runCommand('php artisan config:clear 2>&1');
            $result = runCommand('php artisan migrate:fresh --force 2>&1');
            $result['message'] = $result['success'] ? 'Database migrated successfully' : 'Failed to run migrations';
            break;

        case 'seed':
            $result = runCommand('php artisan db:seed --force 2>&1');
            if ($result['success']) {
                // Update admin user credentials after seeding
                $adminUpdate = updateAdminCredentials();
                if (! $adminUpdate['success']) {
                    $result['message'] = 'Database seeded but failed to update admin: '.$adminUpdate['message'];
                } else {
                    $result['message'] = 'Database seeded and admin account updated successfully';
                }
            } else {
                $result['message'] = 'Failed to seed database';
            }
            break;

        case 'cache':
            runCommand('php artisan config:cache 2>&1');
            runCommand('php artisan route:cache 2>&1');
            runCommand('php artisan view:cache 2>&1');
            $result = ['success' => true, 'message' => 'Cache cleared and rebuilt', 'output' => ''];
            break;

        case 'finalize':
            // Create installed marker
            file_put_contents(BASE_PATH.'/storage/installed', date('Y-m-d H:i:s'));
            $_SESSION['installer_finalized'] = true;
            $result = ['success' => true, 'message' => 'Installation finalized', 'output' => ''];
            break;

        default:
            $result = ['success' => false, 'message' => 'Unknown installation step', 'output' => ''];
    }

    return $result;
}

/**
 * Get all installation steps
 */
function getInstallationSteps()
{
    return [
        'composer' => [
            'name' => 'Installing Composer Dependencies',
            'description' => 'Installing PHP packages via Composer',
        ],
        'key_generate' => [
            'name' => 'Generating Application Key',
            'description' => 'Creating secure encryption key',
        ],
        'storage_link' => [
            'name' => 'Creating Storage Link',
            'description' => 'Linking storage to public directory',
        ],
        'migrate' => [
            'name' => 'Running Database Migrations',
            'description' => 'Creating database tables',
        ],
        'seed' => [
            'name' => 'Seeding Database',
            'description' => 'Adding initial data and admin account',
        ],
        'npm' => [
            'name' => 'Installing NPM Dependencies',
            'description' => 'Installing Node.js packages (optional)',
        ],
        'npm_build' => [
            'name' => 'Compiling Assets',
            'description' => 'Building CSS and JavaScript (optional)',
        ],
        'cache' => [
            'name' => 'Optimizing Application',
            'description' => 'Caching configuration and routes',
        ],
        'finalize' => [
            'name' => 'Finalizing Installation',
            'description' => 'Completing setup process',
        ],
    ];
}
