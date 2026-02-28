<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$page_title = "Knowledge Base";
include 'templates/header.php';
include 'templates/sidebar.php';

$base_dir = __DIR__ . '/../output';
$categories = [];

if (is_dir($base_dir)) {
    $dirs = array_filter(glob($base_dir . '/*'), 'is_dir');
    foreach ($dirs as $dir) {
        $category_name = basename($dir);
        $articles = glob($dir . '/*.md');
        $categories[] = [
            'name' => $category_name,
            'count' => count($articles),
            'slug' => urlencode($category_name)
        ];
    }
}
?>

<div class="space-y-8">
    <div class="text-center py-12 bg-gradient-hero rounded-3xl border border-[#1E293B] relative overflow-hidden">
        <div class="absolute inset-0 bg-indigo-500/10 blur-3xl rounded-full -top-24 -left-24 w-64 h-64"></div>
        <div class="relative z-10 space-y-4">
            <h1 class="text-4xl font-bold">How can we help?</h1>
            <p class="text-gray-400 max-w-xl mx-auto">Search our knowledge base for answers to common questions and helpful guides on managing your hosting.</p>

            <div class="max-w-2xl mx-auto px-4 mt-8">
                <div class="relative">
                    <i class="lucide-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 w-5 h-5"></i>
                    <input type="text" placeholder="Search for articles..." class="input pl-12 py-4 bg-[#111827] border-[#1E293B] focus:border-indigo-500 transition-all text-lg shadow-2xl">
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($categories as $category): ?>
            <a href="kb_category.php?name=<?php echo $category['slug']; ?>" class="card p-6 hover:border-indigo-500/50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center group-hover:bg-indigo-500/20 transition-colors">
                        <i class="lucide-book-open w-6 h-6 text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg"><?php echo e($category['name']); ?></h3>
                        <p class="text-sm text-gray-400"><?php echo $category['count']; ?> articles</p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
