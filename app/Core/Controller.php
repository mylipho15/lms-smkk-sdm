<?php
/**
 * Controller.php - Base Controller
 * 
 * Base Controller untuk semua controller di LMS SMK Kesehatan SDM Sumedang
 */

namespace App\Core;

class Controller {
    protected $session;
    protected $request;
    protected $response;
    
    public function __construct() {
        $this->session = new Session();
        $this->request = new Request();
        $this->response = new Response();
    }
    
    /**
     * Load view dengan data
     * 
     * @param string $view Path ke view (tanpa .php)
     * @param array $data Data yang akan dikirim ke view
     * @return void
     */
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = BASE_PATH . "/resources/views/{$view}.php";
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new \Exception("View {$view} not found at {$viewFile}");
        }
    }
    
    /**
     * Load layout dengan content
     * 
     * @param string $layout Nama layout
     * @param string $content Content HTML
     * @param array $data Data untuk layout
     * @return void
     */
    protected function renderLayout($layout, $content, $data = []) {
        $layoutFile = BASE_PATH . "/resources/views/layouts/{$layout}.php";
        
        if (file_exists($layoutFile)) {
            extract($data);
            require_once $layoutFile;
        } else {
            throw new \Exception("Layout {$layout} not found");
        }
    }
    
    /**
     * Redirect ke URL lain
     * 
     * @param string $url URL tujuan
     * @return void
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Return JSON response
     * 
     * @param mixed $data Data yang akan di-encode ke JSON
     * @param int $statusCode HTTP status code
     * @return void
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Cek apakah user sudah login
     * 
     * @return bool
     */
    protected function isAuthenticated() {
        return $this->session->has('user_id');
    }
    
    /**
     * Dapatkan user yang sedang login
     * 
     * @return array|null
     */
    protected function getCurrentUser() {
        return $this->session->get('user');
    }
    
    /**
     * Cek role user
     * 
     * @param string|array $roles Role yang diperbolehkan
     * @return bool
     */
    protected function hasRole($roles) {
        $userRole = $this->session->get('role');
        
        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }
        
        return $userRole === $roles;
    }
    
    /**
     * Set flash message
     * 
     * @param string $key
     * @param string $message
     * @return void
     */
    protected function setFlash($key, $message) {
        $this->session->setFlash($key, $message);
    }
    
    /**
     * Get flash message
     * 
     * @param string $key
     * @return string|null
     */
    protected function getFlash($key) {
        return $this->session->getFlash($key);
    }
}
