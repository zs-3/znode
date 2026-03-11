<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit;
}

$page_title = "Welcome to " . SITE_NAME;
include 'templates/header.php';
?>

<div class="min-h-screen bg-gradient-hero flex flex-col items-center justify-center p-8 text-center space-y-12">
    <div class="space-y-6 max-w-3xl">
        <?php if (defined('SITE_LOGO') && !empty(SITE_LOGO)): ?>
            <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>" class="h-20 mx-auto shadow-glow animate-float rounded-3xl">
        <?php else: ?>
            <div class="w-20 h-20 rounded-3xl bg-gradient-primary flex items-center justify-center mx-auto shadow-glow animate-float">
                <i class="lucide-server w-10 h-10 text-white"></i>
            </div>
        <?php endif; ?>
        <h1 class="text-6xl font-black tracking-tight text-white leading-tight">
            The Most Advanced <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Free Hosting</span> Panel.
        </h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Deploy your websites in seconds with our high-performance, library-free hosting management system. Secure, fast, and 100% free.
        </p>
    </div>

    <div class="flex flex-col sm:flex-row gap-4">
        <a href="register.php" class="px-8 py-4 rounded-xl bg-indigo-500 text-white font-bold text-lg hover:bg-indigo-400 transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
            Get Started Free
            <i class="lucide-arrow-right w-5 h-5"></i>
        </a>
        <a href="login.php" class="px-8 py-4 rounded-xl bg-white/5 text-white font-bold text-lg hover:bg-white/10 transition-all border border-white/10 backdrop-blur-sm">
            Client Login
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-5xl w-full pt-12">
        <div class="space-y-2">
            <div class="text-3xl font-bold text-white">100%</div>
            <div class="text-sm text-gray-500 uppercase tracking-widest">Free Forever</div>
        </div>
        <div class="space-y-2">
            <div class="text-3xl font-bold text-white">99.9%</div>
            <div class="text-sm text-gray-500 uppercase tracking-widest">Uptime</div>
        </div>
        <div class="space-y-2">
            <div class="text-3xl font-bold text-white">Soft</div>
            <div class="text-sm text-gray-500 uppercase tracking-widest">App Installer</div>
        </div>
        <div class="space-y-2">
            <div class="text-3xl font-bold text-white">SSL</div>
            <div class="text-sm text-gray-500 uppercase tracking-widest">Free Certificates</div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
