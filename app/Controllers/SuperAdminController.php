<?php
/**
 * SuperAdminController - Super Admin Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class SuperAdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        // Check if user is super admin
        if (!$this->isAuthenticated() || $this->session->get('role') !== 'super_admin') {
            $this->redirect('/login');
        }
    }
    
    public function dashboard() {
        $data = [
            'title' => 'Dashboard Super Admin - LMS SMKK SDM',
            'page' => 'super-admin-dashboard'
        ];
        
        ob_start();
        $this->view('super-admin/dashboard', $data);
        $content = ob_get_clean();
        
        $this->renderLayout('main', $content, $data);
    }
    
    public function users() {
        $data = [
            'title' => 'Manajemen User - LMS SMKK SDM',
            'page' => 'super-admin-users'
        ];
        
        ob_start();
        $this->view('super-admin/users', $data);
        $content = ob_get_clean();
        
        $this->renderLayout('main', $content, $data);
    }
    
    public function createUser() {
        // Implementasi create user logic
        $this->redirect('/super-admin/users');
    }
    
    public function updateUser() {
        // Implementasi update user logic
        $this->redirect('/super-admin/users');
    }
    
    public function deleteUser() {
        // Implementasi delete user logic
        $this->redirect('/super-admin/users');
    }
    
    public function roles() {
        $data = [
            'title' => 'Manajemen Role - LMS SMKK SDM',
            'page' => 'super-admin-roles'
        ];
        
        ob_start();
        $this->view('super-admin/roles', $data);
        $content = ob_get_clean();
        
        $this->renderLayout('main', $content, $data);
    }
    
    public function createRole() {
        // Implementasi create role logic
        $this->redirect('/super-admin/roles');
    }
    
    public function backup() {
        $data = [
            'title' => 'Backup & Restore - LMS SMKK SDM',
            'page' => 'super-admin-backup'
        ];
        
        ob_start();
        $this->view('super-admin/backup', $data);
        $content = ob_get_clean();
        
        $this->renderLayout('main', $content, $data);
    }
    
    public function createBackup() {
        // Implementasi backup logic
        $this->redirect('/super-admin/backup');
    }
    
    public function restoreBackup() {
        // Implementasi restore backup logic
        $this->redirect('/super-admin/backup');
    }
    
    public function settings() {
        $data = [
            'title' => 'Pengaturan Sistem - LMS SMKK SDM',
            'page' => 'super-admin-settings'
        ];
        
        ob_start();
        $this->view('super-admin/settings', $data);
        $content = ob_get_clean();
        
        $this->renderLayout('main', $content, $data);
    }
    
    public function updateSettings() {
        // Implementasi update settings logic
        $this->redirect('/super-admin/settings');
    }
}
