<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
    } elseif (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Email already registered";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password]);

            $_SESSION['success'] = "Registration successful. Please login.";
            header("Location: login.php");
            exit;
        }
    }
}

$page_title = "Register";
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

            <h1 class="text-4xl font-bold text-white mb-6">Join us today</h1>
            <p class="text-lg text-white/70 max-w-md">Start your hosting journey with the most advanced free hosting panel.</p>
        </div>
    </div>

    <!-- Right side - Register form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="text-center lg:text-left mb-8">
                <h2 class="text-2xl font-bold mb-2">Create Account</h2>
                <p class="text-muted">Register for a free hosting account</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-3 rounded-lg mb-6">
                    <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium">Full Name</label>
                    <div class="relative">
                        <i class="lucide-user absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                        <input id="name" name="name" type="text" placeholder="John Doe" class="input pl-10" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium">Email Address</label>
                    <div class="relative">
                        <i class="lucide-mail absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                        <input id="email" name="email" type="email" placeholder="you@example.com" class="input pl-10" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <div class="relative">
                        <i class="lucide-lock absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                        <input id="password" name="password" type="password" placeholder="••••••••" class="input pl-10" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="confirm_password" class="block text-sm font-medium">Confirm Password</label>
                    <div class="relative">
                        <i class="lucide-shield-check absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                        <input id="confirm_password" name="confirm_password" type="password" placeholder="••••••••" class="input pl-10" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center py-3">
                    Register
                    <i class="lucide-arrow-right w-5 h-5"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-muted">
                    Already have an account?
                    <a href="login.php" class="text-indigo-400 font-medium hover:underline">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
