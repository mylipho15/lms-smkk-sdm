<?php
/**
 * Configuration Aplikasi - app.php
 * 
 * Konstanta global, URL dasar, timezone untuk LMS SMK Kesehatan SDM Sumedang
 */

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            
            // Replace environment variables in value
            $value = preg_replace_callback('/\$\{([A-Z_]+)\}/', function($matches) {
                return getenv($matches[1]) ?: '';
            }, $value);
            
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// Load .env file
loadEnv(__DIR__ . '/../.env');

// Application Constants
define('APP_NAME', getenv('APP_NAME') ?: 'LMS SMKK SDM');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost/lms-smkk-sdm/public');
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_DEBUG', filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN));
define('APP_TIMEZONE', getenv('APP_TIMEZONE') ?: 'Asia/Jakarta');

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

// Base Path
define('BASE_PATH', dirname(__DIR__));

// URL Paths
define('URL_ASSETS', APP_URL . '/assets');
define('URL_UPLOADS', APP_URL . '/../storage/uploads');

// File Upload Settings
define('MAX_UPLOAD_SIZE', (int)(getenv('MAX_UPLOAD_SIZE') ?: 10485760)); // 10MB default
define('ALLOWED_EXTENSIONS', explode(',', getenv('ALLOWED_EXTENSIONS') ?: 'pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png'));

// Pagination
define('PER_PAGE', (int)(getenv('PER_PAGE') ?: 10));

// Session Configuration
define('SESSION_LIFETIME', (int)(getenv('SESSION_LIFETIME') ?: 120));

// Security
define('HASH_COST', (int)(getenv('HASH_COST') ?: 10));

// Application Version
define('APP_VERSION', '1.0.0');

// Roles Constants
define('ROLE_SUPER_ADMIN', 'super_admin');
define('ROLE_ADMIN_SEKOLAH', 'admin_sekolah');
define('ROLE_GURU', 'guru');
define('ROLE_SISWA', 'siswa');
define('ROLE_WALI_KELAS', 'wali_kelas');
define('ROLE_ORANG_TUA', 'orang_tua');
define('ROLE_KEPALA_SEKOLAH', 'kepala_sekolah');
define('ROLE_MENTOR_INDUSTRI', 'mentor_industri');
