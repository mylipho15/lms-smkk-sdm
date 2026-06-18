<?php
/**
 * Helper Functions - Security Helpers
 * 
 * Fungsi pembantu untuk keamanan di LMS SMK Kesehatan SDM Sumedang
 */

/**
 * Sanitize input string
 * 
 * @param string $data Input data
 * @return string
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    
    // Remove HTML tags except allowed ones
    $allowedTags = '<p><br><strong><em><u><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><code><pre>';
    $data = strip_tags($data, $allowedTags);
    
    // Remove extra whitespace
    $data = trim($data);
    
    return $data;
}

/**
 * Escape HTML output
 * 
 * @param string $data Data to escape
 * @return string
 */
function e($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Deep escape for arrays
 * 
 * @param array $data Array to escape
 * @return array
 */
function e_array($data) {
    return array_map(function($value) {
        return is_array($value) ? e_array($value) : e($value);
    }, $data);
}

/**
 * Validate email format
 * 
 * @param string $email Email to validate
 * @return bool
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate URL format
 * 
 * @param string $url URL to validate
 * @return bool
 */
function is_valid_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Generate secure password hash
 * 
 * @param string $password Plain text password
 * @return string
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
}

/**
 * Verify password against hash
 * 
 * @param string $password Plain text password
 * @param string $hash Password hash
 * @return bool
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Check if password meets requirements
 * 
 * @param string $password Password to check
 * @return array Array with 'valid' boolean and 'errors' array
 */
function validate_password_strength($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password minimal 8 karakter';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password harus mengandung huruf kapital';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password harus mengandung huruf kecil';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password harus mengandung angka';
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Prevent XSS attack
 * 
 * @param string $data Input data
 * @return string
 */
function xss_clean($data) {
    // Remove javascript: protocol
    $data = preg_replace('/javascript:/i', '', $data);
    
    // Remove on* event handlers
    $data = preg_replace('/on\w+\s*=/i', '', $data);
    
    // Remove script tags
    $data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $data);
    
    return $data;
}

/**
 * Prevent SQL Injection - use prepared statements instead
 * This is a fallback for legacy code
 * 
 * @param string $data Input data
 * @return string
 */
function sql_escape($data) {
    // Note: Always use prepared statements instead of this function
    return addslashes($data);
}

/**
 * Validate file upload
 * 
 * @param array $file $_FILES array element
 * @param array $allowedTypes Allowed MIME types
 * @param int $maxSize Maximum file size in bytes
 * @return array Array with 'valid' boolean and 'error' string
 */
function validate_file_upload($file, $allowedTypes = [], $maxSize = MAX_UPLOAD_SIZE) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['valid' => false, 'error' => 'Tidak ada file yang diupload'];
    }
    
    // Check for upload errors
    $uploadErrors = [
        UPLOAD_ERR_INI_SIZE => 'File terlalu besar (php.ini)',
        UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (form)',
        UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
        UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
        UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension'
    ];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = $uploadErrors[$file['error']] ?? 'Error upload tidak diketahui';
        return ['valid' => false, 'error' => $error];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'error' => 'Ukuran file melebihi batas (' . format_file_size($maxSize) . ')'];
    }
    
    // Check file type by extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip'];
    
    if (!empty($allowedTypes) && !in_array($extension, $allowedTypes)) {
        return ['valid' => false, 'error' => 'Tipe file tidak diizinkan'];
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimeTypes = [
        'image/jpeg', 'image/png', 'image/gif',
        'application/pdf',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain', 'application/zip'
    ];
    
    if (!empty($allowedTypes) && !in_array($mimeType, $allowedMimeTypes)) {
        return ['valid' => false, 'error' => 'Tipe file tidak diizinkan (MIME type)'];
    }
    
    return ['valid' => true, 'error' => null];
}

/**
 * Generate secure random token
 * 
 * @param int $length Token length
 * @return string
 */
function generate_secure_token($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Rate limiting helper
 * 
 * @param string $identifier Unique identifier (e.g., IP address, user ID)
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $timeWindow Time window in seconds
 * @return bool True if within limit, false if exceeded
 */
function rate_limit($identifier, $maxAttempts = 5, $timeWindow = 300) {
    $key = 'rate_limit_' . md5($identifier);
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'reset_time' => time() + $timeWindow];
    }
    
    // Reset if time window expired
    if (time() > $_SESSION[$key]['reset_time']) {
        $_SESSION[$key] = ['count' => 0, 'reset_time' => time() + $timeWindow];
    }
    
    $_SESSION[$key]['count']++;
    
    return $_SESSION[$key]['count'] <= $maxAttempts;
}

/**
 * Get remaining rate limit attempts
 * 
 * @param string $identifier Unique identifier
 * @param int $maxAttempts Maximum attempts allowed
 * @return int
 */
function get_rate_limit_remaining($identifier, $maxAttempts = 5) {
    $key = 'rate_limit_' . md5($identifier);
    
    if (!isset($_SESSION[$key])) {
        return $maxAttempts;
    }
    
    return max(0, $maxAttempts - $_SESSION[$key]['count']);
}
