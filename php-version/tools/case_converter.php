<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit;
}

$input = $_POST['input'] ?? '';
$action = $_POST['action'] ?? 'uppercase';
$output = '';

if (!empty($input)) {
    switch ($action) {
        case 'uppercase': $output = strtoupper($input); break;
        case 'lowercase': $output = strtolower($input); break;
        case 'titlecase': $output = ucwords(strtolower($input)); break;
        case 'sentencecase': $output = ucfirst(strtolower($input)); break;
    }
}

$page_title = "Case Converter";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php
    $current_page = 'case_converter.php';
    include '../templates/sidebar.php';
    ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Case Converter</h1>
            <form action="case_converter.php" method="POST" class="card p-6 space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <textarea name="input" rows="6" class="input font-mono text-sm" placeholder="Paste your text here..."><?php echo e($input); ?></textarea>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <button name="action" value="uppercase" class="px-3 py-2 rounded bg-[#1E293B] text-xs font-bold uppercase hover:bg-indigo-500 transition-colors">UPPERCASE</button>
                    <button name="action" value="lowercase" class="px-3 py-2 rounded bg-[#1E293B] text-xs font-bold uppercase hover:bg-indigo-500 transition-colors">lowercase</button>
                    <button name="action" value="titlecase" class="px-3 py-2 rounded bg-[#1E293B] text-xs font-bold uppercase hover:bg-indigo-500 transition-colors">Title Case</button>
                    <button name="action" value="sentencecase" class="px-3 py-2 rounded bg-[#1E293B] text-xs font-bold uppercase hover:bg-indigo-500 transition-colors">Sentence case</button>
                </div>
            </form>
            <?php if (!empty($output)): ?>
                <div class="card p-6 space-y-2">
                    <label class="block text-sm font-medium text-gray-400">Result</label>
                    <textarea readonly class="input font-mono text-sm bg-[#0B1120] text-emerald-400" rows="6"><?php echo e($output); ?></textarea>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
