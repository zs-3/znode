<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit;
}

$page_title = "Color Tools";
include '../templates/header.php';
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <?php
    $current_page = 'color_tools.php';
    include '../templates/sidebar.php';
    ?>

    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 space-y-6">
            <h1 class="text-2xl font-bold">Color Converter</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card p-6 space-y-4">
                    <h2 class="font-bold">Color Picker</h2>
                    <input type="color" id="picker" class="w-full h-32 bg-transparent cursor-pointer rounded-lg border border-[#1E293B]" value="#818CF8">
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div class="p-3 bg-[#0B1120] rounded-lg border border-[#1E293B]">
                            <span class="text-gray-400 block mb-1">HEX</span>
                            <span id="hex-val" class="font-mono text-indigo-400">#818CF8</span>
                        </div>
                        <div class="p-3 bg-[#0B1120] rounded-lg border border-[#1E293B]">
                            <span class="text-gray-400 block mb-1">RGB</span>
                            <span id="rgb-val" class="font-mono text-indigo-400">129, 140, 248</span>
                        </div>
                    </div>
                </div>

                <div id="preview" class="card p-6 flex flex-col items-center justify-center space-y-4 bg-[#818CF8]">
                    <div class="text-white text-4xl font-black drop-shadow-lg uppercase tracking-widest">Preview</div>
                </div>
            </div>

            <script>
                const picker = document.getElementById('picker');
                const hexVal = document.getElementById('hex-val');
                const rgbVal = document.getElementById('rgb-val');
                const preview = document.getElementById('preview');

                picker.addEventListener('input', (e) => {
                    const color = e.target.value;
                    hexVal.textContent = color.toUpperCase();

                    const r = parseInt(color.slice(1, 3), 16);
                    const g = parseInt(color.slice(3, 5), 16);
                    const b = parseInt(color.slice(5, 7), 16);
                    rgbVal.textContent = `${r}, ${g}, ${b}`;

                    preview.style.backgroundColor = color;
                });
            </script>
        </main>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
