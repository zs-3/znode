<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_admin()) {
    header("Location: ../login.php");
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

    if (isset($_POST['reply'])) {
        $message = $_POST['message'] ?? '';
        if (!empty($message)) {
            $stmt = $pdo->prepare("INSERT INTO ticket_replies (ticketId, userId, message, isSupport) VALUES (?, ?, ?, 1)");
            $stmt->execute([$id, $_SESSION['user_id'], $message]);

            $stmt = $pdo->prepare("UPDATE tickets SET status = 'REPLIED' WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: ticket_view.php?id=$id&success=Reply sent");
            exit;
        }
    } elseif (isset($_POST['close'])) {
        $stmt = $pdo->prepare("UPDATE tickets SET status = 'CLOSED' WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: ticket_view.php?id=$id&success=Ticket closed");
        exit;
    }
}

// Fetch ticket
$stmt = $pdo->prepare("SELECT tickets.*, users.name as user_name FROM tickets JOIN users ON tickets.userId = users.id WHERE tickets.id = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header("Location: tickets.php");
    exit;
}

// Fetch replies
$stmt = $pdo->prepare("SELECT ticket_replies.*, users.name FROM ticket_replies JOIN users ON ticket_replies.userId = users.id WHERE ticketId = ? ORDER BY createdAt ASC");
$stmt->execute([$id]);
$replies = $stmt->fetchAll();

$page_title = "Manage Ticket: " . $ticket['subject'];
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php include '../templates/admin_sidebar.php'; ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="tickets.php" class="p-2 rounded-lg hover:bg-[#1E293B] text-gray-400">
                        <i class="lucide-arrow-left w-5 h-5"></i>
                    </a>
                    <h1 class="text-2xl font-bold"><?php echo e($ticket['subject']); ?></h1>
                </div>
                <div class="flex gap-2">
                    <?php if ($ticket['status'] !== 'CLOSED'): ?>
                        <form action="ticket_view.php?id=<?php echo $id; ?>" method="POST" onsubmit="return confirm('Close this ticket?')">
                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                            <button type="submit" name="close" class="px-4 py-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all text-sm font-bold">Close Ticket</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="space-y-4">
                <?php foreach ($replies as $reply): ?>
                    <div class="card p-6 <?php echo $reply['isSupport'] ? 'border-indigo-500/30 bg-indigo-500/5' : ''; ?>">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-sm <?php echo $reply['isSupport'] ? 'text-indigo-400' : 'text-white'; ?>"><?php echo e($reply['name']); ?> <?php echo $reply['isSupport'] ? '(Staff)' : ''; ?></span>
                            <span class="text-xs text-gray-500"><?php echo e($reply['createdAt']); ?></span>
                        </div>
                        <p class="text-gray-300 text-sm whitespace-pre-wrap"><?php echo e($reply['message']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($ticket['status'] !== 'CLOSED'): ?>
                <form action="ticket_view.php?id=<?php echo $id; ?>" method="POST" class="card p-6 space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <textarea name="message" rows="5" class="input" placeholder="Enter your reply..." required></textarea>
                    <button type="submit" name="reply" class="btn-primary">Send Reply</button>
                </form>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
