<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $channelId = $_POST['channelId'] ?? '';

    if (empty($title) || empty($content) || empty($channelId)) {
        $_SESSION['error'] = "All fields are required";
    } else {
        $stmt = $pdo->prepare("INSERT INTO forum_posts (channelId, userId, title, content) VALUES (?, ?, ?, ?)");
        $stmt->execute([$channelId, $_SESSION['user_id'], $title, $content]);
        $postId = $pdo->lastInsertId();

        header("Location: forum_post.php?id=$postId");
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM forum_channels ORDER BY name ASC");
$channels = $stmt->fetchAll();

$page_title = "Start New Discussion";
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="forum.php" class="p-2 rounded-lg hover:bg-[#1E293B] text-gray-400">
            <i class="lucide-arrow-left w-5 h-5"></i>
        </a>
        <h1 class="text-2xl font-bold">Start New Discussion</h1>
    </div>

    <form action="forum_create.php" method="POST" class="card p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

        <div class="space-y-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium">Channel</label>
                <select name="channelId" class="input bg-[#0B1120]" required>
                    <option value="">Select a channel...</option>
                    <?php foreach ($channels as $channel): ?>
                        <option value="<?php echo $channel['id']; ?>"><?php echo e($channel['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Title</label>
                <input name="title" type="text" placeholder="What's on your mind?" class="input" required>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Content</label>
                <textarea name="content" rows="10" placeholder="Share your thoughts, ask a question, or post a guide..." class="input" required></textarea>
            </div>
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3">
            Post Discussion
            <i class="lucide-send w-5 h-5"></i>
        </button>
    </form>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
