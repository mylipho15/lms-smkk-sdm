<?php
/**
 * Helper Functions - URL Helpers
 * 
 * Fungsi pembantu untuk URL dan redirect di LMS SMK Kesehatan SDM Sumedang
 */

/**
 * Get base URL
 * 
 * @param string $path Optional path to append
 * @return string
 */
function base_url($path = '') {
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Get assets URL
 * 
 * @param string $path Path to asset
 * @return string
 */
function asset($path = '') {
    return URL_ASSETS . '/' . ltrim($path, '/');
}

/**
 * Get uploads URL
 * 
 * @param string $path Path to uploaded file
 * @return string
 */
function upload_url($path = '') {
    return URL_UPLOADS . '/' . ltrim($path, '/');
}

/**
 * Redirect to URL
 * 
 * @param string $url URL to redirect to
 * @return void
 */
function redirect($url) {
    header("Location: {$url}");
    exit;
}

/**
 * Redirect back to previous page
 * 
 * @return void
 */
function redirect_back() {
    $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL;
    redirect($referer);
}

/**
 * Get current URL
 * 
 * @return string
 */
function current_url() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') 
           . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
}

/**
 * Check if current URL matches given path
 * 
 * @param string $path Path to check
 * @return bool
 */
function is_current_url($path) {
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return rtrim($currentPath, '/') === '/' . trim($path, '/');
}

/**
 * Generate CSRF token
 * 
 * @return string
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generate CSRF input field
 * 
 * @return string
 */
function csrf_field() {
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get method input
 * 
 * @param string $key Input key
 * @param mixed $default Default value
 * @return mixed
 */
function old($key, $default = '') {
    return $_POST[$key] ?? $default;
}
