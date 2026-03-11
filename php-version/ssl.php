<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$page_title = "SSL Certificates";
include 'templates/header.php';
include 'templates/sidebar.php';

$stmt = $pdo->prepare("SELECT * FROM ssl_certificates WHERE hostingId IN (SELECT id FROM hostings WHERE userId = ?)");
$stmt->execute([$_SESSION['user_id']]);
$certs = $stmt->fetchAll();
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">SSL Certificates</h1>
            <p class="text-gray-400">Secure your domains with free Let's Encrypt certificates.</p>
        </div>
        <a href="hosting.php" class="btn-primary">
            <i class="lucide-plus w-4 h-4"></i>
            New Certificate
        </a>
    </div>

    <?php if (empty($certs)): ?>
        <div class="card p-12 text-center text-gray-500">
            No SSL certificates found. Start by selecting a hosting account.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($certs as $cert): ?>
                <div class="card p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center">
                            <i class="lucide-shield w-5 h-5 text-indigo-400"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?php echo $cert['status'] === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500'; ?>">
                            <?php echo e($cert['status']); ?>
                        </span>
                    </div>
                    <div>
                        <h3 class="font-bold"><?php echo e($cert['domain']); ?></h3>
                        <p class="text-xs text-gray-500 mt-1">Issued: <?php echo e(date('M d, Y', strtotime($cert['createdAt']))); ?></p>
                    </div>
                    <div class="pt-4 border-t border-[#1E293B]">
                        <button class="text-sm font-bold text-indigo-400 hover:text-indigo-300 flex items-center gap-2">
                            View Details
                            <i class="lucide-chevron-right w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
