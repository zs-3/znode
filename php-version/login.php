<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Handle login POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'ADMIN') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $_SESSION['error'] = "Invalid email or password";
        }
    } else {
        $_SESSION['error'] = "Please fill in all fields";
    }
}

$page_title = "Login";
include 'templates/header.php';
?>

<div class="min-h-screen flex">
    <!-- Left side - Decorative -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-hero relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl animate-pulse"></div>
        </div>

        <div class="relative z-10 flex flex-col justify-center px-16">
            <a href="/" class="flex items-center gap-2 mb-12">
                <div class="w-12 h-12 rounded-xl bg-gradient-primary flex items-center justify-center">
                    <i class="lucide-server w-6 h-6 text-white"></i>
                </div>
                <span class="text-2xl font-bold text-white"><?php echo SITE_NAME; ?></span>
            </a>

            <h1 class="text-4xl font-bold text-white mb-6">Welcome back</h1>
            <p class="text-lg text-white/70 max-w-md">Manage your free hosting accounts with ease and professional tools.</p>

            <div class="mt-16 grid grid-cols-3 gap-4">
                <div class="text-center p-4 rounded-xl bg-white/5 border border-white/10">
                    <div class="text-2xl font-bold text-white">10K+</div>
                    <div class="text-sm text-white/60">Accounts</div>
                </div>
                <div class="text-center p-4 rounded-xl bg-white/5 border border-white/10">
                    <div class="text-2xl font-bold text-white">99.9%</div>
                    <div class="text-sm text-white/60">Uptime</div>
                </div>
                <div class="text-center p-4 rounded-xl bg-white/5 border border-white/10">
                    <div class="text-2xl font-bold text-white">24/7</div>
                    <div class="text-sm text-white/60">Support</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right side - Login form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="text-center lg:text-left mb-8">
                <h2 class="text-2xl font-bold mb-2">Login</h2>
                <p class="text-muted">Enter your credentials to access your account</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-3 rounded-lg mb-6">
                    <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium">Email Address</label>
                    <div class="relative">
                        <i class="lucide-mail absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                        <input id="email" name="email" type="email" placeholder="you@example.com" class="input pl-10" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between">
                        <label for="password" class="block text-sm font-medium">Password</label>
                        <a href="#" class="text-sm text-indigo-400 hover:underline">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <i class="lucide-lock absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                        <input id="password" name="password" type="password" placeholder="••••••••" class="input pl-10" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center py-3">
                    Login
                    <i class="lucide-arrow-right w-5 h-5"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-muted">
                    Don't have an account?
                    <a href="register.php" class="text-indigo-400 font-medium hover:underline">Register for free</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
