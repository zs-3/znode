<?php
require_once __DIR__ . '/config.php';

/**
 * Perform a MOFH API Request
 */
function mofh_api_request($endpoint, $data = [], $method = 'POST') {
    $url = "https://panel.myownfreehost.net/json-api/" . $endpoint;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, MOFH_API_USER . ":" . MOFH_API_PASS);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => $error];
    }

    return json_decode($response, true);
}

/**
 * Get current user
 */
function get_current_user_data() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    // In a real app, you would fetch from DB here
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['email'],
        'name' => $_SESSION['name'] ?? 'User'
    ];
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN';
}

/**
 * Redirect with error message
 */
function redirect_with_error($path, $message) {
    $_SESSION['error'] = $message;
    header("Location: " . BASE_URL . $path);
    exit;
}

/**
 * Escape HTML output
 */
function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF Token generation
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verify_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }
}

/**
 * Generate File Manager Link
 */
function get_file_manager_link($username, $password) {
    // XOR + Base64 encoding (simplistic version)
    $key = 'ERFgjowETHGj9wf';
    $out = '';
    for ($i = 0; $i < strlen($password); $i++) {
        $out .= chr(ord($password[$i]) ^ ord($key[$i % strlen($key)]));
    }
    $p = base64_encode($out);
    return "https://filemanager.ai/new3/index.php?u=" . urlencode($username) . "&p=" . urlencode($p);
}

/**
 * Send Email using plain PHP mail()
 */
function send_email($to, $subject, $body) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <noreply@' . $_SERVER['HTTP_HOST'] . '>' . "\r\n";

    return mail($to, $subject, $body, $headers);
}
