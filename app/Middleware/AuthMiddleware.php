<?php
/**
 * AuthMiddleware.php - Cek apakah user sudah login
 * 
 * Middleware untuk autentikasi pengguna LMS SMK Kesehatan SDM Sumedang
 */

namespace App\Middleware;

use App\Core\Session;
use App\Core\Response;

class AuthMiddleware {
    protected $session;
    protected $response;
    
    public function __construct() {
        $this->session = new Session();
        $this->response = new Response();
    }
    
    /**
     * Handle authentication check
     * 
     * @return bool True if authenticated, false otherwise
     */
    public function handle() {
        if (!$this->session->isLoggedIn()) {
            // Store intended URL before redirect
            $this->session->set('intended_url', $_SERVER['REQUEST_URI']);
            
            // Set flash message
            $this->session->setFlash('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
            
            // Redirect to login page
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        return true;
    }
    
    /**
     * Check if user is guest (not logged in)
     * Used for login/register pages
     * 
     * @return bool True if guest, redirect if logged in
     */
    public function guest() {
        if ($this->session->isLoggedIn()) {
            // Redirect based on role
            $role = $this->session->getRole();
            $redirectUrl = $this->getRedirectUrlByRole($role);
            
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        return true;
    }
    
    /**
     * Get redirect URL based on user role
     * 
     * @param string $role User role
     * @return string Dashboard URL
     */
    protected function getRedirectUrlByRole($role) {
        $urls = [
            'super_admin' => APP_URL . '/super-admin',
            'admin_sekolah' => APP_URL . '/admin',
            'guru' => APP_URL . '/guru',
            'siswa' => APP_URL . '/siswa',
            'wali_kelas' => APP_URL . '/wali-kelas',
            'orang_tua' => APP_URL . '/orang-tua',
            'kepala_sekolah' => APP_URL . '/kepala-sekolah',
            'mentor_industri' => APP_URL . '/mentor-industri'
        ];
        
        return $urls[$role] ?? APP_URL . '/';
    }
}
