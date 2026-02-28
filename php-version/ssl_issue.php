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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $domain = $_POST['domain'] ?? $hosting['domain'];

    // Check if certificate already exists
    $stmt = $pdo->prepare("SELECT id FROM ssl_certificates WHERE domain = ? AND status != 'EXPIRED'");
    $stmt->execute([$domain]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "A certificate for this domain is already being processed.";
    } else {
        // Logic to start ACME process
        $stmt = $pdo->prepare("INSERT INTO ssl_certificates (hostingId, domain, status) VALUES (?, ?, 'PENDING_VERIFICATION')");
        $stmt->execute([$hosting['id'], $domain]);

        header("Location: ssl.php?success=SSL issuance started");
        exit;
    }
}

$page_title = "Issue SSL Certificate - " . $hosting['domain'];
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="hosting_details.php?id=<?php echo $id; ?>" class="p-2 rounded-lg hover:bg-[#1E293B] text-gray-400">
            <i class="lucide-arrow-left w-5 h-5"></i>
        </a>
        <h1 class="text-2xl font-bold">Issue Free SSL</h1>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-3 rounded-lg">
            <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card p-8 text-center space-y-6">
        <div class="w-20 h-20 rounded-full bg-emerald-500/10 flex items-center justify-center mx-auto">
            <i class="lucide-shield-check w-10 h-10 text-emerald-500"></i>
        </div>

        <div class="space-y-2">
            <h2 class="text-xl font-bold">Free Let's Encrypt Certificate</h2>
            <p class="text-gray-400">Secure your domain <strong><?php echo e($hosting['domain']); ?></strong> with a trusted SSL certificate. This process is fully automated.</p>
        </div>

        <form action="ssl_issue.php?id=<?php echo $id; ?>" method="POST" class="pt-4 border-t border-[#1E293B]">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="domain" value="<?php echo e($hosting['domain']); ?>">

            <div class="bg-[#1E293B] p-4 rounded-lg text-left text-sm text-gray-400 mb-6 space-y-2">
                <div class="flex items-center gap-2">
                    <i class="lucide-check w-4 h-4 text-emerald-500"></i>
                    <span>Automatic DNS validation</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="lucide-check w-4 h-4 text-emerald-500"></i>
                    <span>90-day validity with auto-renewal</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="lucide-check w-4 h-4 text-emerald-500"></i>
                    <span>Recognized by all modern browsers</span>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full justify-center py-4 text-lg">
                Start Issuance Process
                <i class="lucide-zap w-5 h-5"></i>
            </button>
        </form>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
