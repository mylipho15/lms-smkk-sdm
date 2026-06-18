<?php
/**
 * Routes Configuration - routes.php
 * 
 * Daftar routing (URL -> Controller@Method) untuk LMS SMK Kesehatan SDM Sumedang
 */

return [
    // ==========================================
    // PUBLIC ROUTES
    // ==========================================
    'GET /' => 'HomeController@index',
    'GET /login' => 'AuthController@showLogin',
    'POST /login' => 'AuthController@login',
    'GET /logout' => 'AuthController@logout',
    'GET /register' => 'AuthController@showRegister',
    'POST /register' => 'AuthController@register',
    'GET /forgot-password' => 'AuthController@showForgotPassword',
    'POST /forgot-password' => 'AuthController@forgotPassword',
    'GET /reset-password' => 'AuthController@showResetPassword',
    'POST /reset-password' => 'AuthController@resetPassword',
    
    // ==========================================
    // SUPER ADMIN ROUTES
    // ==========================================
    'GET /super-admin' => 'SuperAdminController@dashboard',
    'GET /super-admin/users' => 'SuperAdminController@users',
    'POST /super-admin/users/create' => 'SuperAdminController@createUser',
    'POST /super-admin/users/update' => 'SuperAdminController@updateUser',
    'POST /super-admin/users/delete' => 'SuperAdminController@deleteUser',
    'GET /super-admin/roles' => 'SuperAdminController@roles',
    'POST /super-admin/roles/create' => 'SuperAdminController@createRole',
    'GET /super-admin/backup' => 'SuperAdminController@backup',
    'POST /super-admin/backup/create' => 'SuperAdminController@createBackup',
    'POST /super-admin/backup/restore' => 'SuperAdminController@restoreBackup',
    'GET /super-admin/settings' => 'SuperAdminController@settings',
    'POST /super-admin/settings/update' => 'SuperAdminController@updateSettings',
    
    // ==========================================
    // ADMIN SEKOLAH ROUTES
    // ==========================================
    'GET /admin' => 'AdminController@dashboard',
    
    // Tahun Ajaran
    'GET /admin/tahun-ajaran' => 'AdminController@tahunAjaran',
    'POST /admin/tahun-ajaran/create' => 'AdminController@createTahunAjaran',
    'POST /admin/tahun-ajaran/update' => 'AdminController@updateTahunAjaran',
    'POST /admin/tahun-ajaran/delete' => 'AdminController@deleteTahunAjaran',
    
    // Jurusan/Kompetensi Keahlian
    'GET /admin/jurusan' => 'AdminController@jurusan',
    'POST /admin/jurusan/create' => 'AdminController@createJurusan',
    'POST /admin/jurusan/update' => 'AdminController@updateJurusan',
    'POST /admin/jurusan/delete' => 'AdminController@deleteJurusan',
    
    // Kelas
    'GET /admin/kelas' => 'AdminController@kelas',
    'POST /admin/kelas/create' => 'AdminController@createKelas',
    'POST /admin/kelas/update' => 'AdminController@updateKelas',
    'POST /admin/kelas/delete' => 'AdminController@deleteKelas',
    
    // Guru
    'GET /admin/guru' => 'AdminController@guru',
    'POST /admin/guru/create' => 'AdminController@createGuru',
    'POST /admin/guru/update' => 'AdminController@updateGuru',
    'POST /admin/guru/delete' => 'AdminController@deleteGuru',
    
    // Siswa
    'GET /admin/siswa' => 'AdminController@siswa',
    'POST /admin/siswa/create' => 'AdminController@createSiswa',
    'POST /admin/siswa/update' => 'AdminController@updateSiswa',
    'POST /admin/siswa/delete' => 'AdminController@deleteSiswa',
    
    // Jadwal
    'GET /admin/jadwal' => 'AdminController@jadwal',
    'POST /admin/jadwal/create' => 'AdminController@createJadwal',
    'POST /admin/jadwal/update' => 'AdminController@updateJadwal',
    'POST /admin/jadwal/delete' => 'AdminController@deleteJadwal',
    
    // Pengumuman
    'GET /admin/pengumuman' => 'AdminController@pengumuman',
    'POST /admin/pengumuman/create' => 'AdminController@createPengumuman',
    'POST /admin/pengumuman/update' => 'AdminController@updatePengumuman',
    'POST /admin/pengumuman/delete' => 'AdminController@deletePengumuman',
    
    // Wali Kelas
    'GET /admin/wali-kelas' => 'AdminController@waliKelas',
    'POST /admin/wali-kelas/assign' => 'AdminController@assignWaliKelas',
    
    // ==========================================
    // GURU ROUTES
    // ==========================================
    'GET /guru' => 'GuruController@dashboard',
    
    // Kursus/Mata Pelajaran
    'GET /guru/kursus' => 'GuruController@kursus',
    'POST /guru/kursus/create' => 'GuruController@createKursus',
    'POST /guru/kursus/update' => 'GuruController@updateKursus',
    'GET /guru/kursus/detail' => 'GuruController@detailKursus',
    
    // Materi Pembelajaran
    'GET /guru/materi' => 'GuruController@materi',
    'POST /guru/materi/upload' => 'GuruController@uploadMateri',
    'POST /guru/materi/delete' => 'GuruController@deleteMateri',
    
    // Tugas/Assignment
    'GET /guru/tugas' => 'GuruController@tugas',
    'POST /guru/tugas/create' => 'GuruController@createTugas',
    'POST /guru/tugas/update' => 'GuruController@updateTugas',
    'POST /guru/tugas/delete' => 'GuruController@deleteTugas',
    'GET /guru/tugas/submissions' => 'GuruController@submissionsTugas',
    'POST /guru/tugas/grade' => 'GuruController@gradeTugas',
    
    // Kuis/Ujian
    'GET /guru/kuis' => 'GuruController@kuis',
    'POST /guru/kuis/create' => 'GuruController@createKuis',
    'POST /guru/kuis/update' => 'GuruController@updateKuis',
    'POST /guru/kuis/delete' => 'GuruController@deleteKuis',
    'GET /guru/kuis/questions' => 'GuruController@questionsKuis',
    'POST /guru/kuis/questions/add' => 'GuruController@addQuestionKuis',
    'GET /guru/kuis/results' => 'GuruController@resultsKuis',
    
    // Absensi
    'GET /guru/absensi' => 'GuruController@absensi',
    'POST /guru/absensi/input' => 'GuruController@inputAbsensi',
    'GET /guru/absensi/rekap' => 'GuruController@rekapAbsensi',
    
    // Nilai
    'GET /guru/nilai' => 'GuruController@nilai',
    'POST /guru/nilai/input' => 'GuruController@inputNilai',
    'GET /guru/nilai/rekap' => 'GuruController@rekapNilai',
    'POST /guru/nilai/export' => 'GuruController@exportNilai',
    
    // Forum Diskusi
    'GET /guru/forum' => 'GuruController@forum',
    'POST /guru/forum/post' => 'GuruController@postForum',
    'POST /guru/forum/reply' => 'GuruController@replyForum',
    
    // ==========================================
    // SISWA ROUTES
    // ==========================================
    'GET /siswa' => 'SiswaController@dashboard',
    'GET /siswa/kursus' => 'SiswaController@kursus',
    'GET /siswa/kursus/detail' => 'SiswaController@detailKursus',
    'GET /siswa/materi' => 'SiswaController@materi',
    'GET /siswa/tugas' => 'SiswaController@tugas',
    'POST /siswa/tugas/submit' => 'SiswaController@submitTugas',
    'GET /siswa/tugas/detail' => 'SiswaController@detailTugas',
    'GET /siswa/kuis' => 'SiswaController@kuis',
    'GET /siswa/kuis/kerjakan' => 'SiswaController@kerjakanKuis',
    'POST /siswa/kuis/submit' => 'SiswaController@submitKuis',
    'GET /siswa/nilai' => 'SiswaController@nilai',
    'GET /siswa/absensi' => 'SiswaController@absensi',
    'GET /siswa/jadwal' => 'SiswaController@jadwal',
    'GET /siswa/forum' => 'SiswaController@forum',
    'POST /siswa/forum/post' => 'SiswaController@postForum',
    'POST /siswa/forum/reply' => 'SiswaController@replyForum',
    'GET /siswa/pesan' => 'SiswaController@pesan',
    'POST /siswa/pesan/kirim' => 'SiswaController@kirimPesan',
    
    // PKL Logbook
    'GET /siswa/pkl' => 'PklController@dashboardSiswa',
    'POST /siswa/pkl/logbook' => 'PklController@inputLogbook',
    'GET /siswa/pkl/logbook' => 'PklController@lihatLogbook',
    'POST /siswa/pkl/laporan' => 'PklController@uploadLaporan',
    'GET /siswa/pkl/nilai' => 'PklController@lihatNilai',
    
    // ==========================================
    // WALI KELAS ROUTES
    // ==========================================
    'GET /wali-kelas' => 'WaliKelasController@dashboard',
    'GET /wali-kelas/absensi' => 'WaliKelasController@absensiKelas',
    'GET /wali-kelas/nilai' => 'WaliKelasController@nilaiKelas',
    'POST /wali-kelas/catatan' => 'WaliKelasController@inputCatatanPerilaku',
    'GET /wali-kelas/orang-tua' => 'WaliKelasController@daftarOrangTua',
    'POST /wali-kelas/pesan' => 'WaliKelasController@kirimPesanOrangTua',
    'GET /wali-kelas/laporan' => 'WaliKelasController@generateLaporan',
    
    // ==========================================
    // ORANG TUA ROUTES
    // ==========================================
    'GET /orang-tua' => 'OrangTuaController@dashboard',
    'GET /orang-tua/anak' => 'OrangTuaController@dataAnak',
    'GET /orang-tua/nilai' => 'OrangTuaController@lihatNilai',
    'GET /orang-tua/absensi' => 'OrangTuaController@lihatAbsensi',
    'GET /orang-tua/tugas' => 'OrangTuaController@lihatTugas',
    'POST /orang-tua/pesan' => 'OrangTuaController@kirimPesan',
    'GET /orang-tua/notifikasi' => 'OrangTuaController@notifikasi',
    
    // ==========================================
    // KEPALA SEKOLAH ROUTES
    // ==========================================
    'GET /kepala-sekolah' => 'KepalaSekolahController@dashboard',
    'GET /kepala-sekolah/analytics' => 'KepalaSekolahController@analytics',
    'GET /kepala-sekolah/laporan' => 'KepalaSekolahController@laporan',
    'POST /kepala-sekolah/laporan/export' => 'KepalaSekolahController@exportLaporan',
    'GET /kepala-sekolah/akreditasi' => 'KepalaSekolahController@akreditasi',
    'GET /kepala-sekolah/kompetensi' => 'KepalaSekolahController@monitoringKompetensi',
    'GET /kepala-sekolah/kelulusan' => 'KepalaSekolahController@statistikKelulusan',
    
    // ==========================================
    // MENTOR INDUSTRI ROUTES
    // ==========================================
    'GET /mentor-industri' => 'MentorIndustriController@dashboard',
    'GET /mentor-industri/peserta-pkl' => 'MentorIndustriController@pesertaPKL',
    'POST /mentor-industri/penilaian' => 'MentorIndustriController@inputPenilaian',
    'GET /mentor-industri/logbook' => 'MentorIndustriController@verifikasiLogbook',
    'POST /mentor-industri/logbook/verify' => 'MentorIndustriController@verifyLogbook',
    'POST /mentor-industri/feedback' => 'MentorIndustriController@beriFeedback',
    'POST /mentor-industri/sertifikat' => 'MentorIndustriController@usulSertifikat',
    
    // ==========================================
    // UKK (UJI KOMPETENSI KEAHLIAN) ROUTES
    // ==========================================
    'GET /ukk' => 'UkkController@dashboard',
    'GET /ukk/jadwal' => 'UkkController@jadwal',
    'POST /ukk/jadwal/create' => 'UkkController@createJadwal',
    'GET /ukk/soal' => 'UkkController@bankSoal',
    'POST /ukk/soal/create' => 'UkkController@createSoal',
    'GET /ukk/ujian' => 'UkkController@ujian',
    'POST /ukk/nilai/input' => 'UkkController@inputNilaiUjian',
    'GET /ukk/nilai' => 'UkkController@rekapNilai',
    'POST /ukk/sertifikat/generate' => 'UkkController@generateSertifikat',
    'GET /ukk/sertifikat' => 'UkkController@daftarSertifikat',
    
    // ==========================================
    // API ROUTES (Optional for AJAX)
    // ==========================================
    'GET /api/notification/count' => 'ApiController@notificationCount',
    'GET /api/notification/list' => 'ApiController@notificationList',
    'POST /api/upload/image' => 'ApiController@uploadImage',
    'POST /api/upload/file' => 'ApiController@uploadFile',
    'GET /api/search' => 'ApiController@search',
    
    // ==========================================
    // ERROR PAGES
    // ==========================================
    'GET /404' => 'ErrorController@notFound',
    'GET /403' => 'ErrorController@forbidden',
    'GET /500' => 'ErrorController@serverError',
];
