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
        <a href="tickets.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo in_array($current_page, ['tickets.php', 'ticket_view.php']) ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
            <i class="lucide-ticket w-5 h-5"></i>
            <span>Support Tickets</span>
        </a>
        <a href="settings.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'settings.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
            <i class="lucide-settings w-5 h-5"></i>
            <span>System Settings</span>
        </a>
        <a href="backup.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'backup.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
            <i class="lucide-database w-5 h-5"></i>
            <span>Backup & Restore</span>
        </a>

        <div class="pt-4 pb-2">
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Communication</p>
            <a href="send_mail.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'send_mail.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
                <i class="lucide-mail w-5 h-5"></i>
                <span>Send Email</span>
            </a>
            <a href="email_history.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'email_history.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
                <i class="lucide-history w-5 h-5"></i>
                <span>Email History</span>
            </a>
            <a href="email_templates.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'email_templates.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
                <i class="lucide-file-text w-5 h-5"></i>
                <span>Email Templates</span>
            </a>
        </div>

        <div class="pt-2 pb-2">
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Domains</p>
            <a href="allowed_domains.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?php echo $current_page == 'allowed_domains.php' ? 'bg-[#818CF8] text-white shadow-md' : 'text-gray-400 hover:bg-[#1E293B] hover:text-white'; ?>">
                <i class="lucide-globe w-5 h-5"></i>
                <span>Allowed Domains</span>
            </a>
        </div>

        <div class="mt-auto pt-4">
            <a href="../index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-[#1E293B] hover:text-white">
                <i class="lucide-arrow-left w-5 h-5"></i>
                <span>User Dashboard</span>
            </a>
        </div>
    </nav>
</aside>
