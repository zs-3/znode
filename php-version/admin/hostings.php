<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_admin()) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    $hosting_id = $_POST['hosting_id'] ?? '';

    // Fetch hosting to get MOFH username
    $stmt = $pdo->prepare("SELECT * FROM hostings WHERE id = ?");
    $stmt->execute([$hosting_id]);
    $hosting = $stmt->fetch();

    if ($hosting) {
        if ($action === 'suspend') {
            $result = mofh_suspend_account($hosting['vpUsername'], "Suspended by admin");
            if ($result) {
                $stmt = $pdo->prepare("UPDATE hostings SET status = 'SUSPENDED' WHERE id = ?");
                $stmt->execute([$hosting_id]);
                header("Location: hostings.php?success=Account suspended");
                exit;
            }
        } elseif ($action === 'unsuspend') {
            $result = mofh_unsuspend_account($hosting['vpUsername']);
            if ($result) {
                $stmt = $pdo->prepare("UPDATE hostings SET status = 'ACTIVE' WHERE id = ?");
                $stmt->execute([$hosting_id]);
                header("Location: hostings.php?success=Account unsuspended");
                exit;
            }
        } elseif ($action === 'delete') {
            $result = mofh_delete_account($hosting['vpUsername']);
            if ($result) {
                $stmt = $pdo->prepare("DELETE FROM hostings WHERE id = ?");
                $stmt->execute([$hosting_id]);
                header("Location: hostings.php?success=Account deleted");
                exit;
            }
        }
    }
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

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-lg text-sm">
                    <?php echo e($_GET['success']); ?>
                </div>
            <?php endif; ?>

            <div class="card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-[#1E293B] text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Domain</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B]">
                        <?php foreach ($hostings as $h): ?>
                        <tr class="hover:bg-[#1E293B]/30 transition-colors">
                            <td class="px-6 py-4 font-bold"><?php echo e($h['domain']); ?></td>
                            <td class="px-6 py-4 text-xs"><?php echo e($h['user_email']); ?></td>
                            <td class="px-6 py-4 font-mono text-xs"><?php echo e($h['vpUsername']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?php echo $h['status'] === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500'; ?>">
                                    <?php echo e($h['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <?php if ($h['status'] === 'ACTIVE'): ?>
                                        <form method="POST" onsubmit="return confirm('Suspend this account?')">
                                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                            <input type="hidden" name="hosting_id" value="<?php echo $h['id']; ?>">
                                            <input type="hidden" name="action" value="suspend">
                                            <button type="submit" class="p-2 hover:bg-[#0B1120] rounded text-gray-400 hover:text-amber-500 transition-colors" title="Suspend">
                                                <i class="lucide-pause-circle w-4 h-4"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" onsubmit="return confirm('Unsuspend this account?')">
                                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                            <input type="hidden" name="hosting_id" value="<?php echo $h['id']; ?>">
                                            <input type="hidden" name="action" value="unsuspend">
                                            <button type="submit" class="p-2 hover:bg-[#0B1120] rounded text-gray-400 hover:text-emerald-500 transition-colors" title="Unsuspend">
                                                <i class="lucide-play-circle w-4 h-4"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" onsubmit="return confirm('Delete this account? This cannot be undone.')">
                                        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="hosting_id" value="<?php echo $h['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="p-2 hover:bg-[#0B1120] rounded text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                            <i class="lucide-trash-2 w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
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
