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
    $to = $_POST['to'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $body = $_POST['body'] ?? '';

    $success = send_email($to, $subject, $body);
    if ($success) {
        // Log the email
        $stmt = $pdo->prepare("INSERT INTO emails (userId, subject, body, status) VALUES (?, ?, ?, ?)");
        // Try to find user ID by email
        $user = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $user->execute([$to]);
        $uid = $user->fetchColumn() ?: null;
        $stmt->execute([$uid, $subject, $body, 'SENT']);
        header("Location: send_mail.php?success=Email sent successfully");
        exit;
    }
}

$templates = $pdo->query("SELECT * FROM email_templates ORDER BY name ASC")->fetchAll();
$users = $pdo->query("SELECT email FROM users ORDER BY email ASC")->fetchAll();

$page_title = "Send Mail";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>
    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Send Email</h1>
            <div class="card p-6">
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">To (Email)</label>
                        <select name="to" class="w-full bg-[#0B1120] border border-[#1E293B] rounded-lg px-4 py-2" required>
                            <option value="">Select a user...</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?php echo e($u['email']); ?>"><?php echo e($u['email']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Load Template</label>
                        <select onchange='var t = <?php echo json_encode($templates); ?>; var s = this.value; t.forEach(function(temp){ if(temp.id == s){ document.getElementsByName("subject")[0].value = temp.subject; document.getElementsByName("body")[0].value = temp.body; } });' class="w-full bg-[#0B1120] border border-[#1E293B] rounded-lg px-4 py-2">
                            <option value="">Select a template...</option>
                            <?php foreach ($templates as $t): ?>
                                <option value="<?php echo $t['id']; ?>"><?php echo e($t['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Subject</label>
                        <input type="text" name="subject" class="w-full bg-[#0B1120] border border-[#1E293B] rounded-lg px-4 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Message Body</label>
                        <textarea name="body" rows="10" class="w-full bg-[#0B1120] border border-[#1E293B] rounded-lg px-4 py-2" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Send Email</button>
                </form>
            </div>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
