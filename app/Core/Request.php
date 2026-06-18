<?php
/**
 * Request.php - Menangani Input Request
 * 
 * Menangani $_GET, $_POST, $_FILES untuk LMS SMK Kesehatan SDM Sumedang
 */

namespace App\Core;

class Request {
    /**
     * Get all GET parameters
     * 
     * @return array
     */
    public function getQueryParams() {
        return $_GET;
    }
    
    /**
     * Get all POST parameters
     * 
     * @return array
     */
    public function getPostParams() {
        return $_POST;
    }
    
    /**
     * Get specific GET parameter
     * 
     * @param string $key Parameter name
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function get($key, $default = null) {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }
    
    /**
     * Get specific POST parameter
     * 
     * @param string $key Parameter name
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function post($key, $default = null) {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }
    
    /**
     * Get all request input (GET + POST)
     * 
     * @return array
     */
    public function all() {
        return array_merge($_GET, $_POST);
    }
    
    /**
     * Check if request has parameter
     * 
     * @param string $key Parameter name
     * @return bool
     */
    public function has($key) {
        return isset($_GET[$key]) || isset($_POST[$key]);
    }
    
    /**
     * Get uploaded file
     * 
     * @param string $key File input name
     * @return array|null
     */
    public function file($key) {
        return isset($_FILES[$key]) ? $_FILES[$key] : null;
    }
    
    /**
     * Check if file was uploaded
     * 
     * @param string $key File input name
     * @return bool
     */
    public function hasFile($key) {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }
    
    /**
     * Get request method
     * 
     * @return string
     */
    public function method() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Check if request is AJAX
     * 
     * @return bool
     */
    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Get request URI
     * 
     * @return string
     */
    public function uri() {
        return $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Get client IP address
     * 
     * @return string
     */
    public function ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Get user agent
     * 
     * @return string
     */
    public function userAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
    
    /**
     * Sanitize input data
     * 
     * @param mixed $data Data to sanitize
     * @return mixed
     */
    private function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        // Don't sanitize if it's already a string with HTML (for rich text editors)
        // Just trim whitespace
        return is_string($data) ? trim($data) : $data;
    }
    
    /**
     * Validate CSRF token
     * 
     * @param string $token Token to validate
     * @return bool
     */
    public function validateCsrfToken($token) {
        session_start();
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }
    
    /**
     * Get JSON input from request body
     * 
     * @return array|null
     */
    public function json() {
        $input = file_get_contents('php://input');
        return json_decode($input, true);
    }
}
