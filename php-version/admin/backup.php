<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_admin()) {
    header("Location: ../login.php");
    exit;
}

$success = '';
if (isset($_POST['backup'])) {
    verify_csrf();

    // Simplistic backup logic: Export tables to JSON
    $tables = ['users', 'hostings', 'tickets', 'ticket_replies', 'forum_channels', 'forum_posts', 'forum_comments'];
    $data = [];

    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT * FROM $table");
        $data[$table] = $stmt->fetchAll();
    }

    $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.json';
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="' . $backup_file . '"');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

$page_title = "Backup & Restore";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Backup & Restore</h1>

            <?php if ($success): ?>
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-lg flex items-center gap-3">
                    <i class="lucide-check-circle w-5 h-5"></i>
                    <span><?php echo e($success); ?></span>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card p-8 text-center space-y-6">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 flex items-center justify-center mx-auto">
                        <i class="lucide-database w-8 h-8 text-indigo-400"></i>
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-xl font-bold">Database Backup</h2>
                        <p class="text-gray-400 text-sm">Download a full snapshot of your users, hosting accounts, and community data in JSON format.</p>
                    </div>
                    <form action="backup.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                        <button type="submit" name="backup" class="btn-primary w-full justify-center">
                            Run Backup Now
                        </button>
                    </form>
                </div>

                <div class="card p-8 text-center space-y-6 opacity-50">
                    <div class="w-16 h-16 rounded-2xl bg-amber-500/10 flex items-center justify-center mx-auto">
                        <i class="lucide-upload w-8 h-8 text-amber-400"></i>
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-xl font-bold">Restore System</h2>
                        <p class="text-gray-400 text-sm">Upload a backup file to restore your entire system to a previous state.</p>
                    </div>
                    <button disabled class="px-4 py-2 rounded-lg bg-[#1E293B] text-gray-500 text-sm font-bold w-full">Coming Soon</button>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
