<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - LMS SMK Kesehatan SDM Sumedang</title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    <!-- Additional page-specific styles can be added here -->
    <?php if (isset($additionalStyles)): ?>
        <?php foreach ($additionalStyles as $style): ?>
            <link rel="stylesheet" href="<?= $style ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="d-flex align-center gap-3">
            <!-- Mobile Menu Toggle -->
            <button class="clay-btn mobile-menu-toggle d-none" style="display: none;" onclick="window.sidebarManager.toggle()">
                ☰
            </button>
            
            <!-- Brand -->
            <a href="<?= url('dashboard.php') ?>" class="navbar-brand">
                🏫 LMS SMK Kesehatan SDM
            </a>
        </div>
        
        <!-- Right Side Nav -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <div class="theme-toggle" title="Ganti Tema"></div>
            </li>
            <li class="nav-item">
                <a href="<?= url('notifications.php') ?>" class="nav-link">
                    🔔
                    <?php if (isset($unreadNotifications) && $unreadNotifications > 0): ?>
                        <span class="badge badge-danger" style="font-size: 0.65rem; padding: 0.2rem 0.4rem;">
                            <?= $unreadNotifications ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('messages.php') ?>" class="nav-link">
                    ✉️
                    <?php if (isset($unreadMessages) && $unreadMessages > 0): ?>
                        <span class="badge badge-primary" style="font-size: 0.65rem; padding: 0.2rem 0.4rem;">
                            <?= $unreadMessages ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" onclick="return false;">
                    👤 <?= htmlspecialchars(auth()->user()['full_name']) ?>
                    <span class="badge badge-<?= getRoleBadge(auth()->role()) ?>" style="font-size: 0.65rem; padding: 0.2rem 0.4rem;">
                        <?= getRoleLabel(auth()->role()) ?>
                    </span>
                </a>
                <div class="dropdown-menu clay-card-flat" style="position: absolute; right: 0; top: 100%; min-width: 200px; display: none; box-shadow: var(--clay-shadow-heavy);">
                    <a href="<?= url('profile.php') ?>" class="nav-link" style="border-radius: var(--clay-radius-sm);">
                        👤 Profil Saya
                    </a>
                    <a href="<?= url('settings.php') ?>" class="nav-link" style="border-radius: var(--clay-radius-sm);">
                        ⚙️ Pengaturan
                    </a>
                    <hr style="margin: 0.5rem 0; border: none; border-top: 1px solid var(--border-color);">
                    <a href="<?= url('auth/logout.php') ?>" class="nav-link" style="border-radius: var(--clay-radius-sm); color: var(--danger);">
                        🚪 Keluar
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    
    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- User Mini Profile -->
        <div class="text-center mb-3" style="padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
            <div style="width: 70px; height: 70px; margin: 0 auto 1rem; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; box-shadow: var(--clay-shadow-medium);">
                <?= strtoupper(substr(auth()->user()['full_name'], 0, 1)) ?>
            </div>
            <h4 style="font-size: 1rem; margin-bottom: 0.25rem;"><?= htmlspecialchars(auth()->user()['full_name']) ?></h4>
            <span class="badge badge-<?= getRoleBadge(auth()->role()) ?>" style="font-size: 0.7rem;">
                <?= getRoleLabel(auth()->role()) ?>
            </span>
        </div>
        
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <?= $sidebarMenu ?? '' ?>
        </ul>
    </aside>
    
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 998; display: none;"></div>
    
    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>
    
    <!-- Scripts -->
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <?php if (isset($additionalScripts)): ?>
        <?php foreach ($additionalScripts as $script): ?>
            <script src="<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>
        // Dropdown toggle
        document.querySelector('.nav-item.dropdown')?.addEventListener('click', function(e) {
            e.preventDefault();
            const menu = this.querySelector('.dropdown-menu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.nav-item.dropdown');
            const menu = dropdown?.querySelector('.dropdown-menu');
            if (menu && !dropdown.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    </script>
</body>
</html>

<?php
/**
 * Helper function to get role badge color
 */
function getRoleBadge($role) {
    $badges = [
        'super_admin' => 'danger',
        'admin_sekolah' => 'primary',
        'guru' => 'info',
        'wali_kelas' => 'info',
        'siswa' => 'success',
        'orang_tua' => 'warning',
        'kepala_sekolah' => 'primary',
        'mentor_industri' => 'secondary'
    ];
    return $badges[$role] ?? 'secondary';
}

/**
 * Helper function to get role label
 */
function getRoleLabel($role) {
    $labels = [
        'super_admin' => 'Super Admin',
        'admin_sekolah' => 'Admin Sekolah',
        'guru' => 'Guru',
        'wali_kelas' => 'Wali Kelas',
        'siswa' => 'Siswa',
        'orang_tua' => 'Orang Tua',
        'kepala_sekolah' => 'Kepala Sekolah',
        'mentor_industri' => 'Mentor Industri'
    ];
    return $labels[$role] ?? ucfirst($role);
}
