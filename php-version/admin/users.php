<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_admin()) {
    header("Location: ../login.php");
    exit;
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
            <h1 class="text-2xl font-bold">Manage Users</h1>
            <div class="card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-[#1E293B] text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B]">
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo e($user['name']); ?></td>
                            <td class="px-6 py-4"><?php echo e($user['email']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase <?php echo $user['role'] === 'ADMIN' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-gray-500/10 text-gray-400'; ?>">
                                    <?php echo e($user['role']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($user['createdAt']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
