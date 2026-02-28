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
        <button class="btn-primary" onclick="alert('SSL issuance coming soon in PHP version')">
            <i class="lucide-plus w-4 h-4"></i>
            Issue Certificate
        </button>
    </div>

    <div class="card p-12 text-center text-gray-500">
        <?php if (empty($certs)): ?>
            No SSL certificates found.
        <?php else: ?>
            <!-- List certs here -->
        <?php endif; ?>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
