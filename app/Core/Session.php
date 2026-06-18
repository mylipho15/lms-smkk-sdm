<?php
/**
 * Session.php - Manajemen Session & Flash Messages
 * 
 * Session management untuk LMS SMK Kesehatan SDM Sumedang
 */

namespace App\Core;

class Session {
    /**
     * Start session if not already started
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Set session value
     * 
     * @param string $key Session key
     * @param mixed $value Session value
     * @return void
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     * 
     * @param string $key Session key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     * 
     * @param string $key Session key
     * @return bool
     */
    public function has($key) {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session value
     * 
     * @param string $key Session key
     * @return void
     */
    public function remove($key) {
        unset($_SESSION[$key]);
    }
    
    /**
     * Clear all session data
     * 
     * @return void
     */
    public function clear() {
        $_SESSION = [];
    }
    
    /**
     * Destroy session completely
     * 
     * @return void
     */
    public function destroy() {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    /**
     * Regenerate session ID (for security after login)
     * 
     * @return void
     */
    public function regenerate() {
        session_regenerate_id(true);
    }
    
    /**
     * Set flash message
     * 
     * @param string $key Flash key
     * @param string $message Flash message
     * @return void
     */
    public function setFlash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }
    
    /**
     * Get and remove flash message
     * 
     * @param string $key Flash key
     * @return string|null
     */
    public function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
    
    /**
     * Check if flash message exists
     * 
     * @param string $key Flash key
     * @return bool
     */
    public function hasFlash($key) {
        return isset($_SESSION['flash'][$key]);
    }
    
    /**
     * Set success flash message
     * 
     * @param string $message Message
     * @return void
     */
    public function success($message) {
        $this->setFlash('success', $message);
    }
    
    /**
     * Set error flash message
     * 
     * @param string $message Message
     * @return void
     */
    public function error($message) {
        $this->setFlash('error', $message);
    }
    
    /**
     * Set warning flash message
     * 
     * @param string $message Message
     * @return void
     */
    public function warning($message) {
        $this->setFlash('warning', $message);
    }
    
    /**
     * Set info flash message
     * 
     * @param string $message Message
     * @return void
     */
    public function info($message) {
        $this->setFlash('info', $message);
    }
    
    /**
     * Get all flash messages
     * 
     * @return array
     */
    public function getAllFlash() {
        $flashes = $_SESSION['flash'] ?? [];
        $_SESSION['flash'] = [];
        return $flashes;
    }
    
    /**
     * Set user session data after login
     * 
     * @param array $user User data
     * @return void
     */
    public function setUser($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
    }
    
    /**
     * Get current user ID
     * 
     * @return int|null
     */
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     * 
     * @return string|null
     */
    public function getRole() {
        return $_SESSION['role'] ?? null;
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    public function isLoggedIn() {
        return $_SESSION['logged_in'] ?? false;
    }
    
    /**
     * Get session ID
     * 
     * @return string
     */
    public function getId() {
        return session_id();
    }
    
    /**
     * Set CSRF token
     * 
     * @return string
     */
    public function generateCsrfToken() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }
    
    /**
     * Verify CSRF token
     * 
     * @param string $token Token to verify
     * @return bool
     */
    public function verifyCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
