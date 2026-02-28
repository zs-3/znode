<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: tickets.php");
    exit;
}

// Handle reply POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $message = $_POST['message'] ?? '';

    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO ticket_replies (ticketId, userId, message) VALUES (?, ?, ?)");
        $stmt->execute([$id, $_SESSION['user_id'], $message]);

        $stmt = $pdo->prepare("UPDATE tickets SET status = 'OPEN' WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: ticket_view.php?id=$id&success=Reply added");
        exit;
    }
}

// Fetch ticket
$stmt = $pdo->prepare("SELECT tickets.*, hostings.domain FROM tickets LEFT JOIN hostings ON tickets.hostingId = hostings.id WHERE tickets.id = ? AND tickets.userId = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header("Location: tickets.php");
    exit;
}

// Fetch replies
$stmt = $pdo->prepare("SELECT ticket_replies.*, users.name FROM ticket_replies JOIN users ON ticket_replies.userId = users.id WHERE ticketId = ? ORDER BY createdAt ASC");
$stmt->execute([$id]);
$replies = $stmt->fetchAll();

$page_title = "Ticket: " . $ticket['subject'];
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="tickets.php" class="p-2 rounded-lg hover:bg-[#1E293B] text-gray-400">
                <i class="lucide-arrow-left w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold"><?php echo e($ticket['subject']); ?></h1>
                <p class="text-sm text-gray-400">Ticket #<?php echo e($ticket['id']); ?> • <?php echo e($ticket['domain'] ?? 'No related account'); ?></p>
            </div>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?php echo $ticket['status'] === 'CLOSED' ? 'bg-gray-500/10 text-gray-500' : 'bg-indigo-500/10 text-indigo-400'; ?>">
            <?php echo e($ticket['status']); ?>
        </span>
    </div>

    <!-- Replies -->
    <div class="space-y-4">
        <?php foreach ($replies as $reply): ?>
            <div class="card p-6 <?php echo $reply['isSupport'] ? 'border-indigo-500/30 bg-indigo-500/5' : ''; ?>">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#1E293B] flex items-center justify-center text-xs font-bold">
                            <?php echo strtoupper(substr($reply['name'], 0, 1)); ?>
                        </div>
                        <span class="font-medium"><?php echo e($reply['name']); ?></span>
                        <?php if ($reply['isSupport']): ?>
                            <span class="px-2 py-0.5 rounded bg-indigo-500 text-[10px] font-bold text-white uppercase">Support</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-xs text-gray-500"><?php echo e($reply['createdAt']); ?></span>
                </div>
                <div class="text-gray-300 whitespace-pre-wrap"><?php echo e($reply['message']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Reply Form -->
    <?php if ($ticket['status'] !== 'CLOSED'): ?>
        <form action="ticket_view.php?id=<?php echo e($id); ?>" method="POST" class="card p-6 space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
            <div class="space-y-2">
                <label class="block text-sm font-medium">Add Reply</label>
                <textarea name="message" rows="4" placeholder="Write your response..." class="input" required></textarea>
            </div>
            <button type="submit" class="btn-primary">
                Post Reply
                <i class="lucide-message-square w-4 h-4"></i>
            </button>
        </form>
    <?php else: ?>
        <div class="card p-6 text-center text-gray-500">
            This ticket is closed and cannot be replied to.
        </div>
    <?php endif; ?>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
