<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$page_title = "Hosting Accounts";
include 'templates/header.php';
include 'templates/sidebar.php';

// Fetch all user's hostings
$stmt = $pdo->prepare("SELECT * FROM hostings WHERE userId = ? ORDER BY createdAt DESC");
$stmt->execute([$_SESSION['user_id']]);
$hostings = $stmt->fetchAll();
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Hosting Accounts</h1>
            <p class="text-gray-400">Manage all your free hosting accounts.</p>
        </div>
        <a href="hosting_create.php" class="btn-primary">
            <i class="lucide-plus w-4 h-4"></i>
            Create New Account
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($hostings as $hosting): ?>
            <div class="card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-primary flex items-center justify-center">
                            <i class="lucide-server w-6 h-6 text-white"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?php echo $hosting['status'] === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500'; ?>">
                            <?php echo e($hosting['status']); ?>
                        </span>
                    </div>
                    <h3 class="text-lg font-bold truncate mb-1"><?php echo e($hosting['domain']); ?></h3>
                    <p class="text-sm text-gray-400 mb-4"><?php echo e($hosting['vpUsername']); ?></p>
                </div>

                <a href="hosting_details.php?id=<?php echo e($hosting['id']); ?>" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg bg-[#1E293B] hover:bg-[#2D3748] transition-colors text-sm font-medium">
                    Manage Account
                    <i class="lucide-settings w-4 h-4"></i>
                </a>
            </div>
        <?php endforeach; ?>

        <?php if (empty($hostings)): ?>
            <div class="col-span-full card p-12 text-center">
                <p class="text-gray-400">No hosting accounts found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
