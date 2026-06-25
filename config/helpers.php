<?php
/**
 * Helper Functions for URL and Path Management
 * LMS SMK Kesehatan SDM Sumedang
 */

// Load configuration
$config = require __DIR__ . '/database.php';
$appConfig = $config['app'];

// Define base path constant
define('BASE_PATH', $appConfig['base_path'] ?? '');
define('APP_URL', $appConfig['url'] ?? 'http://localhost/lms-smkk-sdm');

/**
 * Generate absolute URL with base path
 */
function url($path = '') {
    $basePath = BASE_PATH;
    $path = ltrim($path, '/');
    
    if (empty($path)) {
        return APP_URL;
    }
    
    return APP_URL . '/' . $path;
}

/**
 * Generate asset URL
 */
function asset($path) {
    $path = ltrim($path, '/');
    return APP_URL . '/' . $path;
}

/**
 * Get base path for relative URLs
 */
function base_path($path = '') {
    $basePath = BASE_PATH;
    $path = ltrim($path, '/');
    
    if (empty($path)) {
        return $basePath;
    }
    
    return $basePath . '/' . $path;
}

/**
 * Redirect to a URL
 */
function redirect($path = '/') {
    $url = url($path);
    header("Location: {$url}");
    exit;
}
