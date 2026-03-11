<?php
require_once 'includes/config.php';

$message = '';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? '';
    $db_name = $_POST['db_name'] ?? '';
    $db_user = $_POST['db_user'] ?? '';
    $db_pass = $_POST['db_pass'] ?? '';

    $mofh_user = $_POST['mofh_user'] ?? '';
    $mofh_pass = $_POST['mofh_pass'] ?? '';

    $site_name = $_POST['site_name'] ?? 'ZNode PHP';
    $site_logo = $_POST['site_logo'] ?? '';

    // Simple validation
    if (empty($db_host) || empty($db_name) || empty($db_user)) {
        $message = "Database host, name and user are required.";
        $error = true;
    } else {
        // Try to connect to DB
        try {
            $test_pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $test_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Escaping helper
            $esc = fn($v) => addslashes($v);

            // If connected, update config.php
            $config_content = "<?php\n" .
                "// Database configuration\n" .
                "define('DB_HOST', '" . $esc($db_host) . "');\n" .
                "define('DB_NAME', '" . $esc($db_name) . "');\n" .
                "define('DB_USER', '" . $esc($db_user) . "');\n" .
                "define('DB_PASS', '" . $esc($db_pass) . "');\n\n" .
                "// MOFH Configuration\n" .
                "define('MOFH_API_USER', '" . $esc($mofh_user) . "');\n" .
                "define('MOFH_API_PASS', '" . $esc($mofh_pass) . "');\n" .
                "define('MOFH_CPANEL_URL', 'https://cpanel.byethost.com');\n\n" .
                "// App Settings\n" .
                "define('SITE_NAME', '" . $esc($site_name) . "');\n" .
                "define('SITE_LOGO', '" . $esc($site_logo) . "');\n" .
                "define('BASE_URL', '/php-version');\n" .
                "define('APP_ROOT', dirname(__DIR__));\n\n" .
                "// Error reporting\n" .
                "error_reporting(E_ALL);\n" .
                "ini_set('display_errors', 1);\n\n" .
                "// Start session\n" .
                "if (session_status() === PHP_SESSION_NONE) {\n" .
                "    session_start();\n" .
                "}\n";

            if (file_put_contents('includes/config.php', $config_content)) {
                $message = "Installation successful! Configuration saved.";
                // Try to import SQL
                try {
                    $sql = file_get_contents('install.sql');
                    $test_pdo->exec($sql);
                    $message .= " Database tables created successfully.";
                } catch (Exception $e) {
                    $message .= " Note: Could not import install.sql (" . $e->getMessage() . "). Please import it manually.";
                }
            } else {
                $message = "Failed to write to includes/config.php. Check permissions.";
                $error = true;
            }
        } catch (PDOException $e) {
            $message = "Database connection failed: " . $e->getMessage();
            $error = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install - ZNode PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0f172a; color: white; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-slate-900 p-8 rounded-xl shadow-2xl border border-slate-800 w-full max-w-2xl">
        <h1 class="text-3xl font-bold mb-6 text-center text-indigo-500">ZNode PHP Installer</h1>

        <?php if ($message): ?>
            <div class="p-4 mb-6 rounded <?php echo $error ? 'bg-red-900/50 border border-red-500 text-red-200' : 'bg-green-900/50 border border-green-500 text-green-200'; ?>">
                <?php echo $message; ?>
            </div>
            <?php if (!$error): ?>
                <div class="text-center">
                    <a href="index.php" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded transition">Go to Dashboard</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!$message || $error): ?>
        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold border-b border-slate-800 pb-2">Database Settings</h2>
                    <div>
                        <label class="block text-sm font-medium mb-1">DB Host</label>
                        <input type="text" name="db_host" value="localhost" class="w-full bg-slate-800 border border-slate-700 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">DB Name</label>
                        <input type="text" name="db_name" value="znode_php" class="w-full bg-slate-800 border border-slate-700 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">DB User</label>
                        <input type="text" name="db_user" value="root" class="w-full bg-slate-800 border border-slate-700 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">DB Password</label>
                        <input type="password" name="db_pass" class="w-full bg-slate-800 border border-slate-700 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-xl font-semibold border-b border-slate-800 pb-2">MOFH API Settings</h2>
                    <div>
                        <label class="block text-sm font-medium mb-1">API Username</label>
                        <input type="text" name="mofh_user" class="w-full bg-slate-800 border border-slate-700 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">API Password</label>
                        <input type="password" name="mofh_pass" class="w-full bg-slate-800 border border-slate-700 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <h2 class="text-xl font-semibold border-b border-slate-800 pb-2 mt-4">Site Settings</h2>
                    <div>
                        <label class="block text-sm font-medium mb-1">Site Name</label>
                        <input type="text" name="site_name" value="ZNode PHP" class="w-full bg-slate-800 border border-slate-700 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Site Logo URL</label>
                        <input type="text" name="site_logo" placeholder="https://example.com/logo.png" class="w-full bg-slate-800 border border-slate-700 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition shadow-lg shadow-indigo-500/20">
                Install System
            </button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
