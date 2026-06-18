<?php
/**
 * Front Controller - Entry Point untuk semua request
 * 
 * LMS SMK Kesehatan SDM Sumedang
 */

// Load configuration
require_once __DIR__ . '/../config/app.php';

// Load helper functions
require_once BASE_PATH . '/app/Helpers/url.php';
require_once BASE_PATH . '/app/Helpers/format.php';
require_once BASE_PATH . '/app/Helpers/security.php';

// Autoload Core classes
spl_autoload_register(function ($class) {
    // Check if it's an App namespace class
    if (strpos($class, 'App\\') === 0) {
        $relativeClass = substr($class, strlen('App\\'));
        $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Start session
session_start();

// Initialize and run the application
$app = new App\Core\App();
