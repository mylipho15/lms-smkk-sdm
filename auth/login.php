<?php
/**
 * Login Page
 * LMS SMK Kesehatan SDM Sumedang
 */

require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/Auth.php';

// Redirect if already logged in
if (auth()->check()) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        $userModel = new User();
        $user = $userModel->authenticate($username, $password);
        
        if ($user) {
            auth()->login($user);
            
            // Set redirect based on role
            $redirects = [
                'super_admin' => 'dashboard.php',
                'admin_sekolah' => 'admin/dashboard.php',
                'guru' => 'guru/dashboard.php',
                'wali_kelas' => 'guru/dashboard.php',
                'siswa' => 'siswa/dashboard.php',
                'orang_tua' => 'ortu/dashboard.php',
                'kepala_sekolah' => 'kepalasekolah/dashboard.php',
                'mentor_industri' => 'mentor/dashboard.php'
            ];
            
            $redirect = $redirects[$user['role']] ?? 'dashboard.php';
            redirect($redirect);
        } else {
            $error = 'Username atau password salah';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LMS SMK Kesehatan SDM Sumedang</title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-primary);
            padding: 2rem;
        }
        
        .login-box {
            width: 100%;
            max-width: 450px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: var(--clay-shadow-medium);
        }
        
        .login-title {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .login-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        
        .theme-toggle-wrapper {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <!-- Theme Toggle -->
    <div class="theme-toggle-wrapper">
        <div class="theme-toggle" title="Ganti Tema"></div>
    </div>
    
    <div class="login-container">
        <div class="login-box">
            <!-- Login Card -->
            <div class="clay-card animate-fadeIn">
                <!-- Header -->
                <div class="login-header">
                    <div class="login-logo">🏫</div>
                    <h1 class="login-title">LMS SMK Kesehatan</h1>
                    <p class="login-subtitle">SDM Sumedang</p>
                </div>
                
                <!-- Alert Messages -->
                <?php if ($error): ?>
                    <div class="alert alert-danger" data-dismiss="5000">
                        ⚠️ <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success" data-dismiss="5000">
                        ✅ <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form method="POST" action="" data-validate>
                    <div class="clay-input-group">
                        <label class="clay-input-label" for="username">
                            👤 Username / Email
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="clay-input" 
                            placeholder="Masukkan username atau email"
                            required
                            autofocus
                        >
                    </div>
                    
                    <div class="clay-input-group">
                        <label class="clay-input-label" for="password">
                            🔒 Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="clay-input" 
                            placeholder="Masukkan password"
                            required
                        >
                    </div>
                    
                    <div class="d-flex justify-between align-center mb-3">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="remember" style="width: auto;">
                            <span style="font-size: 0.875rem; color: var(--text-muted);">Ingat saya</span>
                        </label>
                        <a href="<?= url('auth/forgot-password.php') ?>" style="font-size: 0.875rem;">Lupa password?</a>
                    </div>
                    
                    <button type="submit" class="clay-btn clay-btn-primary clay-btn-block clay-btn-lg">
                        🚀 Masuk ke Dashboard
                    </button>
                </form>
                
                <!-- Footer -->
                <div class="text-center mt-3" style="padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                    <p style="color: var(--text-muted); font-size: 0.875rem;">
                        © 2024 LMS SMK Kesehatan SDM Sumedang
                    </p>
                    <p style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.5rem;">
                        Butuh bantuan? Hubungi admin sekolah
                    </p>
                </div>
            </div>
            
            <!-- Demo Credentials -->
            <div class="clay-card-flat mt-3" style="text-align: center;">
                <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                    🔑 <strong>Demo Login:</strong>
                </p>
                <p style="font-size: 0.75rem; color: var(--text-muted);">
                    Username: <code>superadmin</code><br>
                    Password: <code>admin123</code>
                </p>
            </div>
        </div>
    </div>
    
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
