<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$page_title = "Dashboard";
include 'templates/header.php';
include 'templates/sidebar.php';

// Fetch user's hostings
$stmt = $pdo->prepare("SELECT * FROM hostings WHERE userId = ? ORDER BY createdAt DESC");
$stmt->execute([$_SESSION['user_id']]);
$hostings = $stmt->fetchAll();

// Fetch stats
$total = count($hostings);
$active = count(array_filter($hostings, fn($h) => $h['status'] === 'ACTIVE'));
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <p class="text-gray-400">Welcome back to your hosting control panel.</p>
        </div>
        <a href="hosting_create.php" class="btn-primary">
            <i class="lucide-plus w-4 h-4"></i>
            Create New Account
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                    <i class="lucide-hard-drive w-6 h-6 text-indigo-400"></i>
                </div>
            </div>
            <p class="text-sm text-gray-400 mb-1">Hosting Accounts</p>
            <p class="text-2xl font-bold"><?php echo e($total); ?> <span class="text-gray-500 font-normal">/ 3</span></p>
        </div>
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <i class="lucide-shield w-6 h-6 text-emerald-400"></i>
                </div>
            </div>
            <p class="text-sm text-gray-400 mb-1">SSL Certificates</p>
            <p class="text-2xl font-bold">0</p>
        </div>
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center">
                    <i class="lucide-message-square w-6 h-6 text-violet-400"></i>
                </div>
            </div>
            <p class="text-sm text-gray-400 mb-1">Open Tickets</p>
            <p class="text-2xl font-bold">0</p>
        </div>
    </div>

    <!-- Recent Accounts -->
    <div class="card overflow-hidden">
        <div class="p-6 border-b border-[#1E293B] flex items-center justify-between">
            <h2 class="text-lg font-semibold">Recent Hosting Accounts</h2>
            <a href="hosting.php" class="text-sm text-indigo-400 hover:underline flex items-center gap-1">
                View All
                <i class="lucide-arrow-up-right w-4 h-4"></i>
            </a>
        </div>

        <?php if (empty($hostings)): ?>
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-[#1E293B] flex items-center justify-center mx-auto mb-4">
                    <i class="lucide-server w-8 h-8 text-gray-500"></i>
                </div>
                <h3 class="text-lg font-medium mb-2">No hosting accounts yet</h3>
                <p class="text-gray-400 mb-6">Create your first account to get started with your website.</p>
                <a href="hosting_create.php" class="btn-primary">
                    <i class="lucide-plus w-4 h-4"></i>
                    Create Hosting
                </a>
            </div>
        <?php else: ?>
            <div class="divide-y divide-[#1E293B]">
                <?php foreach (array_slice($hostings, 0, 5) as $hosting): ?>
                    <a href="hosting_details.php?id=<?php echo e($hosting['id']); ?>" class="flex items-center gap-4 p-4 hover:bg-[#1E293B]/50 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-gradient-primary flex items-center justify-center flex-shrink-0">
                            <i class="lucide-server w-6 h-6 text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-medium truncate"><?php echo e($hosting['domain']); ?></h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?php echo $hosting['status'] === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500'; ?>">
                                    <?php echo e($hosting['status']); ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 truncate"><?php echo e($hosting['vpUsername']); ?></p>
                        </div>
                        <i class="lucide-chevron-right w-5 h-5 text-gray-600"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
echo "</main></div></div>"; // Close sidebar & main wrappers
include 'templates/footer.php';
?>
