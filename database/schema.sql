-- LMS SMK Kesehatan SDM Sumedang
-- Database Schema

CREATE DATABASE IF NOT EXISTS `lms-smkk-sdm` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lms-smkk-sdm`;

-- ============================================
-- TABLE: users (Base user table for all roles)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    avatar VARCHAR(255) DEFAULT 'default-avatar.png',
    role ENUM('super_admin', 'admin_sekolah', 'guru', 'siswa', 'wali_kelas', 'orang_tua', 'kepala_sekolah', 'mentor_industri') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role (role),
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: tahun_ajaran
-- ============================================
CREATE TABLE IF NOT EXISTS tahun_ajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL, -- e.g., "2024/2025"
    semester ENUM('ganjil', 'genap') NOT NULL,
    is_active BOOLEAN DEFAULT FALSE,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: jurusan (Kompetensi Keahlian)
-- ============================================
CREATE TABLE IF NOT EXISTS jurusan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_jurusan VARCHAR(20) UNIQUE NOT NULL,
    nama_jurusan VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    kepala_jurusan_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kepala_jurusan_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_kode (kode_jurusan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: kelas
-- ============================================
CREATE TABLE IF NOT EXISTS kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL, -- e.g., "X-RPL-1", "XI-TKJ-2"
    jurusan_id INT NOT NULL,
    tahun_ajaran_id INT NOT NULL,
    wali_kelas_id INT NULL,
    kapasitas INT DEFAULT 32,
    ruangan VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jurusan_id) REFERENCES jurusan(id) ON DELETE CASCADE,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE,
    FOREIGN KEY (wali_kelas_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_jurusan (jurusan_id),
    INDEX idx_tahun_ajaran (tahun_ajaran_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: siswa (Extended student profile)
-- ============================================
CREATE TABLE IF NOT EXISTS siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    nis VARCHAR(50) UNIQUE NOT NULL,
    nisn VARCHAR(50) UNIQUE,
    kelas_id INT NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat TEXT,
    nama_ortu VARCHAR(255),
    telepon_ortu VARCHAR(20),
    foto_siswa VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    INDEX idx_nis (nis),
    INDEX idx_kelas (kelas_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: orang_tua (Parent/Guardian profile)
-- ============================================
CREATE TABLE IF NOT EXISTS orang_tua (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    siswa_id INT NOT NULL,
    hubungan ENUM('ayah', 'ibu', 'wali') NOT NULL,
    pekerjaan VARCHAR(100),
    alamat TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    INDEX idx_siswa (siswa_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: mata_pelajaran
-- ============================================
CREATE TABLE IF NOT EXISTS mata_pelajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_mapel VARCHAR(20) UNIQUE NOT NULL,
    nama_mapel VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    kategori ENUM('normatif', 'adaptif', 'produktif', 'umum') DEFAULT 'umum',
    kktp DECIMAL(5,2) DEFAULT 75.00, -- Kriteria Ketuntasan Tujuan Pembelajaran
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_kode (kode_mapel),
    INDEX idx_kategori (kategori)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: guru_mapel (Teacher assignment to subjects)
-- ============================================
CREATE TABLE IF NOT EXISTS guru_mapel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guru_id INT NOT NULL,
    mapel_id INT NOT NULL,
    kelas_id INT NOT NULL,
    tahun_ajaran_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE,
    UNIQUE KEY unique_guru_mapel_kelas (guru_id, mapel_id, kelas_id, tahun_ajaran_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: kursus (Course/Learning module)
-- ============================================
CREATE TABLE IF NOT EXISTS kursus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guru_mapel_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    capaian_pembelajaran TEXT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (guru_mapel_id) REFERENCES guru_mapel(id) ON DELETE CASCADE,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: materi (Learning materials)
-- ============================================
CREATE TABLE IF NOT EXISTS materi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kursus_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    jenis ENUM('pdf', 'ppt', 'video', 'link', 'text') NOT NULL,
    konten TEXT, -- For text or link URL
    file_path VARCHAR(500), -- For uploaded files
    urutan INT DEFAULT 0,
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kursus_id) REFERENCES kursus(id) ON DELETE CASCADE,
    INDEX idx_kursus (kursus_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: tugas (Assignments)
-- ============================================
CREATE TABLE IF NOT EXISTS tugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kursus_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    instruksi TEXT,
    deadline DATETIME NOT NULL,
    poin_maksimal INT DEFAULT 100,
    rubrik_penilaian TEXT, -- JSON format
    allow_late_submission BOOLEAN DEFAULT FALSE,
    late_penalty_percent DECIMAL(5,2) DEFAULT 10.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kursus_id) REFERENCES kursus(id) ON DELETE CASCADE,
    INDEX idx_kursus (kursus_id),
    INDEX idx_deadline (deadline)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: submissions (Student task submissions)
-- ============================================
CREATE TABLE IF NOT EXISTS submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tugas_id INT NOT NULL,
    siswa_id INT NOT NULL,
    file_path VARCHAR(500),
    catatan_siswa TEXT,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_late BOOLEAN DEFAULT FALSE,
    nilai DECIMAL(5,2),
    feedback_guru TEXT,
    graded_by INT NULL,
    graded_at DATETIME NULL,
    status ENUM('submitted', 'graded', 'returned') DEFAULT 'submitted',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tugas_id) REFERENCES tugas(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (graded_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_submission (tugas_id, siswa_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: kuis (Quizzes/Exams)
-- ============================================
CREATE TABLE IF NOT EXISTS kuis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kursus_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    durasi_menit INT DEFAULT 60,
    total_poin INT DEFAULT 100,
    pass_score DECIMAL(5,2) DEFAULT 70.00,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    shuffle_soal BOOLEAN DEFAULT TRUE,
    shuffle_opsi BOOLEAN DEFAULT TRUE,
    show_result_immediately BOOLEAN DEFAULT FALSE,
    attempts_allowed INT DEFAULT 1,
    prevent_copy_paste BOOLEAN DEFAULT TRUE,
    status ENUM('draft', 'active', 'closed') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kursus_id) REFERENCES kursus(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_time (start_time, end_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: soal (Quiz questions)
-- ============================================
CREATE TABLE IF NOT EXISTS soal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kuis_id INT NOT NULL,
    jenis ENUM('pilihan_ganda', 'essay', 'benar_salah', 'menjodohkan') NOT NULL,
    pertanyaan TEXT NOT NULL,
    gambar_soal VARCHAR(500),
    poin INT DEFAULT 1,
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kuis_id) REFERENCES kuis(id) ON DELETE CASCADE,
    INDEX idx_kuis (kuis_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: opsi_jawaban (Answer options for multiple choice)
-- ============================================
CREATE TABLE IF NOT EXISTS opsi_jawaban (
    id INT AUTO_INCREMENT PRIMARY KEY,
    soal_id INT NOT NULL,
    teks TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    urutan INT DEFAULT 0,
    FOREIGN KEY (soal_id) REFERENCES soal(id) ON DELETE CASCADE,
    INDEX idx_soal (soal_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: jawaban_siswa (Student quiz answers)
-- ============================================
CREATE TABLE IF NOT EXISTS jawaban_siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kuis_id INT NOT NULL,
    siswa_id INT NOT NULL,
    soal_id INT NOT NULL,
    jawaban TEXT,
    is_correct BOOLEAN DEFAULT FALSE,
    poin_diperoleh DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kuis_id) REFERENCES kuis(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (soal_id) REFERENCES soal(id) ON DELETE CASCADE,
    INDEX idx_kuis_siswa (kuis_id, siswa_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: absensi (Attendance)
-- ============================================
CREATE TABLE IF NOT EXISTS absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kelas_id INT NOT NULL,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('hadir', 'sakit', 'izin', 'alpha', 'terlambat') DEFAULT 'hadir',
    keterangan TEXT,
    check_in_time TIME,
    check_out_time TIME,
    qr_code VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    UNIQUE KEY unique_absensi (kelas_id, siswa_id, tanggal),
    INDEX idx_tanggal (tanggal),
    INDEX idx_siswa (siswa_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: jadwal (Schedule)
-- ============================================
CREATE TABLE IF NOT EXISTS jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kelas_id INT NOT NULL,
    guru_mapel_id INT NOT NULL,
    hari ENUM('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    ruangan VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    FOREIGN KEY (guru_mapel_id) REFERENCES guru_mapel(id) ON DELETE CASCADE,
    INDEX idx_hari (hari)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: forum (Discussion forums)
-- ============================================
CREATE TABLE IF NOT EXISTS forum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kursus_id INT NULL,
    kelas_id INT NULL,
    user_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    konten TEXT NOT NULL,
    is_pinned BOOLEAN DEFAULT FALSE,
    is_locked BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kursus_id) REFERENCES kursus(id) ON DELETE CASCADE,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_kursus (kursus_id),
    INDEX idx_kelas (kelas_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: komentar_forum (Forum comments/replies)
-- ============================================
CREATE TABLE IF NOT EXISTS komentar_forum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    forum_id INT NOT NULL,
    parent_id INT NULL,
    user_id INT NOT NULL,
    konten TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (forum_id) REFERENCES forum(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES komentar_forum(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_forum (forum_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: pesan (Internal messaging)
-- ============================================
CREATE TABLE IF NOT EXISTS pesan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subjek VARCHAR(255),
    konten TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_receiver (receiver_id, is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: pkl_placement (PKL/Prakerin placements)
-- ============================================
CREATE TABLE IF NOT EXISTS pkl_placement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    mentor_industri_id INT NULL,
    nama_perusahaan VARCHAR(255) NOT NULL,
    alamat_perusahaan TEXT,
    bidang_pekerjaan VARCHAR(255),
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    status ENUM('pending', 'approved', 'ongoing', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_industri_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: logbook_pkl (PKL daily logbook)
-- ============================================
CREATE TABLE IF NOT EXISTS logbook_pkl (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pkl_placement_id INT NOT NULL,
    tanggal DATE NOT NULL,
    kegiatan TEXT NOT NULL,
    hambatan TEXT,
    solusi TEXT,
    jam_kerja DECIMAL(4,2),
    file_bukti VARCHAR(500),
    status_verifikasi ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    catatan_mentor TEXT,
    nilai_harian DECIMAL(5,2),
    verified_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pkl_placement_id) REFERENCES pkl_placement(id) ON DELETE CASCADE,
    INDEX idx_tanggal (tanggal),
    INDEX idx_status (status_verifikasi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: penilaian_pkl (PKL final assessment)
-- ============================================
CREATE TABLE IF NOT EXISTS penilaian_pkl (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pkl_placement_id INT NOT NULL,
    mentor_industri_id INT NOT NULL,
    nilai_sikap DECIMAL(5,2),
    nilai_keterampilan DECIMAL(5,2),
    nilai_pengetahuan DECIMAL(5,2),
    nilai_akhir DECIMAL(5,2),
    predikat ENUM('A', 'B', 'C', 'D', 'E'),
    catatan TEXT,
    sertifikat_generated BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pkl_placement_id) REFERENCES pkl_placement(id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_industri_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_penilaian (pkl_placement_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: pengumuman (Announcements)
-- ============================================
CREATE TABLE IF NOT EXISTS pengumuman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    konten TEXT NOT NULL,
    target_audience ENUM('all', 'guru', 'siswa', 'orang_tua', 'specific_class') DEFAULT 'all',
    kelas_id INT NULL,
    is_pinned BOOLEAN DEFAULT FALSE,
    expire_date DATETIME NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_target (target_audience)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: notifikasi (Notifications)
-- ============================================
CREATE TABLE IF NOT EXISTS notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tipe ENUM('tugas_baru', 'deadline', 'nilai_keluar', 'pengumuman', 'pesan_baru', 'absensi', 'lainnya') NOT NULL,
    judul VARCHAR(255),
    konten TEXT,
    link VARCHAR(500),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id, is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: sertifikat (Certificates)
-- ============================================
CREATE TABLE IF NOT EXISTS sertifikat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    jenis ENUM('kursus', 'ukk', 'pkl', 'prestasi') NOT NULL,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    nomor_sertifikat VARCHAR(100) UNIQUE,
    file_path VARCHAR(500),
    issued_date DATE,
    valid_until DATE NULL,
    qr_code_hash VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    INDEX idx_jenis (jenis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: catatan_perilaku (Behavior records)
-- ============================================
CREATE TABLE IF NOT EXISTS catatan_perilaku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    jenis ENUM('positif', 'negatif') NOT NULL,
    kategori VARCHAR(100),
    deskripsi TEXT NOT NULL,
    poin INT DEFAULT 0,
    tanggal DATE NOT NULL,
    dibuat_oleh INT NOT NULL,
    tindak_lanjut TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_siswa (siswa_id),
    INDEX idx_jenis (jenis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: backup_log (System backup logs)
-- ============================================
CREATE TABLE IF NOT EXISTS backup_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    backup_type ENUM('full', 'database', 'files') NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size BIGINT,
    status ENUM('success', 'failed') NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT DEFAULT SUPER ADMIN
-- Password: admin123 (hashed with PASSWORD_DEFAULT)
-- ============================================
INSERT INTO users (username, email, password_hash, full_name, role) 
VALUES ('superadmin', 'admin@smkksdm.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'super_admin');

-- ============================================
-- INSERT SAMPLE DATA
-- ============================================
INSERT INTO tahun_ajaran (nama, semester, is_active, start_date, end_date) 
VALUES ('2024/2025', 'ganjil', TRUE, '2024-07-01', '2024-12-31');

INSERT INTO jurusan (kode_jurusan, nama_jurusan, deskripsi) VALUES
('TKJ', 'Teknik Komputer dan Jaringan', 'Kompetensi keahlian di bidang jaringan komputer'),
('AKL', 'Akuntansi Keuangan Lembaga', 'Kompetensi keahlian di bidang akuntansi'),
('OTKP', 'Otomatisasi Tata Kelola Perkantoran', 'Kompetensi keahlian di bidang administrasi perkantoran'),
('KEP', 'Keperawatan', 'Kompetensi keahlian di bidang kesehatan dan keperawatan'),
('FA', 'Farmasi', 'Kompetensi keahlian di bidang farmasi dan obat-obatan');
