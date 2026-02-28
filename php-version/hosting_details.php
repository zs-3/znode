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
    header("Location: hosting.php");
    exit;
}

// Fetch hosting details
$stmt = $pdo->prepare("SELECT * FROM hostings WHERE id = ? AND userId = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$hosting = $stmt->fetch();

if (!$hosting) {
    header("Location: hosting.php");
    exit;
}

$page_title = "Hosting Details - " . $hosting['domain'];
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="space-y-8">
    <!-- Back link -->
    <a href="hosting.php" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors group">
        <i class="lucide-arrow-left w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
        Back to list
    </a>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-primary flex items-center justify-center">
                <i class="lucide-server w-7 h-7 text-white"></i>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold"><?php echo e($hosting['domain']); ?></h1>
                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $hosting['status'] === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500'; ?>">
                        <?php echo e($hosting['status']); ?>
                    </span>
                </div>
                <a href="http://<?php echo e($hosting['domain']); ?>" target="_blank" class="text-gray-400 hover:text-white flex items-center gap-1 mt-1">
                    <i class="lucide-globe w-4 h-4"></i>
                    <?php echo e($hosting['domain']); ?>
                    <i class="lucide-external-link w-3 h-3"></i>
                </a>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="window.location.reload()" class="px-4 py-2 border border-[#1E293B] rounded-lg text-sm hover:bg-[#1E293B]">
                <i class="lucide-refresh-cw w-4 h-4 mr-2 inline"></i>
                Refresh
            </button>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?php echo MOFH_CPANEL_URL; ?>" target="_blank" class="card p-6 hover:border-indigo-500/50 transition-colors block">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-4">
                <i class="lucide-monitor w-6 h-6 text-indigo-400"></i>
            </div>
            <h3 class="font-bold">Control Panel</h3>
            <p class="text-xs text-gray-500 mt-1">Manage hosting features</p>
        </a>
        <a href="<?php echo get_file_manager_link($hosting['vpUsername'], $hosting['password']); ?>" target="_blank" class="card p-6 hover:border-blue-500/50 transition-colors block">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center mb-4">
                <i class="lucide-folder w-6 h-6 text-blue-400"></i>
            </div>
            <h3 class="font-bold">File Manager</h3>
            <p class="text-xs text-gray-500 mt-1">Upload & edit files</p>
        </a>
        <div class="card p-6 hover:border-amber-500/50 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center mb-4">
                <i class="lucide-box w-6 h-6 text-amber-400"></i>
            </div>
            <h3 class="font-bold">Softaculous</h3>
            <p class="text-xs text-gray-500 mt-1">Install WordPress, etc.</p>
        </div>
        <div class="card p-6 hover:border-emerald-500/50 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-4">
                <i class="lucide-settings w-6 h-6 text-emerald-400"></i>
            </div>
            <h3 class="font-bold">Settings</h3>
            <p class="text-xs text-gray-500 mt-1">Account settings</p>
        </div>
    </div>

    <!-- Account Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card overflow-hidden">
            <div class="p-6 border-b border-[#1E293B]">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <i class="lucide-shield w-5 h-5 text-emerald-500"></i>
                    Account Information
                </h2>
            </div>
            <div class="divide-y divide-[#1E293B]">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="text-xs text-gray-500">Domain</p>
                        <code class="text-sm"><?php echo e($hosting['domain']); ?></code>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="text-xs text-gray-500">Username</p>
                        <code class="text-sm"><?php echo e($hosting['vpUsername']); ?></code>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="text-xs text-gray-500">Password</p>
                        <code class="text-sm">••••••••••••</code>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="text-xs text-gray-500">Created At</p>
                        <code class="text-sm"><?php echo e($hosting['createdAt']); ?></code>
                    </div>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="p-6 border-b border-[#1E293B]">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <i class="lucide-database w-5 h-5 text-violet-500"></i>
                    MySQL Databases
                </h2>
            </div>
            <div class="p-6 text-center text-gray-500">
                <p>Database management coming soon in PHP version.</p>
            </div>
        </div>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
