<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: forum.php");
    exit;
}

// Handle comment POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $content = $_POST['content'] ?? '';

    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO forum_comments (postId, userId, content) VALUES (?, ?, ?)");
        $stmt->execute([$id, $_SESSION['user_id'], $content]);

        header("Location: forum_post.php?id=$id&success=Comment added");
        exit;
    }
}

// Fetch post
$stmt = $pdo->prepare("SELECT forum_posts.*, users.name as author, forum_channels.name as channel_name FROM forum_posts JOIN users ON forum_posts.userId = users.id JOIN forum_channels ON forum_posts.channelId = forum_channels.id WHERE forum_posts.id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    header("Location: forum.php");
    exit;
}

// Fetch comments
$stmt = $pdo->prepare("SELECT forum_comments.*, users.name FROM forum_comments JOIN users ON forum_comments.userId = users.id WHERE postId = ? ORDER BY createdAt ASC");
$stmt->execute([$id]);
$comments = $stmt->fetchAll();

$page_title = $post['title'] . " - Community Forum";
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="forum.php" class="p-2 rounded-lg hover:bg-[#1E293B] text-gray-400">
            <i class="lucide-arrow-left w-5 h-5"></i>
        </a>
        <div>
            <span class="text-[10px] font-bold uppercase text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded"><?php echo e($post['channel_name']); ?></span>
            <h1 class="text-2xl font-bold mt-1"><?php echo e($post['title']); ?></h1>
            <p class="text-xs text-gray-500">Posted by <?php echo e($post['author']); ?> • <?php echo e($post['createdAt']); ?></p>
        </div>
    </div>

    <!-- Main Post -->
    <div class="card p-8">
        <div class="text-gray-300 leading-relaxed whitespace-pre-wrap"><?php echo e($post['content']); ?></div>
    </div>

    <h2 class="text-lg font-bold flex items-center gap-2">
        <i class="lucide-message-circle w-5 h-5 text-indigo-400"></i>
        Comments (<?php echo count($comments); ?>)
    </h2>

    <!-- Comments -->
    <div class="space-y-4">
        <?php foreach ($comments as $comment): ?>
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#1E293B] flex items-center justify-center text-xs font-bold">
                            <?php echo strtoupper(substr($comment['name'], 0, 1)); ?>
                        </div>
                        <span class="font-medium text-sm"><?php echo e($comment['name']); ?></span>
                    </div>
                    <span class="text-[10px] text-gray-500"><?php echo e($comment['createdAt']); ?></span>
                </div>
                <div class="text-gray-400 text-sm whitespace-pre-wrap"><?php echo e($comment['content']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Comment Form -->
    <form action="forum_post.php?id=<?php echo e($id); ?>" method="POST" class="card p-6 space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <div class="space-y-2">
            <label class="block text-sm font-medium">Add Comment</label>
            <textarea name="content" rows="4" placeholder="Share your thoughts..." class="input" required></textarea>
        </div>
        <button type="submit" class="btn-primary">
            Post Comment
            <i class="lucide-message-square w-4 h-4"></i>
        </button>
    </form>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
