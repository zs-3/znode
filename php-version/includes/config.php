<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'znode_php');
define('DB_USER', 'root');
define('DB_PASS', '');

// MOFH Configuration
define('MOFH_API_USER', '');
define('MOFH_API_PASS', '');
define('MOFH_CPANEL_URL', 'https://cpanel.byethost.com');

// App Settings
define('SITE_NAME', 'ZNode PHP');
define('BASE_URL', '/php-version');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
