<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    $hostingId = $_POST['hostingId'] ?? null;

    if (empty($subject) || empty($message)) {
        $_SESSION['error'] = "Subject and message are required";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tickets (userId, hostingId, subject, status) VALUES (?, ?, ?, 'OPEN')");
        $stmt->execute([$_SESSION['user_id'], $hostingId, $subject]);
        $ticketId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO ticket_replies (ticketId, userId, message) VALUES (?, ?, ?)");
        $stmt->execute([$ticketId, $_SESSION['user_id'], $message]);

        header("Location: tickets.php?success=Ticket opened");
        exit;
    }
}

$stmt = $pdo->prepare("SELECT id, domain FROM hostings WHERE userId = ?");
$stmt->execute([$_SESSION['user_id']]);
$hostings = $stmt->fetchAll();

$page_title = "Open New Ticket";
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="tickets.php" class="p-2 rounded-lg hover:bg-[#1E293B] text-gray-400">
            <i class="lucide-arrow-left w-5 h-5"></i>
        </a>
        <h1 class="text-2xl font-bold">Open New Ticket</h1>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-3 rounded-lg">
            <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="ticket_create.php" method="POST" class="card p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

        <div class="space-y-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium">Subject</label>
                <input name="subject" type="text" placeholder="I have an issue with..." class="input" required>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Related Hosting Account (Optional)</label>
                <select name="hostingId" class="input bg-[#0B1120]">
                    <option value="">None / Other</option>
                    <?php foreach ($hostings as $hosting): ?>
                        <option value="<?php echo e($hosting['id']); ?>"><?php echo e($hosting['domain']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Message</label>
                <textarea name="message" rows="6" placeholder="Describe your issue in detail..." class="input" required></textarea>
            </div>
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3">
            Open Ticket
            <i class="lucide-send w-5 h-5"></i>
        </button>
    </form>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
