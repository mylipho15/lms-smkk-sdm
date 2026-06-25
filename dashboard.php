<?php
/**
 * Main Dashboard
 * LMS SMK Kesehatan SDM Sumedang
 */

require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/middleware/Auth.php';

// Require authentication
auth()->requireLogin();

$user = auth()->user();
$userId = auth()->id();
$userRole = auth()->role();

// Initialize database
$db = Database::getInstance();

// Get dashboard statistics based on role
$stats = [];
$recentActivities = [];

switch ($userRole) {
    case 'super_admin':
        $stats = [
            'total_users' => $db->fetchOne("SELECT COUNT(*) as count FROM users")['count'],
            'total_guru' => $db->fetchOne("SELECT COUNT(*) as count FROM users WHERE role IN ('guru', 'wali_kelas')")['count'],
            'total_siswa' => $db->fetchOne("SELECT COUNT(*) as count FROM siswa")['count'],
            'total_kelas' => $db->fetchOne("SELECT COUNT(*) as count FROM kelas")['count']
        ];
        break;
        
    case 'admin_sekolah':
        $stats = [
            'total_guru' => $db->fetchOne("SELECT COUNT(*) as count FROM users WHERE role IN ('guru', 'wali_kelas')")['count'],
            'total_siswa' => $db->fetchOne("SELECT COUNT(*) as count FROM siswa")['count'],
            'total_kelas' => $db->fetchOne("SELECT COUNT(*) as count FROM kelas")['count'],
            'total_jurusan' => $db->fetchOne("SELECT COUNT(*) as count FROM jurusan")['count']
        ];
        break;
        
    case 'guru':
    case 'wali_kelas':
        $stats = [
            'total_kelas' => $db->fetchOne("SELECT COUNT(DISTINCT kelas_id) as count FROM guru_mapel WHERE guru_id = :id", ['id' => $userId])['count'],
            'total_tugas' => $db->fetchOne("SELECT COUNT(*) as count FROM tugas t JOIN guru_mapel gm ON t.kursus_id = gm.id WHERE gm.guru_id = :id", ['id' => $userId])['count'],
            'total_siswa' => $db->fetchOne("SELECT COUNT(*) as count FROM siswa s JOIN guru_mapel gm ON s.kelas_id = gm.kelas_id WHERE gm.guru_id = :id", ['id' => $userId])['count'],
            'pending_grading' => $db->fetchOne("SELECT COUNT(*) as count FROM submissions sub JOIN tugas t ON sub.tugas_id = t.id JOIN kursus k ON t.kursus_id = k.id JOIN guru_mapel gm ON k.guru_mapel_id = gm.id WHERE gm.guru_id = :id AND sub.status = 'submitted'", ['id' => $userId])['count']
        ];
        break;
        
    case 'siswa':
        // Get student profile
        $siswa = $db->fetchOne("SELECT * FROM siswa WHERE user_id = :id", ['id' => $userId]);
        $kelasId = $siswa['kelas_id'] ?? 0;
        
        $stats = [
            'total_tugas' => $db->fetchOne("SELECT COUNT(*) as count FROM tugas t JOIN guru_mapel gm ON t.kursus_id = gm.id WHERE gm.kelas_id = :kelasId", ['kelasId' => $kelasId])['count'],
            'tugas_selesai' => $db->fetchOne("SELECT COUNT(*) as count FROM submissions sub JOIN tugas t ON sub.tugas_id = t.id JOIN guru_mapel gm ON t.kursus_id = gm.id WHERE sub.siswa_id = :siswaId AND gm.kelas_id = :kelasId", ['siswaId' => $siswa['id'], 'kelasId' => $kelasId])['count'],
            'rata_rata_nilai' => $db->fetchOne("SELECT AVG(nilai) as avg FROM submissions sub JOIN tugas t ON sub.tugas_id = t.id WHERE sub.siswa_id = :siswaId AND nilai IS NOT NULL", ['siswaId' => $siswa['id']])['avg'] ?? 0,
            'kehadiran' => $db->fetchOne("SELECT COUNT(*) as count FROM absensi WHERE siswa_id = :siswaId AND status = 'hadir'", ['siswaId' => $siswa['id']])['count']
        ];
        break;
        
    case 'orang_tua':
        // Get children data
        $children = $db->fetchAll("SELECT s.* FROM orang_tua ot JOIN siswa s ON ot.siswa_id = s.id WHERE ot.user_id = :id", ['id' => $userId]);
        $childIds = array_column($children, 'id');
        
        if (!empty($childIds)) {
            $ids = implode(',', $childIds);
            $stats = [
                'jumlah_anak' => count($children),
                'rata_rata_nilai' => $db->fetchOne("SELECT AVG(nilai) as avg FROM submissions WHERE siswa_id IN ({$ids}) AND nilai IS NOT NULL")['avg'] ?? 0,
                'total_kehadiran' => $db->fetchOne("SELECT COUNT(*) as count FROM absensi WHERE siswa_id IN ({$ids}) AND status = 'hadir'")['count']
            ];
        } else {
            $stats = ['jumlah_anak' => 0, 'rata_rata_nilai' => 0, 'total_kehadiran' => 0];
        }
        break;
        
    default:
        $stats = [];
}

// Sidebar menu based on role
$sidebarMenu = generateSidebarMenu($userRole);

// Page content
ob_start();
?>

<!-- Page Header -->
<div class="mb-4">
    <h1 style="margin-bottom: 0.5rem;">👋 Selamat Datang, <?= htmlspecialchars($user['full_name']) ?>!</h1>
    <p style="color: var(--text-muted);">Dashboard LMS SMK Kesehatan SDM Sumedang</p>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <?php foreach ($stats as $key => $value): ?>
    <div class="col-3" style="flex: 1; min-width: 200px;">
        <div class="stats-card animate-fadeIn">
            <div class="stats-icon">
                <?= getStatIcon($key) ?>
            </div>
            <div class="stats-info">
                <h3><?= number_format($value) ?></h3>
                <p><?= getStatLabel($key) ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="row">
    <!-- Quick Actions -->
    <div class="col-6" style="flex: 1; min-width: 300px;">
        <div class="clay-card mb-4">
            <h3 class="mb-3">⚡ Aksi Cepat</h3>
            <div class="d-flex flex-wrap gap-2">
                <?= getQuickActions($userRole) ?>
            </div>
        </div>
    </div>
    
    <!-- Recent Notifications -->
    <div class="col-6" style="flex: 1; min-width: 300px;">
        <div class="clay-card">
            <h3 class="mb-3">📢 Pengumuman Terbaru</h3>
            <?php
            $pengumuman = $db->fetchAll("SELECT * FROM pengumuman WHERE target_audience IN ('all', :role) ORDER BY created_at DESC LIMIT 5", ['role' => $userRole]);
            if (empty($pengumuman)):
            ?>
                <p style="color: var(--text-muted); text-align: center; padding: 2rem;">Belum ada pengumuman</p>
            <?php else: ?>
                <?php foreach ($pengumuman as $item): ?>
                <div class="d-flex align-center gap-2 mb-2" style="padding: 1rem; background: var(--bg-tertiary); border-radius: var(--clay-radius-sm);">
                    <div style="font-size: 1.5rem;"><?= $item['is_pinned'] ? '📌' : '📢' ?></div>
                    <div style="flex: 1;">
                        <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;"><?= htmlspecialchars($item['judul']) ?></h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">
                            <?= DateUtils::formatDateIndonesian($item['created_at']) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Dashboard';

// Include main layout
include __DIR__ . '/layouts/main.php';

/**
 * Generate sidebar menu based on role
 */
function generateSidebarMenu($role) {
    $menus = [
        'super_admin' => '
            <li class="sidebar-item"><a href="<?= url('dashboard.php') ?>" class="sidebar-link active">📊 Dashboard</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/users.php') ?>" class="sidebar-link">👥 Manajemen User</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/roles.php') ?>" class="sidebar-link">🔐 Role & Akses</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/backup.php') ?>" class="sidebar-link">💾 Backup/Restore</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/settings.php') ?>" class="sidebar-link">⚙️ Konfigurasi</a></li>
        ',
        'admin_sekolah' => '
            <li class="sidebar-item"><a href="<?= url('dashboard.php') ?>" class="sidebar-link active">📊 Dashboard</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/tahun-ajaran.php') ?>" class="sidebar-link">📅 Tahun Ajaran</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/jurusan.php') ?>" class="sidebar-link">🎓 Jurusan</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/kelas.php') ?>" class="sidebar-link">🏫 Kelas</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/guru.php') ?>" class="sidebar-link">👨‍🏫 Guru</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/siswa.php') ?>" class="sidebar-link">👨‍🎓 Siswa</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/jadwal.php') ?>" class="sidebar-link">📋 Jadwal</a></li>
            <li class="sidebar-item"><a href="<?= url('admin/pengumuman.php') ?>" class="sidebar-link">📢 Pengumuman</a></li>
        ',
        'guru' => '
            <li class="sidebar-item"><a href="<?= url('dashboard.php') ?>" class="sidebar-link active">📊 Dashboard</a></li>
            <li class="sidebar-item"><a href="<?= url('guru/kursus.php') ?>" class="sidebar-link">📚 Kursus</a></li>
            <li class="sidebar-item"><a href="<?= url('guru/materi.php') ?>" class="sidebar-link">📖 Materi</a></li>
            <li class="sidebar-item"><a href="<?= url('guru/tugas.php') ?>" class="sidebar-link">📝 Tugas</a></li>
            <li class="sidebar-item"><a href="<?= url('guru/kuis.php') ?>" class="sidebar-link">❓ Kuis</a></li>
            <li class="sidebar-item"><a href="<?= url('guru/absensi.php') ?>" class="sidebar-link">✅ Absensi</a></li>
            <li class="sidebar-item"><a href="<?= url('guru/nilai.php') ?>" class="sidebar-link">📈 Nilai</a></li>
            <li class="sidebar-item"><a href="<?= url('guru/forum.php') ?>" class="sidebar-link">💬 Forum</a></li>
        ',
        'siswa' => '
            <li class="sidebar-item"><a href="<?= url('dashboard.php') ?>" class="sidebar-link active">📊 Dashboard</a></li>
            <li class="sidebar-item"><a href="<?= url('siswa/kursus.php') ?>" class="sidebar-link">📚 Kursus Saya</a></li>
            <li class="sidebar-item"><a href="<?= url('siswa/tugas.php') ?>" class="sidebar-link">📝 Tugas</a></li>
            <li class="sidebar-item"><a href="<?= url('siswa/kuis.php') ?>" class="sidebar-link">❓ Kuis</a></li>
            <li class="sidebar-item"><a href="<?= url('siswa/nilai.php') ?>" class="sidebar-link">📈 Nilai</a></li>
            <li class="sidebar-item"><a href="<?= url('siswa/absensi.php') ?>" class="sidebar-link">✅ Kehadiran</a></li>
            <li class="sidebar-item"><a href="<?= url('siswa/logbook.php') ?>" class="sidebar-link">📔 Logbook PKL</a></li>
            <li class="sidebar-item"><a href="<?= url('siswa/forum.php') ?>" class="sidebar-link">💬 Forum</a></li>
        ',
        'orang_tua' => '
            <li class="sidebar-item"><a href="<?= url('dashboard.php') ?>" class="sidebar-link active">📊 Dashboard</a></li>
            <li class="sidebar-item"><a href="<?= url('ortu/anak.php') ?>" class="sidebar-link">👨‍🎓 Data Anak</a></li>
            <li class="sidebar-item"><a href="<?= url('ortu/nilai.php') ?>" class="sidebar-link">📈 Nilai</a></li>
            <li class="sidebar-item"><a href="<?= url('ortu/absensi.php') ?>" class="sidebar-link">✅ Kehadiran</a></li>
            <li class="sidebar-item"><a href="<?= url('ortu/pesan.php') ?>" class="sidebar-link">✉️ Pesan</a></li>
        ',
        'kepala_sekolah' => '
            <li class="sidebar-item"><a href="<?= url('dashboard.php') ?>" class="sidebar-link active">📊 Dashboard Analitik</a></li>
            <li class="sidebar-item"><a href="<?= url('kepalasekolah/laporan.php') ?>" class="sidebar-link">📄 Laporan</a></li>
            <li class="sidebar-item"><a href="<?= url('kepalasekolah/akreditasi.php') ?>" class="sidebar-link">🏆 Akreditasi</a></li>
            <li class="sidebar-item"><a href="<?= url('kepalasekolah/kompetensi.php') ?>" class="sidebar-link">🎯 Kompetensi</a></li>
            <li class="sidebar-item"><a href="<?= url('kepalasekolah/kelulusan.php') ?>" class="sidebar-link">🎓 Kelulusan</a></li>
        ',
        'mentor_industri' => '
            <li class="sidebar-item"><a href="<?= url('dashboard.php') ?>" class="sidebar-link active">📊 Dashboard</a></li>
            <li class="sidebar-item"><a href="<?= url('mentor/peserta.php') ?>" class="sidebar-link">👨‍🎓 Peserta PKL</a></li>
            <li class="sidebar-item"><a href="<?= url('mentor/logbook.php') ?>" class="sidebar-link">📔 Verifikasi Logbook</a></li>
            <li class="sidebar-item"><a href="<?= url('mentor/penilaian.php') ?>" class="sidebar-link">⭐ Penilaian</a></li>
            <li class="sidebar-item"><a href="<?= url('mentor/sertifikat.php') ?>" class="sidebar-link">📜 Sertifikat</a></li>
        '
    ];
    
    return $menus[$role] ?? $menus['siswa'];
}

/**
 * Get icon for stat card
 */
function getStatIcon($key) {
    $icons = [
        'total_users' => '👥',
        'total_guru' => '👨‍🏫',
        'total_siswa' => '👨‍🎓',
        'total_kelas' => '🏫',
        'total_jurusan' => '🎓',
        'total_tugas' => '📝',
        'pending_grading' => '⏳',
        'tugas_selesai' => '✅',
        'rata_rata_nilai' => '📈',
        'kehadiran' => '✅',
        'jumlah_anak' => '👨‍👩‍👧‍👦'
    ];
    return $icons[$key] ?? '📊';
}

/**
 * Get label for stat
 */
function getStatLabel($key) {
    $labels = [
        'total_users' => 'Total Pengguna',
        'total_guru' => 'Total Guru',
        'total_siswa' => 'Total Siswa',
        'total_kelas' => 'Total Kelas',
        'total_jurusan' => 'Total Jurusan',
        'total_tugas' => 'Total Tugas',
        'pending_grading' => 'Menilai Tugas',
        'tugas_selesai' => 'Tugas Selesai',
        'rata_rata_nilai' => 'Rata-rata Nilai',
        'kehadiran' => 'Kehadiran',
        'jumlah_anak' => 'Jumlah Anak'
    ];
    return $labels[$key] ?? ucfirst(str_replace('_', ' ', $key));
}

/**
 * Get quick actions buttons based on role
 */
function getQuickActions($role) {
    $actions = [
        'super_admin' => '<a href="<?= url('admin/users.php" class="clay-btn clay-btn-primary clay-btn-sm">👥 User</a><a href="<?= url('admin/settings.php" class="clay-btn clay-btn-sm">⚙️ Settings</a>',
        'admin_sekolah' => '<a href="<?= url('admin/siswa.php" class="clay-btn clay-btn-primary clay-btn-sm">➕ Siswa Baru</a><a href="<?= url('admin/pengumuman.php" class="clay-btn clay-btn-sm">📢 Pengumuman</a>',
        'guru' => '<a href="<?= url('guru/tugas/create.php" class="clay-btn clay-btn-primary clay-btn-sm">➕ Buat Tugas</a><a href="<?= url('guru/absensi.php" class="clay-btn clay-btn-sm">✅ Input Absen</a>',
        'siswa' => '<a href="<?= url('siswa/tugas.php" class="clay-btn clay-btn-primary clay-btn-sm">📝 Kerjakan Tugas</a><a href="<?= url('siswa/kuis.php" class="clay-btn clay-btn-sm">❓ Kuis</a>',
        'orang_tua' => '<a href="<?= url('ortu/pesan.php" class="clay-btn clay-btn-primary clay-btn-sm">✉️ Kirim Pesan</a>',
        'kepala_sekolah' => '<a href="<?= url('kepalasekolah/laporan.php" class="clay-btn clay-btn-primary clay-btn-sm">📄 Lihat Laporan</a>',
        'mentor_industri' => '<a href="<?= url('mentor/penilaian.php" class="clay-btn clay-btn-primary clay-btn-sm">⭐ Beri Nilai</a>'
    ];
    return $actions[$role] ?? '';
}
?>
