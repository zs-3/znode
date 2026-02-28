<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

$token = $_GET['token'] ?? '';

if (!empty($token)) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE verificationToken = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare("UPDATE users SET emailVerified = 1, verificationToken = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);
        $_SESSION['success'] = "Email verified successfully. You can now login.";
    } else {
        $_SESSION['error'] = "Invalid or expired verification token.";
    }
} else {
    $_SESSION['error'] = "Verification token is missing.";
}

header("Location: login.php");
exit;
