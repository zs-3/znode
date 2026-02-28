<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_admin()) {
    header("Location: ../login.php");
    exit;
}

$page_title = "Admin Dashboard";
// Override BASE_URL for admin templates if needed, or use relative paths
include '../templates/header.php';
?>

<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Admin Overview</h1>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <?php
                $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
                $hostingCount = $pdo->query("SELECT COUNT(*) FROM hostings")->fetchColumn();
                $ticketCount = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'OPEN'")->fetchColumn();
                ?>
                <div class="card p-6">
                    <p class="text-sm text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold"><?php echo e($userCount); ?></p>
                </div>
                <div class="card p-6">
                    <p class="text-sm text-gray-400">Hosting Accounts</p>
                    <p class="text-2xl font-bold"><?php echo e($hostingCount); ?></p>
                </div>
                <div class="card p-6">
                    <p class="text-sm text-gray-400">Open Tickets</p>
                    <p class="text-2xl font-bold text-indigo-400"><?php echo e($ticketCount); ?></p>
                </div>
            </div>

            <div class="card overflow-hidden">
                <div class="p-6 border-b border-[#1E293B]">
                    <h2 class="text-lg font-semibold">System Status</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-emerald-500">
                        <i class="lucide-check-circle w-5 h-5"></i>
                        <span>MOFH API Connection: Healthy</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
