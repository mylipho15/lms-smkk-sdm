<?php
/**
 * RoleMiddleware.php - Cek role (Admin, Guru, Siswa, Mentor PKL)
 * 
 * Middleware untuk pengecekan role pengguna LMS SMK Kesehatan SDM Sumedang
 */

namespace App\Middleware;

use App\Core\Session;

class RoleMiddleware {
    protected $session;
    
    public function __construct() {
        $this->session = new Session();
    }
    
    /**
     * Handle role check with single or multiple roles
     * 
     * @param string|array $allowedRoles Role(s) yang diperbolehkan
     * @return bool True if authorized, redirect if not
     */
    public function handle($allowedRoles) {
        // First check if user is logged in
        if (!$this->session->isLoggedIn()) {
            $this->session->setFlash('error', 'Silakan login terlebih dahulu.');
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        $userRole = $this->session->getRole();
        
        // Convert single role to array for consistency
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }
        
        // Check if user role is in allowed roles
        if (!in_array($userRole, $allowedRoles)) {
            // Set flash message
            $this->session->setFlash(
                'error', 
                'Anda tidak memiliki akses ke halaman ini. Role diperlukan: ' . implode(', ', $allowedRoles)
            );
            
            // Redirect based on user role
            $redirectUrl = $this->getRedirectUrlByRole($userRole);
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        return true;
    }
    
    /**
     * Check for specific role - Super Admin
     * 
     * @return bool
     */
    public function superAdmin() {
        return $this->handle('super_admin');
    }
    
    /**
     * Check for specific role - Admin Sekolah
     * 
     * @return bool
     */
    public function adminSekolah() {
        return $this->handle('admin_sekolah');
    }
    
    /**
     * Check for specific role - Guru
     * 
     * @return bool
     */
    public function guru() {
        return $this->handle('guru');
    }
    
    /**
     * Check for specific role - Siswa
     * 
     * @return bool
     */
    public function siswa() {
        return $this->handle('siswa');
    }
    
    /**
     * Check for specific role - Wali Kelas
     * 
     * @return bool
     */
    public function waliKelas() {
        return $this->handle('wali_kelas');
    }
    
    /**
     * Check for specific role - Orang Tua
     * 
     * @return bool
     */
    public function orangTua() {
        return $this->handle('orang_tua');
    }
    
    /**
     * Check for specific role - Kepala Sekolah
     * 
     * @return bool
     */
    public function kepalaSekolah() {
        return $this->handle('kepala_sekolah');
    }
    
    /**
     * Check for specific role - Mentor Industri
     * 
     * @return bool
     */
    public function mentorIndustri() {
        return $this->handle('mentor_industri');
    }
    
    /**
     * Check for teaching staff roles (Guru, Wali Kelas, Kepala Sekolah)
     * 
     * @return bool
     */
    public function teachingStaff() {
        return $this->handle(['guru', 'wali_kelas', 'kepala_sekolah']);
    }
    
    /**
     * Check for management roles (Super Admin, Admin Sekolah, Kepala Sekolah)
     * 
     * @return bool
     */
    public function management() {
        return $this->handle(['super_admin', 'admin_sekolah', 'kepala_sekolah']);
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
