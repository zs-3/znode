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

// Fetch channels
$stmt = $pdo->query("SELECT forum_channels.*, (SELECT COUNT(*) FROM forum_posts WHERE channelId = forum_channels.id) as post_count FROM forum_channels ORDER BY name ASC");
$channels = $stmt->fetchAll();

// Fetch recent posts
$stmt = $pdo->query("SELECT forum_posts.*, users.name as author, forum_channels.name as channel_name FROM forum_posts JOIN users ON forum_posts.userId = users.id JOIN forum_channels ON forum_posts.channelId = forum_channels.id ORDER BY createdAt DESC LIMIT 10");
$recent_posts = $stmt->fetchAll();
?>

<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Community Forum</h1>
            <p class="text-gray-400">Connect, share, and learn from other users.</p>
        </div>
        <a href="forum_create.php" class="btn-primary">
            <i class="lucide-plus w-4 h-4"></i>
            New Discussion
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Channels -->
        <div class="lg:col-span-1 space-y-4">
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Channels</h2>
            <div class="card overflow-hidden">
                <div class="divide-y divide-[#1E293B]">
                    <?php if (empty($channels)): ?>
                        <div class="p-4 text-xs text-gray-500 italic">No channels created.</div>
                    <?php else: ?>
                        <?php foreach ($channels as $channel): ?>
                            <a href="forum.php?channel=<?php echo $channel['id']; ?>" class="p-4 flex items-center justify-between hover:bg-[#1E293B]/50 transition-colors block">
                                <div>
                                    <h3 class="font-medium text-sm"><?php echo e($channel['name']); ?></h3>
                                    <p class="text-[10px] text-gray-500"><?php echo e($channel['description']); ?></p>
                                </div>
                                <span class="text-xs bg-[#1E293B] px-2 py-1 rounded text-gray-400"><?php echo $channel['post_count']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Posts -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Recent Discussions</h2>
            <div class="card overflow-hidden">
                <div class="divide-y divide-[#1E293B]">
                    <?php if (empty($recent_posts)): ?>
                        <div class="p-12 text-center text-gray-500">No discussions yet. Be the first to start one!</div>
                    <?php else: ?>
                        <?php foreach ($recent_posts as $post): ?>
                            <a href="forum_post.php?id=<?php echo $post['id']; ?>" class="p-6 hover:bg-[#1E293B]/50 transition-colors block group">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] font-bold uppercase text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded"><?php echo e($post['channel_name']); ?></span>
                                    <span class="text-[10px] text-gray-500">Posted by <?php echo e($post['author']); ?> • <?php echo e($post['createdAt']); ?></span>
                                </div>
                                <h3 class="text-lg font-bold group-hover:text-indigo-400 transition-colors"><?php echo e($post['title']); ?></h3>
                                <p class="text-sm text-gray-400 line-clamp-2 mt-1"><?php echo e(substr(strip_tags($post['content']), 0, 150)); ?>...</p>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
