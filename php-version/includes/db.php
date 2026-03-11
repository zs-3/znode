<?php
require_once 'config.php';

try {
    // Prioritize MySQL configuration if set (from installer)
    if (defined('DB_HOST') && DB_HOST !== 'localhost' && DB_HOST !== '') {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    } else {
        // Fallback to SQLite for sandbox/development if MySQL is not configured
        $db_path = __DIR__ . '/../znode_php.sqlite';
        $pdo = new PDO("sqlite:" . $db_path);
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
