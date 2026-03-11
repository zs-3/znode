<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $subdomain = strtolower($_POST['subdomain'] ?? '');
    $domain = $_POST['domain'] ?? '';
    $label = $_POST['label'] ?? '';

    // Simple validation
    if (empty($subdomain) || empty($domain)) {
        $_SESSION['error'] = "Subdomain and domain are required";
    } elseif (!preg_match('/^[a-z0-9]{3,8}$/', $subdomain)) {
        $_SESSION['error'] = "Subdomain must be 3-8 lowercase letters or numbers";
    } else {
        $full_domain = "$subdomain.$domain";
        $password = bin2hex(random_bytes(6)); // Random 12 char hex password

        // Call MOFH API
        $mofh_response = mofh_api_request('createacct.php', [
            'username' => $subdomain,
            'password' => $password,
            'contactemail' => $_SESSION['email'],
            'domain' => $full_domain,
            'plan' => 'free' // Should come from config
        ]);

        if (isset($mofh_response['result'][0]['status']) && $mofh_response['result'][0]['status'] == 1) {
            $vpUsername = $mofh_response['result'][0]['options']['vpusername'];

            // Save to DB
            $stmt = $pdo->prepare("INSERT INTO hostings (userId, vpUsername, username, password, domain, status, createdAt) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$_SESSION['user_id'], $vpUsername, $subdomain, $password, $full_domain, 'PENDING']);

            header("Location: index.php?success=Account created");
            exit;
        } else {
            $_SESSION['error'] = $mofh_response['result'][0]['statusmsg'] ?? "Failed to create account";
        }
    }
}

$page_title = "Create Hosting";
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Create Hosting Account</h1>
        <p class="text-gray-400">Launch your new website in seconds.</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-3 rounded-lg">
            <?php echo e($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="hosting_create.php" method="POST" class="card p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Subdomain</label>
                    <input name="subdomain" type="text" placeholder="mysite" class="input" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Domain Extension</label>
                    <select name="domain" class="input bg-[#0B1120]">
                        <option value="epizy.com">epizy.com</option>
                        <option value="rf.gd">rf.gd</option>
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium">Account Label (Optional)</label>
                <input name="label" type="text" placeholder="My Blog" class="input">
            </div>
        </div>

        <div class="bg-[#1E293B] p-4 rounded-lg flex items-start gap-3">
            <i class="lucide-info w-5 h-5 text-indigo-400 shrink-0"></i>
            <p class="text-xs text-gray-400">Your account will be created instantly, but DNS propagation might take a few minutes to a few hours.</p>
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3">
            Create Account
            <i class="lucide-rocket w-5 h-5"></i>
        </button>
    </form>
</div>

<?php
echo "</main></div></div>";
include 'templates/footer.php';
?>
