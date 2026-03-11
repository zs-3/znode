<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_admin()) {
    header("Location: ../login.php");
    exit;
}

$stmt = $pdo->query("SELECT tickets.*, users.name as user_name FROM tickets JOIN users ON tickets.userId = users.id ORDER BY createdAt DESC");
$tickets = $stmt->fetchAll();

$page_title = "Manage Support Tickets";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Support Tickets</h1>
            <div class="card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-[#1E293B] text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Created</th>
                            <th class="px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B]">
                        <?php foreach ($tickets as $t): ?>
                        <tr class="hover:bg-[#1E293B]/30 transition-colors">
                            <td class="px-6 py-4 font-medium"><?php echo e($t['subject']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-400"><?php echo e($t['user_name']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase <?php echo $t['status'] === 'CLOSED' ? 'bg-gray-500/10 text-gray-500' : 'bg-indigo-500/10 text-indigo-400'; ?>">
                                    <?php echo e($t['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500"><?php echo e($t['createdAt']); ?></td>
                            <td class="px-6 py-4">
                                <a href="ticket_view.php?id=<?php echo $t['id']; ?>" class="text-indigo-400 hover:text-white transition-colors">
                                    <i class="lucide-external-link w-4 h-4"></i>
                                </a>
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
