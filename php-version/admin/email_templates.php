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
    $name = $_POST['name'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $body = $_POST['body'] ?? '';

    if (isset($_POST['edit_id'])) {
        $stmt = $pdo->prepare("UPDATE email_templates SET name = ?, subject = ?, body = ? WHERE id = ?");
        $stmt->execute([$name, $subject, $body, $_POST['edit_id']]);
        $msg = "Template updated";
    } else {
        $stmt = $pdo->prepare("INSERT INTO email_templates (name, subject, body) VALUES (?, ?, ?)");
        $stmt->execute([$name, $subject, $body]);
        $msg = "Template created";
    }
    header("Location: email_templates.php?success=$msg");
    exit;
}

$templates = $pdo->query("SELECT * FROM email_templates ORDER BY name ASC")->fetchAll();

$page_title = "Email Templates";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>
    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Email Templates</h1>

            <div class="card p-6">
                <h2 class="text-lg font-bold mb-4">Create / Edit Template</h2>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Template Name</label>
                            <input type="text" name="name" class="w-full bg-[#0B1120] border border-[#1E293B] rounded-lg px-4 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Subject</label>
                            <input type="text" name="subject" class="w-full bg-[#0B1120] border border-[#1E293B] rounded-lg px-4 py-2" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Body (HTML supported)</label>
                        <textarea name="body" rows="6" class="w-full bg-[#0B1120] border border-[#1E293B] rounded-lg px-4 py-2" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Save Template</button>
                </form>
            </div>

            <div class="card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-[#1E293B] text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1E293B]">
                        <?php foreach ($templates as $t): ?>
                        <tr>
                            <td class="px-6 py-4 font-medium"><?php echo e($t['name']); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo e($t['subject']); ?></td>
                            <td class="px-6 py-4 text-right">
                                <button onclick='document.getElementsByName("name")[0].value=<?php echo json_encode($t['name']); ?>; document.getElementsByName("subject")[0].value=<?php echo json_encode($t['subject']); ?>; document.getElementsByName("body")[0].value=<?php echo json_encode($t['body']); ?>; var f = document.querySelector("form"); if(!document.getElementsByName("edit_id").length){ var i = document.createElement("input"); i.type="hidden"; i.name="edit_id"; f.appendChild(i); } document.getElementsByName("edit_id")[0].value="<?php echo $t['id']; ?>";' class="text-indigo-400 hover:text-indigo-300 mr-2">
                                    <i class="lucide-edit w-4 h-4"></i>
                                </button>
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
