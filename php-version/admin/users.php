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
    $user_id = $_POST['user_id'] ?? '';

    if ($action === 'delete' && $user_id != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        header("Location: users.php?success=User deleted");
        exit;
    } elseif ($action === 'verify') {
        $stmt = $pdo->prepare("UPDATE users SET emailVerified = 1 WHERE id = ?");
        $stmt->execute([$user_id]);
        header("Location: users.php?success=User verified");
        exit;
    } elseif ($action === 'unverify') {
        $stmt = $pdo->prepare("UPDATE users SET emailVerified = 0 WHERE id = ?");
        $stmt->execute([$user_id]);
        header("Location: users.php?success=User unverified");
        exit;
    } elseif ($action === 'login_as') {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['is_admin'] = false; // Downgrade to user for the session
        header("Location: ../dashboard.php");
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY createdAt DESC");
$users = $stmt->fetchAll();

$page_title = "Manage Users";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Manage Users</h1>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-lg text-sm">
                    <?php echo e($_GET['success']); ?>
                </div>
            <?php endif; ?>

            <div class="card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-[#1E293B] text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Joined</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B]">
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-[#1E293B]/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold"><?php echo e($user['name']); ?></span>
                                    <span class="text-xs text-gray-500"><?php echo e($user['email']); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($user['emailVerified']): ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-500">Verified</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/10 text-amber-500">Unverified</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase <?php echo $user['role'] === 'ADMIN' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-gray-500/10 text-gray-400'; ?>">
                                    <?php echo e($user['role']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500"><?php echo date('M d, Y', strtotime($user['createdAt'])); ?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <form method="POST" onsubmit="return confirm('Login as this user?')">
                                        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="action" value="login_as">
                                        <button type="submit" class="p-2 hover:bg-[#0B1120] rounded text-gray-400 hover:text-white transition-colors" title="Login As">
                                            <i class="lucide-log-in w-4 h-4"></i>
                                        </button>
                                    </form>
                                    <?php if ($user['emailVerified']): ?>
                                        <form method="POST">
                                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="action" value="unverify">
                                            <button type="submit" class="p-2 hover:bg-[#0B1120] rounded text-gray-400 hover:text-amber-500 transition-colors" title="Unverify">
                                                <i class="lucide-shield-off w-4 h-4"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST">
                                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="action" value="verify">
                                            <button type="submit" class="p-2 hover:bg-[#0B1120] rounded text-gray-400 hover:text-emerald-500 transition-colors" title="Verify">
                                                <i class="lucide-shield-check w-4 h-4"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" onsubmit="return confirm('Delete this user? This cannot be undone.')">
                                        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="p-2 hover:bg-[#0B1120] rounded text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                            <i class="lucide-trash-2 w-4 h-4"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
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
