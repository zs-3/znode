<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_admin()) {
    header("Location: ../login.php");
    exit;
}

$emails = $pdo->query("SELECT emails.*, users.email as user_email FROM emails LEFT JOIN users ON emails.userId = users.id ORDER BY createdAt DESC")->fetchAll();

$page_title = "Email History";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>
    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Email History</h1>
            <div class="card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-[#1E293B] text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Recipient</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Sent At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B]">
                        <?php foreach ($emails as $e): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm"><?php echo e($e['user_email'] ?? $e['userId']); ?></td>
                            <td class="px-6 py-4 text-sm font-medium"><?php echo e($e['subject']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-500">Sent</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500"><?php echo e($e['createdAt']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
