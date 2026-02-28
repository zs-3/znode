<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$category_name = urldecode($_GET['name'] ?? '');
if (empty($category_name)) {
    header("Location: kb.php");
    exit;
}

$page_title = $category_name . " - Knowledge Base";
include 'templates/header.php';
include 'templates/sidebar.php';

$category_dir = __DIR__ . '/../output/' . $category_name;
$articles = [];

if (is_dir($category_dir)) {
    $files = glob($category_dir . '/*.md');
    foreach ($files as $file) {
        $articles[] = [
            'title' => str_replace('.md', '', basename($file)),
            'filename' => urlencode(basename($file))
        ];
    }
}
?>

<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="kb.php" class="p-2 rounded-lg hover:bg-[#1E293B] text-gray-400">
            <i class="lucide-arrow-left w-5 h-5"></i>
        </a>
        <h1 class="text-2xl font-bold"><?php echo e($category_name); ?></h1>
    </div>

    <div class="card overflow-hidden">
        <div class="divide-y divide-[#1E293B]">
            <?php if (empty($articles)): ?>
                <div class="p-12 text-center text-gray-500">No articles found in this category.</div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <a href="kb_article.php?category=<?php echo urlencode($category_name); ?>&file=<?php echo $article['filename']; ?>" class="p-4 flex items-center justify-between hover:bg-[#1E293B]/50 transition-colors block">
                        <div class="flex items-center gap-3">
                            <i class="lucide-file-text w-4 h-4 text-gray-500"></i>
                            <span class="font-medium"><?php echo e($article['title']); ?></span>
                        </div>
                        <i class="lucide-chevron-right w-4 h-4 text-gray-600"></i>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
