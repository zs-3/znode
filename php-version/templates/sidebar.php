<?php
$current_user = get_current_user_data();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="min-h-screen flex w-full bg-[#0B1120]">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-[#111827] border-r border-[#1E293B]">
        <!-- Logo -->
        <div class="flex items-center h-16 px-4 border-b border-[#1E293B]">
            <a href="index.php" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-lg bg-gradient-primary flex items-center justify-center">
                    <i class="lucide-server w-5 h-5 text-white"></i>
                </div>
                <span class="text-lg font-bold text-white"><?php echo SITE_NAME; ?></span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 py-4 px-2 space-y-1 overflow-y-auto">
            <a href="index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'index.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
                <i class="lucide-layout-dashboard w-5 h-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="hosting.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo in_array($current_page, ['hosting.php', 'hosting_create.php', 'hosting_details.php']) ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
                <i class="lucide-hard-drive w-5 h-5"></i>
                <span>Hosting Accounts</span>
            </a>
            <a href="ssl.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'ssl.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
                <i class="lucide-shield w-5 h-5"></i>
                <span>SSL Certificates</span>
            </a>
            <a href="tickets.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo in_array($current_page, ['tickets.php', 'ticket_create.php', 'ticket_view.php']) ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
                <i class="lucide-ticket w-5 h-5"></i>
                <span>Support Tickets</span>
            </a>
            <a href="forum.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'forum.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
                <i class="lucide-message-square w-5 h-5"></i>
                <span>Community Forum</span>
            </a>
        </nav>

        <!-- User section -->
        <div class="p-4 border-t border-[#1E293B]">
            <div class="flex items-center gap-3 px-2 py-2 rounded-lg">
                <div class="w-9 h-9 rounded-full bg-[#1E293B] flex items-center justify-center text-white font-medium">
                    <?php echo strtoupper(substr($current_user['email'], 0, 1)); ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate"><?php echo $current_user['name']; ?></p>
                    <p class="text-xs text-gray-500 truncate"><?php echo $current_user['email']; ?></p>
                </div>
                <a href="logout.php" class="text-gray-500 hover:text-red-400">
                    <i class="lucide-log-out w-4 h-4"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6">
