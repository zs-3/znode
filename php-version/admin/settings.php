<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_admin()) {
    header("Location: ../login.php");
    exit;
}

$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $mofh_user = $_POST['mofh_user'] ?? MOFH_API_USER;
    $mofh_pass = $_POST['mofh_pass'] ?? MOFH_API_PASS;
    $site_name = $_POST['site_name'] ?? SITE_NAME;
    $smtp_host = $_POST['smtp_host'] ?? '';
    $smtp_port = $_POST['smtp_port'] ?? '';

    $esc = fn($v) => addslashes($v);

    $config_content = "<?php\n" .
        "// Database configuration\n" .
        "define('DB_HOST', '" . $esc(DB_HOST) . "');\n" .
        "define('DB_NAME', '" . $esc(DB_NAME) . "');\n" .
        "define('DB_USER', '" . $esc(DB_USER) . "');\n" .
        "define('DB_PASS', '" . $esc(DB_PASS) . "');\n\n" .
        "// MOFH Configuration\n" .
        "define('MOFH_API_USER', '" . $esc($mofh_user) . "');\n" .
        "define('MOFH_API_PASS', '" . $esc($mofh_pass) . "');\n" .
        "define('MOFH_CPANEL_URL', 'https://cpanel.byethost.com');\n\n" .
        "// App Settings\n" .
        "define('SITE_NAME', '" . $esc($site_name) . "');\n" .
        "define('SITE_LOGO', '" . $esc(SITE_LOGO) . "');\n" .
        "define('BASE_URL', '" . $esc(BASE_URL) . "');\n" .
        "define('APP_ROOT', '" . $esc(APP_ROOT) . "');\n\n" .
        "// SMTP Settings\n" .
        "define('SMTP_HOST', '" . $esc($smtp_host) . "');\n" .
        "define('SMTP_PORT', '" . $esc($smtp_port) . "');\n\n" .
        "// Error reporting\n" .
        "error_reporting(E_ALL);\n" .
        "ini_set('display_errors', 1);\n\n" .
        "// Start session\n" .
        "if (session_status() === PHP_SESSION_NONE) {\n" .
        "    session_start();\n" .
        "}\n";

    if (file_put_contents('../includes/config.php', $config_content)) {
        $success = "Settings updated successfully!";
    } else {
        $error = "Failed to update configuration file. Check permissions.";
    }
}

$page_title = "System Settings";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">System Settings</h1>

            <?php if ($success): ?>
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-lg"><?php echo e($success); ?></div>
            <?php endif; ?>

            <form action="settings.php" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

                <div class="card p-6 space-y-4">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="lucide-settings w-5 h-5 text-indigo-400"></i>
                        General Settings
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Site Name</label>
                            <input type="text" name="site_name" value="<?php echo e(SITE_NAME); ?>" class="input">
                        </div>
                    </div>
                </div>

                <div class="card p-6 space-y-4">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="lucide-api w-5 h-5 text-indigo-400"></i>
                        MOFH API Configuration
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">API Username</label>
                            <input type="text" name="mofh_user" value="<?php echo e(MOFH_API_USER); ?>" class="input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">API Password</label>
                            <input type="password" name="mofh_pass" value="<?php echo e(MOFH_API_PASS); ?>" class="input">
                        </div>
                    </div>
                    <div class="pt-4">
                        <label class="text-sm font-medium text-gray-400">Server IP Address (for MOFH Whitelisting)</label>
                        <div class="flex gap-2 mt-1">
                            <input type="text" value="<?php echo e($_SERVER['SERVER_ADDR'] ?? gethostbyname(gethostname())); ?>" class="input flex-1" readonly id="server-ip">
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('server-ip').value)" class="px-4 py-2 bg-[#1E293B] hover:bg-[#2D3B4E] rounded-lg text-xs font-bold transition-colors">
                                Copy
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1 italic">Note: Use this IP to whitelist your server in the MOFH Reseller Panel.</p>
                    </div>
                </div>

                <div class="card p-6 space-y-4">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="lucide-mail w-5 h-5 text-indigo-400"></i>
                        SMTP Settings
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">SMTP Host</label>
                            <input type="text" name="smtp_host" value="<?php echo e(defined('SMTP_HOST') ? SMTP_HOST : ''); ?>" placeholder="smtp.example.com" class="input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">SMTP Port</label>
                            <input type="number" name="smtp_port" value="<?php echo e(defined('SMTP_PORT') ? SMTP_PORT : ''); ?>" placeholder="587" class="input">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary px-8">Save All Settings</button>
            </form>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
