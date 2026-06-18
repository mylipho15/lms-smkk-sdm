# 📚 LMS SMK Kesehatan SDM Sumedang

**Learning Management System untuk SMK Kesehatan SDM Sumedang**

![PHP](https://img.shields.io/badge/PHP-7.4+-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

---

## 🎯 Tentang Proyek

LMS (Learning Management System) ini dirancang khusus untuk **SMK Kesehatan SDM Sumedang** dengan fitur lengkap untuk mendukung proses pembelajaran digital, manajemen PKL/Prakerin, dan Uji Kompetensi Keahlian (UKK).

### ✨ Fitur Utama

- **8 Role Pengguna**: Super Admin, Admin Sekolah, Guru, Siswa, Wali Kelas, Orang Tua, Kepala Sekolah, Mentor Industri
- **Claymorphism Theme**: Desain modern dengan dukungan Light Mode & Dark Mode
- **Manajemen Pembelajaran**: Materi, Tugas, Kuis, Absensi, Nilai
- **PKL/Prakerin**: Logbook digital, penilaian mentor industri, laporan
- **UKK**: Bank soal, jadwal ujian, sertifikat digital
- **Komunikasi**: Forum diskusi, pesan internal, notifikasi

---

## 📁 Struktur Folder

```
C:\laragon\www\lms-smkk-sdm\
│
├── config/                     # Konfigurasi aplikasi
│   ├── app.php                 # Konstanta global, URL dasar, timezone
│   ├── database.php            # Konfigurasi koneksi PDO MySQL
│   └── routes.php              # Daftar routing (URL -> Controller@Method)
│
├── app/                        # Logika inti aplikasi (MVC)
│   ├── Core/                   # Framework mini buatan sendiri
│   │   ├── App.php             # Bootstrapper aplikasi
│   │   ├── Controller.php      # Base Controller
│   │   ├── Model.php           # Base Model (Koneksi DB)
│   │   ├── Router.php          # Custom Router (Mapping URL)
│   │   ├── Request.php         # Menangani $_GET, $_POST, $_FILES
│   │   ├── Response.php        # Menangani redirect, JSON response
│   │   ├── Database.php        # Singleton PDO Connection
│   │   └── Session.php         # Manajemen Session & Flash messages
│   │
│   ├── Middleware/             # Penjaga akses (Auth & Role)
│   │   ├── AuthMiddleware.php  # Cek apakah user sudah login
│   │   └── RoleMiddleware.php  # Cek role (Admin, Guru, Siswa, Mentor PKL)
│   │
│   ├── Controllers/            # Logika bisnis per modul
│   │   ├── AuthController.php  # Login, Logout, Register, Lupa Password
│   │   ├── AdminController.php # Kelola User, Jurusan, Kelas, Tahun Ajaran
│   │   ├── GuruController.php  # Kelola Materi, Tugas, Nilai, Absensi
│   │   ├── SiswaController.php # Dashboard Siswa, Kerjakan Tugas, Kuis
│   │   ├── PklController.php   # Khusus SMK: Logbook, Laporan PKL, Nilai Industri
│   │   └── UkkController.php   # Khusus SMK: Jadwal & Nilai Uji Kompetensi
│   │
│   ├── Models/                 # Interaksi dengan Database (CRUD)
│   │   ├── User.php
│   │   ├── Course.php          # Mata Pelajaran / Produktif SMK
│   │   ├── Material.php
│   │   ├── Assignment.php
│   │   ├── Attendance.php
│   │   └── PklLog.php          # Model khusus logbook Prakerin/PKL
│   │
│   └── Helpers/                # Fungsi pembantu global
│       ├── url.php             # Fungsi base_url(), redirect()
│       ├── format.php          # Format tanggal, rupiah, tanggal Indonesia
│       └── security.php        # Fungsi csrf_token(), sanitize()
│
├── resources/                  # File mentah (Views & Assets)
│   ├── views/                  # File HTML/PHP (Template)
│   │   ├── layouts/            # Master template (header, sidebar, footer)
│   │   ├── auth/               # View login, register
│   │   ├── admin/              # View dashboard admin
│   │   ├── guru/               # View dashboard guru
│   │   ├── siswa/              # View dashboard siswa
│   │   └── errors/             # View 404, 403, 500
│   │
│   └── assets/                 # Akan di-copy/di-link ke public saat build/deploy
│       ├── css/                # Bootstrap, Tailwind, atau CSS custom
│       ├── js/                 # Vanilla JS, jQuery, Chart.js
│       ├── img/                # Logo sekolah, ikon
│       └── vendor/             # Library frontend (jika tidak pakai CDN)
│
├── public/                     # 🌐 SATU-SATUNYA folder yang bisa diakses via browser
│   ├── index.php               # Front Controller (Entry point semua request)
│   ├── .htaccess               # Rewrite rule untuk URL bersih & keamanan
│   └── assets/                 # Folder hasil copy dari resources/assets
│
├── storage/                    # Folder untuk file yang di-generate atau di-upload
│   ├── uploads/                # File upload user
│   │   ├── materi/             # PDF, PPT, Video materi
│   │   ├── tugas/              # File jawaban tugas siswa
│   │   ├── pkl/                # Laporan & foto kegiatan PKL
│   │   ├── sertifikat/         # Sertifikat UKL/UKK yang di-generate
│   │   └── profiles/           # Foto profil user
│   ├── logs/                   # Error log aplikasi (error.log)
│   └── cache/                  # Cache query atau session file
│
├── database/                   # File database
│   ├── migrations/             # File SQL untuk create table
│   └── seeders/                # Data dummy (Admin default, Jurusan SMK, dll)
│
├── .env                        # Environment variables
├── .gitignore                  # Ignore vendor/, storage/uploads/, .env
├── composer.json               # Konfigurasi Composer & Autoloading PSR-4
└── README.md                   # Dokumentasi ini
```

---

## 🚀 Instalasi

### Prasyarat

- PHP >= 7.4
- MySQL >= 5.7 atau MariaDB >= 10.3
- Composer (untuk dependency management)
- Laragon/XAMPP/WAMP atau web server lainnya
- mod_rewrite Apache enabled

### Langkah Instalasi

#### 1. Clone/Download Repository

```bash
cd C:\laragon\www
# atau clone dari git
git clone <repository-url> lms-smkk-sdm
```

#### 2. Install Dependencies

```bash
cd lms-smkk-sdm
composer install
```

#### 3. Konfigurasi Environment

Copy file `.env.example` menjadi `.env`:

```bash
copy .env.example .env
```

Edit file `.env` sesuai konfigurasi Anda:

```env
APP_NAME="LMS SMKK SDM"
APP_URL="http://localhost/lms-smkk-sdm/public"
APP_DEBUG=true

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=lms-smkk-sdm
DB_USERNAME=root
DB_PASSWORD=root
```

#### 4. Buat Database

Buat database baru di MySQL:

```sql
CREATE DATABASE `lms-smkk-sdm` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

#### 5. Import Database Schema

Jalankan file migrasi:

```bash
# Via command line
mysql -u root -p lms-smkk-sdm < database/migrations/001_initial_schema.sql

# Atau import via phpMyAdmin
# 1. Buka phpMyAdmin
# 2. Pilih database lms-smkk-sdm
# 3. Klik tab "Import"
# 4. Upload file 001_initial_schema.sql
```

#### 6. Set Permissions

Pastikan folder berikut memiliki permission write:

```bash
# Untuk Linux/Mac
chmod -R 775 storage/
chmod -R 775 public/assets/

# Untuk Windows (via GUI)
# Klik kanan folder > Properties > Security > Edit > Add full control for Users
```

#### 7. Akses Aplikasi

Buka browser dan akses:

```
http://localhost/lms-smkk-sdm/public
```

#### 8. Login Pertama Kali

Gunakan kredensial default untuk Super Admin:

```
Username: superadmin
Email: superadmin@smkksdm.sch.id
Password: admin123
```

**⚠️ PENTING**: Segera ubah password default setelah login pertama kali!

---

## 👥 Role & Hak Akses

| Role | Deskripsi | Akses Utama |
|------|-----------|-------------|
| **Super Admin** | Administrator sistem | Manajemen user, role, backup/restore, konfigurasi server |
| **Admin Sekolah** | Administrator sekolah | Tahun ajaran, kelas, jurusan, guru, siswa, jadwal |
| **Guru** | Pengajar/Instruktur | Buat kursus, upload materi, tugas, kuis, nilai, absensi |
| **Siswa** | Peserta didik | Akses materi, kerjakan tugas/kuis, lihat nilai, upload PKL |
| **Wali Kelas** | Guru wali kelas | Pantau kehadiran, rekap nilai, komunikasi ortu, catatan perilaku |
| **Orang Tua** | Wali murid | Lihat laporan anak (nilai, absen, tugas), notifikasi |
| **Kepala Sekolah** | Manajemen | Dashboard analitik, laporan akreditasi, monitoring |
| **Mentor Industri** | Pembimbing PKL | Input nilai praktik, verifikasi logbook, feedback |

---

## 📊 Fitur Detail per Modul

### 1. Autentikasi & RBAC
- Login/Logout dengan session
- Reset password via email
- Role-based access control (RBAC)
- Session management dengan timeout
- CSRF protection

### 2. Manajemen Kelas & Kompetensi
- Pengelompokan per jurusan (Keperawatan, Farmasi, dll)
- Manajemen kelas dan tingkat
- Mata pelajaran normatif, adaptif, produktif
- Capaian pembelajaran per kompetensi

### 3. Materi Pembelajaran
- Upload PDF, PPT, DOC, video
- Link eksternal dan embed YouTube
- Catatan terstruktur per materi
- Tracking views materi

### 4. Penugasan & Penilaian
- Deadline pengumpulan
- Rubrik penilaian
- Upload file jawaban
- Feedback guru per tugas
- Rekap nilai otomatis/manual

### 5. Kuis & Ujian Online
- Pilihan ganda, essay, benar/salah
- Timer otomatis
- Acak soal dan jawaban
- Auto-grading untuk pilihan ganda
- Pencegahan kecurangan dasar

### 6. Absensi Digital
- Input harian per pertemuan
- QR code check-in (opsional)
- Rekap bulanan
- Status: Hadir, Sakit, Izin, Alpha
- Bukti file untuk sakit/izin

### 7. Jadwal & Kalender Akademik
- Jadwal pelajaran per kelas
- Jadwal ujian
- Jadwal PKL
- Holiday akademik

### 8. Forum & Pesan Internal
- Diskusi per kelas/mapel
- Chat privat guru-siswa
- Thread tugas
- Notifikasi real-time

### 9. Laporan & Dashboard
- Grafik progress belajar
- Export Excel/PDF
- Nilai akhir semester
- Kompetensi tercapai
- Statistik kehadiran

### 10. Manajemen PKL / Prakerin
- Logbook harian siswa
- Laporan mingguan
- Penilaian mentor industri
- Jadwal penempatan
- Verifikasi online

### 11. Uji Kompetensi (UKK) & Sertifikat
- Bank soal UKK
- Jadwal ujian praktik
- Input nilai penguji
- Generate sertifikat digital
- Download sertifikat PDF

### 12. Notifikasi & Pengumuman
- Broadcast sistem
- Notifikasi tugas baru
- Reminder deadline
- Pengumuman sekolah
- Email notification

---

## 🎨 Tema Claymorphism

Aplikasi menggunakan desain **Claymorphism** dengan karakteristik:

- **Soft shadows** untuk efek 3D
- **Rounded corners** untuk tampilan friendly
- **Light/Dark mode** toggle
- **Smooth transitions** dan animasi
- **Responsive design** untuk semua device

### Toggle Dark Mode

Klik tombol toggle di pojok kanan atas untuk beralih antara Light dan Dark mode.

---

## 🔧 Konfigurasi Tambahan

### Mengubah Logo Sekolah

1. Upload logo baru ke `public/assets/img/`
2. Ganti nama file menjadi `logo.png` atau update reference di layout

### Mengatur Max Upload Size

Edit file `.htaccess`:

```apache
php_value upload_max_filesize 20M
php_value post_max_size 20M
```

### Backup Database Otomatis

Tambahkan cron job untuk backup harian:

```bash
# Backup setiap hari jam 2 pagi
0 2 * * * mysqldump -u root -p lms-smkk-sdm > backup_$(date +\%Y\%m\%d).sql
```

---

## 🛡️ Keamanan

### Best Practices yang Diterapkan

- ✅ Prepared statements (PDO) untuk mencegah SQL Injection
- ✅ CSRF token untuk semua form POST
- ✅ XSS protection dengan htmlspecialchars
- ✅ Password hashing dengan bcrypt
- ✅ Session regeneration setelah login
- ✅ Rate limiting untuk login attempts
- ✅ File upload validation
- ✅ Role-based access control

### Rekomendasi Tambahan

- Gunakan HTTPS di production
- Aktifkan firewall aplikasi web (WAF)
- Update dependencies secara berkala
- Backup database rutin
- Monitor error logs

---

## 📝 API Endpoints (Opsional)

Aplikasi menyediakan beberapa endpoint API untuk integrasi:

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/notification/count` | Hitung notifikasi belum dibaca |
| GET | `/api/notification/list` | List notifikasi |
| POST | `/api/upload/image` | Upload image |
| POST | `/api/upload/file` | Upload file umum |
| GET | `/api/search` | Pencarian global |

---

## 🐛 Troubleshooting

### Error: Database Connection Failed

```
Solusi:
1. Pastikan MySQL service berjalan
2. Cek kredensial di file .env
3. Pastikan database sudah dibuat
4. Cek user MySQL memiliki akses ke database
```

### Error: 404 Not Found pada semua halaman

```
Solusi:
1. Pastikan mod_rewrite Apache enabled
2. Cek AllowOverride All di httpd.conf
3. Pastikan .htaccess ada di folder public
4. Restart Apache
```

### Error: Permission Denied saat upload file

```
Solusi:
1. chmod 775 storage/uploads/
2. chown www-data:www-data storage/uploads/ (Linux)
3. Restart Apache/Nginx
```

### Session tidak tersimpan

```
Solusi:
1. Cek session.save_path di php.ini
2. Pastikan folder session writable
3. Restart web server
```

---

## 📞 Support

Untuk bantuan teknis dan pertanyaan:

- **Email**: admin@smkksdm.sch.id
- **Phone**: (022) XXXXXXX
- **Documentation**: `/docs` folder

---

## 📄 License

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 👨‍💻 Developer Credits

Dibuat dengan ❤️ untuk **SMK Kesehatan SDM Sumedang**

**Tech Stack:**
- PHP 7.4+ (Vanilla)
- MySQL 5.7+
- CSS3 (Custom Claymorphism)
- Vanilla JavaScript
- No Framework (Custom MVC)

---

## 🔄 Changelog

### Version 1.0.0 (2024)
- Initial release
- Core MVC framework
- 8 user roles
- Complete LMS features
- PKL/Prakerin module
- UKK module
- Claymorphism theme
- Light/Dark mode

---

## 📚 Resources

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Claymorphism Design](https://claymorphism.com/)

---

**© 2024 SMK Kesehatan SDM Sumedang. All Rights Reserved.**
