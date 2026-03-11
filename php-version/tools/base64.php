<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit;
}

$input = $_POST['input'] ?? '';
$action = $_POST['action'] ?? 'encode';
$output = '';

if (!empty($input)) {
    if ($action === 'encode') {
        $output = base64_encode($input);
    } else {
        $output = base64_decode($input);
    }
}

$page_title = "Base64 Tool";
include '../templates/header.php';
?>

<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php
    $current_page = 'base64.php';
    include '../templates/sidebar.php';
    ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Base64 Encode/Decode</h1>

            <form action="base64.php" method="POST" class="card p-6 space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Input Text</label>
                    <textarea name="input" rows="6" class="input font-mono text-sm" placeholder="Paste your text here..."><?php echo e($input); ?></textarea>
                </div>

                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="action" value="encode" <?php echo $action === 'encode' ? 'checked' : ''; ?> class="w-4 h-4 text-indigo-500">
                        <span>Encode</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="action" value="decode" <?php echo $action === 'decode' ? 'checked' : ''; ?> class="w-4 h-4 text-indigo-500">
                        <span>Decode</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary">Process Text</button>
            </form>

            <?php if (!empty($output)): ?>
                <div class="card p-6 space-y-2">
                    <label class="block text-sm font-medium text-gray-400">Result</label>
                    <pre class="bg-[#0B1120] p-4 rounded-lg border border-[#1E293B] overflow-x-auto text-emerald-400 font-mono text-sm"><?php echo e($output); ?></pre>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
