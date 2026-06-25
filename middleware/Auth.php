<?php
/**
 * Session Management and Authentication Helper
 */

require_once __DIR__ . '/../config/helpers.php';

class Auth {
    private static $instance = null;
    
    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Login user
     */
    public function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // Set session lifetime based on config
        ini_set('session.gc_maxlifetime', 7200);
        session_set_cookie_params(7200);
    }
    
    /**
     * Logout user
     */
    public function logout() {
        session_unset();
        session_destroy();
        
        // Delete session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }
    
    /**
     * Check if user is logged in
     */
    public function check() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Get current user ID
     */
    public function id() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     */
    public function role() {
        return $_SESSION['role'] ?? null;
    }
    
    /**
     * Get current user data
     */
    public function user() {
        if (!$this->check()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'],
            'full_name' => $_SESSION['full_name'],
            'role' => $_SESSION['role']
        ];
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($roles) {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        return in_array($this->role(), $roles);
    }
    
    /**
     * Check if user is super admin
     */
    public function isSuperAdmin() {
        return $this->hasRole('super_admin');
    }
    
    /**
     * Check if user is admin sekolah
     */
    public function isAdminSekolah() {
        return $this->hasRole('admin_sekolah');
    }
    
    /**
     * Check if user is guru
     */
    public function isGuru() {
        return $this->hasRole(['guru', 'wali_kelas']);
    }
    
    /**
     * Check if user is siswa
     */
    public function isSiswa() {
        return $this->hasRole('siswa');
    }
    
    /**
     * Check if user is orang tua
     */
    public function isOrangTua() {
        return $this->hasRole('orang_tua');
    }
    
    /**
     * Check if user is kepala sekolah
     */
    public function isKepalaSekolah() {
        return $this->hasRole('kepala_sekolah');
    }
    
    /**
     * Check if user is mentor industri
     */
    public function isMentorIndustri() {
        return $this->hasRole('mentor_industri');
    }
    
    /**
     * Require authentication, redirect to login if not authenticated
     */
    public function requireLogin($redirectUrl = null) {
        if (!$this->check()) {
            $url = $redirectUrl ?? url('auth/login.php');
            header("Location: {$url}");
            exit;
        }
    }
    
    /**
     * Require specific role, redirect if not authorized
     */
    public function requireRole($roles, $redirectUrl = null) {
        $this->requireLogin();
        
        if (!$this->hasRole($roles)) {
            $url = $redirectUrl ?? url('unauthorized.php');
            header("Location: {$url}");
            exit;
        }
    }
    
    /**
     * Set flash message
     */
    public function setFlash($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }
    
    /**
     * Get and clear flash message
     */
    public function getFlash($type = null) {
        if ($type === null) {
            $flash = $_SESSION['flash'] ?? [];
            unset($_SESSION['flash']);
            return $flash;
        }
        
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    
    /**
     * Regenerate session ID for security
     */
    public function regenerate() {
        session_regenerate_id(true);
        $_SESSION['login_time'] = time();
    }
    
    /**
     * Check session timeout
     */
    public function isSessionExpired($timeout = 7200) {
        if (!isset($_SESSION['login_time'])) {
            return true;
        }
        
        return (time() - $_SESSION['login_time']) > $timeout;
    }
}

// Helper functions
function auth() {
    return Auth::getInstance();
}

function isLoggedIn() {
    return auth()->check();
}

function currentUser() {
    return auth()->user();
}

function userRole() {
    return auth()->role();
}

function hasRole($roles) {
    return auth()->hasRole($roles);
}
