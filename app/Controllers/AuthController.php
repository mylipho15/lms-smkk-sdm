<?php
/**
 * AuthController - Authentication Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller {
    
    public function showLogin() {
        $data = [
            'title' => 'Login - LMS SMKK SDM',
            'page' => 'login'
        ];
        
        ob_start();
        $this->view('auth/login', $data);
        $content = ob_get_clean();
        
        $this->renderLayout('main', $content, $data);
    }
    
    public function login() {
        // Implementasi login logic
        $email = $this->request->post('email');
        $password = $this->request->post('password');
        
        // Placeholder - implementasi actual akan menggunakan Model
        if ($email && $password) {
            // Set session (placeholder)
            $this->session->set('user_id', 1);
            $this->session->set('role', 'siswa');
            
            $this->redirect('/siswa');
        } else {
            $this->setFlash('error', 'Email dan password harus diisi');
            $this->redirect('/login');
        }
    }
    
    public function logout() {
        $this->session->destroy();
        $this->redirect('/');
    }
    
    public function showRegister() {
        $data = [
            'title' => 'Daftar Akun - LMS SMKK SDM',
            'page' => 'register'
        ];
        
        ob_start();
        $this->view('auth/register', $data);
        $content = ob_get_clean();
        
        $this->renderLayout('main', $content, $data);
    }
    
    public function register() {
        // Implementasi registrasi logic
        $this->redirect('/login');
    }
    
    public function showForgotPassword() {
        $data = [
            'title' => 'Lupa Password - LMS SMKK SDM',
            'page' => 'forgot-password'
        ];
        
        ob_start();
        $this->view('auth/forgot-password', $data);
        $content = ob_get_clean();
        
        $this->renderLayout('main', $content, $data);
    }
    
    public function forgotPassword() {
        // Implementasi forgot password logic
        $this->redirect('/login');
    }
    
    public function showResetPassword() {
        $data = [
            'title' => 'Reset Password - LMS SMKK SDM',
            'page' => 'reset-password'
        ];
        
        ob_start();
        $this->view('auth/reset-password', $data);
        $content = ob_get_clean();
        
        $this->renderLayout('main', $content, $data);
    }
    
    public function resetPassword() {
        // Implementasi reset password logic
        $this->redirect('/login');
    }
}
