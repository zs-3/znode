<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-[#111827] border-r border-[#1E293B]">
    <div class="flex items-center h-16 px-4 border-b border-[#1E293B]">
        <a href="index.php" class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-lg bg-gradient-primary flex items-center justify-center">
                <i class="lucide-shield w-5 h-5 text-white"></i>
            </div>
            <span class="text-lg font-bold text-white">Admin Panel</span>
        </a>
    </div>
    <nav class="flex-1 py-4 px-2 space-y-1">
        <a href="index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'index.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
            <i class="lucide-layout-dashboard w-5 h-5"></i>
            <span>Dashboard</span>
        </a>
        <a href="users.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'users.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
            <i class="lucide-users w-5 h-5"></i>
            <span>Users</span>
        </a>
        <a href="hostings.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'hostings.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
            <i class="lucide-hard-drive w-5 h-5"></i>
            <span>Hosting Accounts</span>
        </a>
        <div class="mt-auto pt-4">
            <a href="../index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-[#1E293B] hover:text-white">
                <i class="lucide-arrow-left w-5 h-5"></i>
                <span>User Dashboard</span>
            </a>
        </div>
    </nav>
</aside>
