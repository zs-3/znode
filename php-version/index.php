<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (is_logged_in()) {
    header("Location: dashboard.php");
} else {
    header("Location: landing.php");
}
exit;
