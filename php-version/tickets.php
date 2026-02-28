<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$page_title = "Support Tickets";
include 'templates/header.php';
include 'templates/sidebar.php';

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE userId = ? ORDER BY createdAt DESC");
$stmt->execute([$_SESSION['user_id']]);
$tickets = $stmt->fetchAll();
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Support Tickets</h1>
            <p class="text-gray-400">Get help with your hosting accounts.</p>
        </div>
        <a href="ticket_create.php" class="btn-primary">
            <i class="lucide-plus w-4 h-4"></i>
            Open New Ticket
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="divide-y divide-[#1E293B]">
            <?php if (empty($tickets)): ?>
                <div class="p-12 text-center text-gray-500">
                    No tickets found.
                </div>
            <?php else: ?>
                <?php foreach ($tickets as $ticket): ?>
                    <div class="p-4 flex items-center justify-between hover:bg-[#1E293B]/50 transition-colors">
                        <div>
                            <h3 class="font-medium"><?php echo e($ticket['subject']); ?></h3>
                            <p class="text-xs text-gray-500"><?php echo e($ticket['createdAt']); ?></p>
                        </div>
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-indigo-500/10 text-indigo-400">
                            <?php echo e($ticket['status']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
