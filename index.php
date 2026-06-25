<?php
/**
 * Main Entry Point
 * LMS SMK Kesehatan SDM Sumedang
 */

// Error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load helpers for URL generation
require_once __DIR__ . '/config/helpers.php';

// Check if user is logged in, redirect to dashboard or login
require_once __DIR__ . '/middleware/Auth.php';

if (auth()->check()) {
    redirect('/dashboard.php');
} else {
    redirect('/auth/login.php');
}
exit;
