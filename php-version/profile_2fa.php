<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';
require_once 'includes/totp.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if (isset($_POST['enable'])) {
        $secret = $_POST['secret'];
        $code = $_POST['code'];

        if (TOTP::verifyCode($secret, $code)) {
            $stmt = $pdo->prepare("UPDATE users SET twoFactorSecret = ? WHERE id = ?");
            $stmt->execute([$secret, $user_id]);
            $user['twoFactorSecret'] = $secret;
            $success = "Two-factor authentication enabled successfully!";
        } else {
            $error = "Invalid verification code. Please try again.";
        }
    } elseif (isset($_POST['disable'])) {
        $stmt = $pdo->prepare("UPDATE users SET twoFactorSecret = NULL WHERE id = ?");
        $stmt->execute([$user_id]);
        $user['twoFactorSecret'] = null;
        $success = "Two-factor authentication disabled.";
    }
}

$page_title = "Two-Factor Authentication";
include 'templates/header.php';
include 'templates/sidebar.php';

$is_enabled = !empty($user['twoFactorSecret']);
$new_secret = TOTP::generateSecret();
?>

<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="index.php" class="p-2 rounded-lg hover:bg-[#1E293B] text-gray-400">
            <i class="lucide-arrow-left w-5 h-5"></i>
        </a>
        <h1 class="text-2xl font-bold">Two-Factor Authentication</h1>
    </div>

    <?php if ($success): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-lg flex items-center gap-3">
            <i class="lucide-check-circle w-5 h-5"></i>
            <span><?php echo e($success); ?></span>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-lg flex items-center gap-3">
            <i class="lucide-alert-circle w-5 h-5"></i>
            <span><?php echo e($error); ?></span>
        </div>
    <?php endif; ?>

    <div class="card p-8 space-y-6">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl bg-indigo-500/10 flex items-center justify-center shrink-0">
                <i class="lucide-shield-check w-10 h-10 text-indigo-400"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold"><?php echo $is_enabled ? 'Secure' : 'Add Protection'; ?></h2>
                <p class="text-gray-400">Two-factor authentication adds an extra layer of security to your account by requiring a code from your mobile device.</p>
            </div>
        </div>

        <?php if ($is_enabled): ?>
            <div class="bg-emerald-500/5 border border-emerald-500/20 p-6 rounded-2xl flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-emerald-500">2FA is currently active</h3>
                    <p class="text-sm text-gray-500">Your account is protected with TOTP.</p>
                </div>
                <form action="profile_2fa.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <button type="submit" name="disable" class="px-4 py-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all text-sm font-bold">Disable 2FA</button>
                </form>
            </div>
        <?php else: ?>
            <form action="profile_2fa.php" method="POST" class="space-y-8 pt-6 border-t border-[#1E293B]">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="secret" value="<?php echo $new_secret; ?>">

                <div class="space-y-4">
                    <h3 class="font-bold">1. Setup Authenticator App</h3>
                    <p class="text-sm text-gray-400">Install an app like Google Authenticator or Authy, and scan the QR code below or enter the secret manually.</p>

                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <div class="p-4 bg-white rounded-xl">
                            <!-- In a real app, generate a QR code here. For now, we show the secret. -->
                            <div class="w-32 h-32 bg-gray-100 flex items-center justify-center text-black font-bold text-center text-xs">QR Code Placeholder</div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Secret Key</p>
                            <code class="text-lg font-mono text-indigo-400 bg-[#0B1120] px-4 py-2 rounded-lg border border-[#1E293B] block">
                                <?php echo chunk_split($new_secret, 4, ' '); ?>
                            </code>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-6 border-t border-[#1E293B]">
                    <h3 class="font-bold">2. Verify Setup</h3>
                    <p class="text-sm text-gray-400">Enter the 6-digit code from your app to verify the setup.</p>
                    <div class="flex gap-4">
                        <input type="text" name="code" class="input text-center text-2xl tracking-[1em] font-mono" maxlength="6" placeholder="000000" required>
                        <button type="submit" name="enable" class="btn-primary px-8">Enable 2FA</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
