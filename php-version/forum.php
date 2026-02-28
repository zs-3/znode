<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$page_title = "Community Forum";
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Community Forum</h1>
            <p class="text-gray-400">Join the discussion with other users.</p>
        </div>
        <button class="btn-primary">
            <i class="lucide-plus w-4 h-4"></i>
            New Post
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <div class="card p-8 text-center text-gray-500">
            <div class="w-16 h-16 rounded-full bg-[#1E293B] flex items-center justify-center mx-auto mb-4">
                <i class="lucide-message-square w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-medium text-white mb-2">Welcome to the Forum</h3>
            <p class="max-w-md mx-auto">The forum is a place to ask questions, share knowledge, and connect with the community. This feature is being ported to the PHP version.</p>
        </div>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
