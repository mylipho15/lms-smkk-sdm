-- ============================================
-- LMS SMK Kesehatan SDM Sumedang
-- Database Migration - Version 1.0
-- ============================================

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `lms-smkk-sdm` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `lms-smkk-sdm`;

-- ============================================
-- TABLE: roles
-- ============================================
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `display_name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: users
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT UNSIGNED NOT NULL,
    `nip_nis` VARCHAR(50) UNIQUE,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(255) NOT NULL,
    `gender` ENUM('L', 'P') DEFAULT 'L',
    `phone` VARCHAR(20),
    `avatar` VARCHAR(255),
    `birth_date` DATE,
    `address` TEXT,
    `is_active` TINYINT(1) DEFAULT 1,
    `last_login` TIMESTAMP NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: tahun_ajaran
-- ============================================
CREATE TABLE IF NOT EXISTS `tahun_ajaran` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(50) NOT NULL,
    `tahun_mulai` YEAR NOT NULL,
    `tahun_selesai` YEAR NOT NULL,
    `semester` ENUM('Ganjil', 'Genap') NOT NULL,
    `is_active` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: jurusan
-- ============================================
CREATE TABLE IF NOT EXISTS `jurusan` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode` VARCHAR(20) NOT NULL UNIQUE,
    `nama` VARCHAR(255) NOT NULL,
    `deskripsi` TEXT,
    `kepala_jurusan` INT UNSIGNED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`kepala_jurusan`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: kelas
-- ============================================
CREATE TABLE IF NOT EXISTS `kelas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `jurusan_id` INT UNSIGNED NOT NULL,
    `nama` VARCHAR(50) NOT NULL,
    `tingkat` TINYINT NOT NULL,
    `wali_kelas_id` INT UNSIGNED,
    `tahun_ajaran_id` INT UNSIGNED NOT NULL,
    `kapasitas` INT DEFAULT 32,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`wali_kelas_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: siswa
-- ============================================
CREATE TABLE IF NOT EXISTS `siswa` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL UNIQUE,
    `nis` VARCHAR(50) NOT NULL UNIQUE,
    `nisn` VARCHAR(50) UNIQUE,
    `kelas_id` INT UNSIGNED,
    `tempat_lahir` VARCHAR(100),
    `tanggal_lahir` DATE,
    `agama` VARCHAR(50),
    `alamat` TEXT,
    `nama_ayah` VARCHAR(255),
    `nama_ibu` VARCHAR(255),
    `no_telp_ortu` VARCHAR(20),
    `tgl_masuk` DATE,
    `status` ENUM('Aktif', 'Cuti', 'Keluar', 'Lulus') DEFAULT 'Aktif',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: orang_tua
-- ============================================
CREATE TABLE IF NOT EXISTS `orang_tua` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL UNIQUE,
    `siswa_id` INT UNSIGNED NOT NULL,
    `hubungan` ENUM('Ayah', 'Ibu', 'Wali') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: mata_pelajaran
-- ============================================
CREATE TABLE IF NOT EXISTS `mata_pelajaran` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kode` VARCHAR(20) NOT NULL UNIQUE,
    `nama` VARCHAR(255) NOT NULL,
    `kelompok` ENUM('Normatif', 'Adaptif', 'Produktif') NOT NULL,
    `kkm` DECIMAL(5,2) DEFAULT 75.00,
    `jurusan_id` INT UNSIGNED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: guru_mapel
-- ============================================
CREATE TABLE IF NOT EXISTS `guru_mapel` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `guru_id` INT UNSIGNED NOT NULL,
    `mapel_id` INT UNSIGNED NOT NULL,
    `kelas_id` INT UNSIGNED,
    `tahun_ajaran_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`guru_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_guru_mapel` (`guru_id`, `mapel_id`, `kelas_id`, `tahun_ajaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: jadwal_pelajaran
-- ============================================
CREATE TABLE IF NOT EXISTS `jadwal_pelajaran` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kelas_id` INT UNSIGNED NOT NULL,
    `mapel_id` INT UNSIGNED NOT NULL,
    `guru_id` INT UNSIGNED NOT NULL,
    `hari` ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') NOT NULL,
    `jam_mulai` TIME NOT NULL,
    `jam_selesai` TIME NOT NULL,
    `ruangan` VARCHAR(50),
    `tahun_ajaran_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`guru_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: materi
-- ============================================
CREATE TABLE IF NOT EXISTS `materi` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `guru_id` INT UNSIGNED NOT NULL,
    `mapel_id` INT UNSIGNED NOT NULL,
    `kelas_id` INT UNSIGNED,
    `judul` VARCHAR(255) NOT NULL,
    `deskripsi` TEXT,
    `jenis` ENUM('PDF', 'PPT', 'Video', 'Link', 'Text') NOT NULL,
    `file_path` VARCHAR(255),
    `link_url` VARCHAR(500),
    `konten` LONGTEXT,
    `views` INT UNSIGNED DEFAULT 0,
    `is_published` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`guru_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: tugas
-- ============================================
CREATE TABLE IF NOT EXISTS `tugas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `guru_id` INT UNSIGNED NOT NULL,
    `mapel_id` INT UNSIGNED NOT NULL,
    `kelas_id` INT UNSIGNED NOT NULL,
    `judul` VARCHAR(255) NOT NULL,
    `deskripsi` TEXT,
    `instruksi` TEXT,
    `file_path` VARCHAR(255),
    `tgl_diberikan` DATETIME NOT NULL,
    `tgl_deadline` DATETIME NOT NULL,
    `poin_maksimal` INT DEFAULT 100,
    `tipe` ENUM('Individu', 'Kelompok') DEFAULT 'Individu',
    `is_published` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`guru_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: jawaban_tugas
-- ============================================
CREATE TABLE IF NOT EXISTS `jawaban_tugas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tugas_id` INT UNSIGNED NOT NULL,
    `siswa_id` INT UNSIGNED NOT NULL,
    `jawaban` TEXT,
    `file_path` VARCHAR(255),
    `tgl_pengumpulan` DATETIME,
    `terlambat` TINYINT(1) DEFAULT 0,
    `nilai` DECIMAL(5,2),
    `feedback` TEXT,
    `status` ENUM('Belum Mengumpulkan', 'Sudah Mengumpulkan', 'Dinilai') DEFAULT 'Belum Mengumpulkan',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`tugas_id`) REFERENCES `tugas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_jawaban` (`tugas_id`, `siswa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: kuis
-- ============================================
CREATE TABLE IF NOT EXISTS `kuis` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `guru_id` INT UNSIGNED NOT NULL,
    `mapel_id` INT UNSIGNED NOT NULL,
    `kelas_id` INT UNSIGNED NOT NULL,
    `judul` VARCHAR(255) NOT NULL,
    `deskripsi` TEXT,
    `waktu_mulai` DATETIME NOT NULL,
    `waktu_selesai` DATETIME NOT NULL,
    `durasi` INT NOT NULL COMMENT 'Durasi dalam menit',
    `poin_per_soal` INT DEFAULT 1,
    `acak_soal` TINYINT(1) DEFAULT 0,
    `acak_jawaban` TINYINT(1) DEFAULT 0,
    `tampilkan_nilai` TINYINT(1) DEFAULT 1,
    `is_published` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`guru_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: soal_kuis
-- ============================================
CREATE TABLE IF NOT EXISTS `soal_kuis` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kuis_id` INT UNSIGNED NOT NULL,
    `pertanyaan` TEXT NOT NULL,
    `tipe` ENUM('Pilihan Ganda', 'Essay', 'Benar/Salah', 'Menjodohkan') NOT NULL,
    `poin` INT DEFAULT 1,
    `urutan` INT DEFAULT 0,
    `gambar` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`kuis_id`) REFERENCES `kuis`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: pilihan_jawaban
-- ============================================
CREATE TABLE IF NOT EXISTS `pilihan_jawaban` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `soal_id` INT UNSIGNED NOT NULL,
    `teks` TEXT NOT NULL,
    `is_correct` TINYINT(1) DEFAULT 0,
    `urutan` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`soal_id`) REFERENCES `soal_kuis`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: jawaban_kuis_siswa
-- ============================================
CREATE TABLE IF NOT EXISTS `jawaban_kuis_siswa` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `kuis_id` INT UNSIGNED NOT NULL,
    `siswa_id` INT UNSIGNED NOT NULL,
    `soal_id` INT UNSIGNED NOT NULL,
    `jawaban_siswa` TEXT,
    `is_correct` TINYINT(1) DEFAULT 0,
    `poin_diperoleh` DECIMAL(5,2) DEFAULT 0,
    `waktu_jawab` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`kuis_id`) REFERENCES `kuis`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`soal_id`) REFERENCES `soal_kuis`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: absensi
-- ============================================
CREATE TABLE IF NOT EXISTS `absensi` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `siswa_id` INT UNSIGNED NOT NULL,
    `kelas_id` INT UNSIGNED NOT NULL,
    `mapel_id` INT UNSIGNED,
    `tanggal` DATE NOT NULL,
    `status` ENUM('Hadir', 'Sakit', 'Izin', 'Alpha') NOT NULL DEFAULT 'Hadir',
    `keterangan` TEXT,
    `bukti_file` VARCHAR(255),
    `diinput_oleh` INT UNSIGNED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`diinput_oleh`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    UNIQUE KEY `unique_absensi` (`siswa_id`, `tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: nilai
-- ============================================
CREATE TABLE IF NOT EXISTS `nilai` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `siswa_id` INT UNSIGNED NOT NULL,
    `mapel_id` INT UNSIGNED NOT NULL,
    `kelas_id` INT UNSIGNED NOT NULL,
    `tahun_ajaran_id` INT UNSIGNED NOT NULL,
    `nilai_harian` DECIMAL(5,2) DEFAULT 0,
    `nilai_uts` DECIMAL(5,2) DEFAULT 0,
    `nilai_uas` DECIMAL(5,2) DEFAULT 0,
    `nilai_akhir` DECIMAL(5,2) DEFAULT 0,
    `predikat` VARCHAR(10),
    `keterangan` ENUM('Lulus', 'Remedial', 'Tidak Lulus') DEFAULT 'Lulus',
    `catatan` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_nilai` (`siswa_id`, `mapel_id`, `tahun_ajaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: pkl_logbook
-- ============================================
CREATE TABLE IF NOT EXISTS `pkl_logbook` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `siswa_id` INT UNSIGNED NOT NULL,
    `industri_id` INT UNSIGNED,
    `tanggal` DATE NOT NULL,
    `kegiatan` TEXT NOT NULL,
    `jam_mulai` TIME,
    `jam_selesai` TIME,
    `catatan` TEXT,
    `foto_kegiatan` VARCHAR(255),
    `status_verifikasi` ENUM('Pending', 'Disetujui', 'Ditolak') DEFAULT 'Pending',
    `verifikator_id` INT UNSIGNED,
    `tanggal_verifikasi` TIMESTAMP NULL,
    `feedback_verifikator` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`verifikator_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: pkl_penilaian
-- ============================================
CREATE TABLE IF NOT EXISTS `pkl_penilaian` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `siswa_id` INT UNSIGNED NOT NULL,
    `mentor_industri_id` INT UNSIGNED,
    `pembimbing_sekolah_id` INT UNSIGNED,
    `nilai_sikap` DECIMAL(5,2),
    `nilai_keterampilan` DECIMAL(5,2),
    `nilai_pengetahuan` DECIMAL(5,2),
    `nilai_akhir` DECIMAL(5,2),
    `predikat` VARCHAR(10),
    `catatan_mentor` TEXT,
    `catatan_pembimbing` TEXT,
    `sertifikat_file` VARCHAR(255),
    `tanggal_penilaian` DATE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`mentor_industri_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`pembimbing_sekolah_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: pengumuman
-- ============================================
CREATE TABLE IF NOT EXISTS `pengumuman` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(255) NOT NULL,
    `konten` TEXT NOT NULL,
    `kategori` ENUM('Umum', 'Akademik', 'PKL', 'UKK', 'Ekstrakurikuler') NOT NULL,
    `target_audience` ENUM('Semua', 'Guru', 'Siswa', 'Orang Tua', 'Mentor') DEFAULT 'Semua',
    `kelas_id` INT UNSIGNED,
    `file_attachment` VARCHAR(255),
    `is_pinned` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `dibuat_oleh` INT UNSIGNED NOT NULL,
    `tgl_publish` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`dibuat_oleh`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: forum_diskusi
-- ============================================
CREATE TABLE IF NOT EXISTS `forum_diskusi` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `mapel_id` INT UNSIGNED,
    `kelas_id` INT UNSIGNED,
    `user_id` INT UNSIGNED NOT NULL,
    `judul` VARCHAR(255) NOT NULL,
    `konten` TEXT NOT NULL,
    `is_locked` TINYINT(1) DEFAULT 0,
    `is_pinned` TINYINT(1) DEFAULT 0,
    `views` INT UNSIGNED DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: komentar_forum
-- ============================================
CREATE TABLE IF NOT EXISTS `komentar_forum` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `diskusi_id` INT UNSIGNED NOT NULL,
    `parent_id` INT UNSIGNED,
    `user_id` INT UNSIGNED NOT NULL,
    `konten` TEXT NOT NULL,
    `file_attachment` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`diskusi_id`) REFERENCES `forum_diskusi`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`parent_id`) REFERENCES `komentar_forum`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: pesan
-- ============================================
CREATE TABLE IF NOT EXISTS `pesan` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `pengirim_id` INT UNSIGNED NOT NULL,
    `penerima_id` INT UNSIGNED NOT NULL,
    `subjek` VARCHAR(255),
    `konten` TEXT NOT NULL,
    `file_attachment` VARCHAR(255),
    `is_read` TINYINT(1) DEFAULT 0,
    `read_at` TIMESTAMP NULL,
    `deleted_by_sender` TINYINT(1) DEFAULT 0,
    `deleted_by_receiver` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`pengirim_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`penerima_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: notifikasi
-- ============================================
CREATE TABLE IF NOT EXISTS `notifikasi` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `url` VARCHAR(500),
    `is_read` TINYINT(1) DEFAULT 0,
    `read_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: catatan_perilaku
-- ============================================
CREATE TABLE IF NOT EXISTS `catatan_perilaku` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `siswa_id` INT UNSIGNED NOT NULL,
    `guru_id` INT UNSIGNED NOT NULL,
    `jenis` ENUM('Positif', 'Negatif') NOT NULL,
    `kategori` VARCHAR(100),
    `deskripsi` TEXT NOT NULL,
    `poin` INT DEFAULT 0,
    `bukti_file` VARCHAR(255),
    `tindak_lanjut` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`guru_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: ukk_jadwal
-- ============================================
CREATE TABLE IF NOT EXISTS `ukk_jadwal` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `jurusan_id` INT UNSIGNED NOT NULL,
    `tahun_ajaran_id` INT UNSIGNED NOT NULL,
    `nama_ujian` VARCHAR(255) NOT NULL,
    `tgl_mulai` DATE NOT NULL,
    `tgl_selesai` DATE NOT NULL,
    `lokasi` VARCHAR(255),
    `keterangan` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: ukk_nilai
-- ============================================
CREATE TABLE IF NOT EXISTS `ukk_nilai` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `siswa_id` INT UNSIGNED NOT NULL,
    `ukk_jadwal_id` INT UNSIGNED NOT NULL,
    `penguji_1_id` INT UNSIGNED,
    `penguji_2_id` INT UNSIGNED,
    `nilai_produk` DECIMAL(5,2),
    `nilai_proses` DECIMAL(5,2),
    `nilai_sikap` DECIMAL(5,2),
    `nilai_akhir` DECIMAL(5,2),
    `predikat` VARCHAR(10),
    `keterangan` ENUM('Lulus', 'Tidak Lulus'),
    `sertifikat_file` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`ukk_jadwal_id`) REFERENCES `ukk_jadwal`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`penguji_1_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`penguji_2_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: industri_pasangan
-- ============================================
CREATE TABLE IF NOT EXISTS `industri_pasangan` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(255) NOT NULL,
    `alamat` TEXT,
    `kota` VARCHAR(100),
    `provinsi` VARCHAR(100),
    `telepon` VARCHAR(20),
    `email` VARCHAR(255),
    `website` VARCHAR(255),
    `kontak_person` VARCHAR(255),
    `bidang_usaha` VARCHAR(255),
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: pkl_penempatan
-- ============================================
CREATE TABLE IF NOT EXISTS `pkl_penempatan` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `siswa_id` INT UNSIGNED NOT NULL,
    `industri_id` INT UNSIGNED NOT NULL,
    `tahun_ajaran_id` INT UNSIGNED NOT NULL,
    `tgl_mulai` DATE NOT NULL,
    `tgl_selesai` DATE NOT NULL,
    `mentor_industri_id` INT UNSIGNED,
    `pembimbing_sekolah_id` INT UNSIGNED,
    `status` ENUM('Berjalan', 'Selesai', 'Dibatalkan') DEFAULT 'Berjalan',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`industri_id`) REFERENCES `industri_pasangan`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`mentor_industri_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`pembimbing_sekolah_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT DEFAULT DATA
-- ============================================

-- Insert default roles
INSERT INTO `roles` (`name`, `display_name`, `description`) VALUES
('super_admin', 'Super Admin', 'Administrator sistem dengan akses penuh'),
('admin_sekolah', 'Admin Sekolah', 'Administrator sekolah untuk manajemen data'),
('guru', 'Guru / Instruktur', 'Pengajar dan pembimbing siswa'),
('siswa', 'Siswa / Peserta Didik', 'Peserta didik di sekolah'),
('wali_kelas', 'Wali Kelas', 'Guru yang bertugas sebagai wali kelas'),
('orang_tua', 'Orang Tua / Wali Murid', 'Orang tua atau wali dari siswa'),
('kepala_sekolah', 'Kepala Sekolah', 'Manajemen dan pimpinan sekolah'),
('mentor_industri', 'Mentor Industri', 'Pembimbing PKL dari industri');

-- Insert default super admin user (password: admin123)
INSERT INTO `users` (`role_id`, `username`, `email`, `password`, `full_name`, `is_active`) VALUES
(1, 'superadmin', 'superadmin@smkksdm.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 1);

-- Insert sample tahun ajaran
INSERT INTO `tahun_ajaran` (`nama`, `tahun_mulai`, `tahun_selesai`, `semester`, `is_active`) VALUES
('2024/2025 Ganjil', 2024, 2025, 'Ganjil', 1),
('2024/2025 Genap', 2024, 2025, 'Genap', 0);

-- Insert sample jurusan untuk SMK Kesehatan
INSERT INTO `jurusan` (`kode`, `nama`, `deskripsi`) VALUES
('KEP', 'Keperawatan', 'Kompetensi Keahlian Keperawatan'),
('FA', 'Farmasi', 'Kompetensi Keahlian Farmasi'),
('AK', 'Akuntansi', 'Kompetensi Keahlian Akuntansi'),
('TKJ', 'Teknik Komputer Jaringan', 'Kompetensi Keahlian Teknik Komputer dan Jaringan');
