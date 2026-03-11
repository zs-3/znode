<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';
require_once 'includes/totp.php';

if (!isset($_SESSION['temp_user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $code = $_POST['code'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['temp_user_id']]);
    $user = $stmt->fetch();

    if ($user && TOTP::verifyCode($user['twoFactorSecret'], $code)) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        unset($_SESSION['temp_user_id']);

        if ($user['role'] === 'ADMIN') {
            header("Location: admin/index.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $_SESSION['error'] = "Invalid 2FA code.";
    }
}

$page_title = "Two-Factor Authentication Check";
include 'templates/header.php';
?>

<div class="min-h-screen flex items-center justify-center p-8 bg-gradient-hero">
    <div class="w-full max-w-md card p-8 space-y-6">
        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-full bg-indigo-500/10 flex items-center justify-center mx-auto mb-4">
                <i class="lucide-shield-lock w-8 h-8 text-indigo-400"></i>
            </div>
            <h1 class="text-2xl font-bold">Two-Factor Check</h1>
            <p class="text-gray-400">Enter the code from your authenticator app.</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-3 rounded-lg text-center text-sm">
                <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="login_2fa.php" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
            <div class="space-y-2">
                <input type="text" name="code" class="input text-center text-3xl tracking-[0.5em] font-mono py-4" maxlength="6" placeholder="000000" autofocus required>
            </div>
            <button type="submit" class="btn-primary w-full justify-center py-3">Verify & Login</button>
        </form>

        <div class="text-center">
            <a href="login.php" class="text-sm text-gray-500 hover:text-white">Back to Login</a>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
