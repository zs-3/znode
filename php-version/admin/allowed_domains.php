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
    $domain = $_POST['domain'] ?? '';
    if (!empty($domain)) {
        $stmt = $pdo->prepare("INSERT INTO allowed_domains (domain) VALUES (?)");
        $stmt->execute([$domain]);
        header("Location: allowed_domains.php?success=Domain added");
        exit;
    }
    if (isset($_POST['delete_id'])) {
        $stmt = $pdo->prepare("DELETE FROM allowed_domains WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        header("Location: allowed_domains.php?success=Domain removed");
        exit;
    }
}

$domains = $pdo->query("SELECT * FROM allowed_domains ORDER BY domain ASC")->fetchAll();

$page_title = "Allowed Domains";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>
    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Allowed Domains</h1>

            <div class="card p-6">
                <form method="POST" class="flex gap-4">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <input type="text" name="domain" class="flex-1 bg-[#0B1120] border border-[#1E293B] rounded-lg px-4 py-2" placeholder="example.com" required>
                    <button type="submit" class="btn-primary">Add Domain</button>
                </form>
            </div>

            <div class="card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-[#1E293B] text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Domain</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B]">
                        <?php foreach ($domains as $d): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo e($d['domain']); ?></td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" onsubmit="return confirm('Remove this domain?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                    <input type="hidden" name="delete_id" value="<?php echo $d['id']; ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-400">
                                        <i class="lucide-trash-2 w-4 h-4"></i>
                                    </button>
                                </form>
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
