<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_admin()) {
    header("Location: ../login.php");
    exit;
}

$stmt = $pdo->query("SELECT hostings.*, users.email as user_email FROM hostings JOIN users ON hostings.userId = users.id ORDER BY createdAt DESC");
$hostings = $stmt->fetchAll();

$page_title = "Manage Hosting Accounts";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Manage Hosting Accounts</h1>
            <div class="card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-[#1E293B] text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Domain</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B]">
                        <?php foreach ($hostings as $h): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo e($h['domain']); ?></td>
                            <td class="px-6 py-4 text-xs"><?php echo e($h['user_email']); ?></td>
                            <td class="px-6 py-4 font-mono text-xs"><?php echo e($h['vpUsername']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?php echo $h['status'] === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500'; ?>">
                                    <?php echo e($h['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
