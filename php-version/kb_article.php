<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$category = urldecode($_GET['category'] ?? '');
$filename = urldecode($_GET['file'] ?? '');

if (empty($category) || empty($filename)) {
    header("Location: kb.php");
    exit;
}

$filepath = __DIR__ . '/../output/' . $category . '/' . $filename;
if (!file_exists($filepath)) {
    header("Location: kb.php");
    exit;
}

$content = file_get_contents($filepath);
$title = str_replace('.md', '', $filename);

// Very basic markdown parsing
$content = e($content);
$content = preg_replace('/^# (.*)$/m', '<h1 class="text-3xl font-bold mb-6">$1</h1>', $content);
$content = preg_replace('/^## (.*)$/m', '<h2 class="text-xl font-bold mt-8 mb-4">$1</h2>', $content);
$content = preg_replace('/^\- (.*)$/m', '<li class="ml-4 mb-2">$1</li>', $content);
$content = preg_replace('/\n\n/', '</p><p class="mb-4">', $content);
$content = '<p class="mb-4">' . $content . '</p>';

$page_title = $title . " - Knowledge Base";
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="kb_category.php?name=<?php echo urlencode($category); ?>" class="p-2 rounded-lg hover:bg-[#1E293B] text-gray-400">
            <i class="lucide-arrow-left w-5 h-5"></i>
        </a>
        <div>
            <p class="text-sm text-indigo-400 font-medium"><?php echo e($category); ?></p>
            <h1 class="text-2xl font-bold"><?php echo e($title); ?></h1>
        </div>
    </div>

    <div class="card p-8 prose prose-invert max-w-none">
        <div class="text-gray-300 leading-relaxed">
            <?php echo $content; ?>
        </div>
    </div>

    <div class="card p-6 flex items-center justify-between">
        <div class="flex items-center gap-3 text-gray-400">
            <i class="lucide-thumbs-up w-5 h-5"></i>
            <span>Was this article helpful?</span>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 rounded-lg bg-[#1E293B] hover:bg-[#2D3748] text-sm">Yes</button>
            <button class="px-4 py-2 rounded-lg bg-[#1E293B] hover:bg-[#2D3748] text-sm">No</button>
        </div>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
