# LMS SMK Kesehatan SDM Sumedang

Learning Management System untuk SMK Kesehatan SDM Sumedang dengan desain Claymorphism dan dukungan Light/Dark Mode.

## 📋 Fitur Utama

### Peran Pengguna (Roles)
- **Super Admin**: Kelola sistem global, backup/restore, konfigurasi server, manajemen role & hak akses
- **Admin Sekolah**: Kelola tahun ajaran, kelas, jurusan, guru, siswa, jadwal, pengumuman
- **Guru/Instruktur**: Buat kursus, upload materi, buat tugas/kuis, nilai, rekap kehadiran, forum diskusi
- **Siswa/Peserta Didik**: Akses materi, kerjakan tugas/kuis, lihat nilai & progress, unggah bukti PKL, chat guru
- **Wali Kelas**: Pantau kehadiran, rekap nilai kelas, komunikasi dengan ortu, input catatan perilaku
- **Orang Tua/Wali Murid**: Lihat laporan anak (nilai, absen, tugas), terima notifikasi, hubungi wali kelas/guru
- **Kepala Sekolah/Manajemen**: Dashboard analitik, laporan akreditasi, monitoring kompetensi & kelulusan
- **Mentor Industri/Pembimbing PKL**: Input penilaian praktik, verifikasi logbook PKL, beri sertifikat/feedback industri

### Modul Fungsional
- ✅ Autentikasi & RBAC (Role-Based Access Control)
- ✅ Manajemen Kelas & Kompetensi Keahlian
- ✅ Materi Pembelajaran (PDF, PPT, Video, Link)
- ✅ Penugasan & Penilaian dengan Rubrik
- ✅ Kuis & Ujian Online dengan Auto-grading
- ✅ Absensi Digital dengan QR Code
- ✅ Jadwal & Kalender Akademik
- ✅ Forum Diskusi & Pesan Internal
- ✅ Laporan & Dashboard Analitik
- ✅ Manajemen PKL/Prakerin dengan Logbook
- ✅ Uji Kompetensi (UKK) & Sertifikat Digital
- ✅ Notifikasi & Pengumuman

## 🎨 Desain

- **Tema**: Claymorphism (efek 3D soft dengan bayangan lembut)
- **Mode**: Light Mode & Dark Mode Support
- **Responsif**: Mobile-friendly dengan sidebar collapsible

## 🗄️ Database

- **MySQL Database**: `lms-smkk-sdm`
- **Username**: `root`
- **Password**: `root`

## 📁 Struktur Folder

```
/workspace
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet (Claymorphism)
│   ├── js/
│   │   └── app.js             # JavaScript utilities
│   └── images/
├── config/
│   └── database.php           # Database configuration
├── controllers/               # Application controllers
├── models/
│   ├── Database.php           # Database connection class
│   └── User.php               # User model
├── middleware/
│   └── Auth.php               # Authentication middleware
├── views/                     # View templates
├── layouts/
│   └── main.php               # Main layout template
├── auth/
│   ├── login.php              # Login page
│   └── logout.php             # Logout handler
├── routes/                    # Route definitions
├── utils/                     # Utility functions
├── uploads/                   # File uploads
│   ├── materi/
│   ├── tugas/
│   ├── logbook/
│   └── sertifikat/
├── database/
│   └── schema.sql             # Database schema
├── dashboard.php              # Main dashboard
└── index.php                  # Entry point
```

## 🚀 Instalasi

### 1. Clone Repository
```bash
cd /workspace
```

### 2. Import Database
```bash
mysql -u root -p < database/schema.sql
```
Masukkan password: `root`

### 3. Konfigurasi Database
Edit file `config/database.php` jika diperlukan:
```php
'database' => [
    'host' => 'localhost',
    'database' => 'lms-smkk-sdm',
    'username' => 'root',
    'password' => 'root',
]
```

### 4. Setup Web Server

#### Apache Configuration
Pastikan mod_rewrite enabled:
```apache
<VirtualHost *:80>
    DocumentRoot /workspace
    <Directory /workspace>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name localhost;
    root /workspace;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 5. Set Permissions
```bash
chmod -R 755 /workspace
chmod -R 777 /workspace/uploads
```

### 6. Akses Aplikasi
Buka browser dan akses:
```
http://localhost/auth/login.php
```

## 🔑 Default Login

| Role | Username | Password |
|------|----------|----------|
| Super Admin | `superadmin` | `admin123` |

## 🛠️ Teknologi

- **Backend**: PHP 8.x
- **Database**: MySQL 8.x
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Design Pattern**: MVC (Model-View-Controller)
- **Authentication**: Session-based dengan RBAC

## 📱 Fitur Unggulan

### 1. Claymorphism Design
Desain modern dengan efek 3D yang lembut, memberikan pengalaman pengguna yang menarik dan nyaman.

### 2. Dark Mode Support
Toggle tema untuk beralih antara light mode dan dark mode sesuai preferensi pengguna.

### 3. Role-Based Dashboard
Setiap role memiliki dashboard dan menu yang disesuaikan dengan kebutuhan dan tanggung jawabnya.

### 4. Manajemen PKL Terintegrasi
Fitur khusus untuk SMK dengan manajemen PKL lengkap termasuk logbook harian dan penilaian mentor industri.

### 5. Sertifikat Digital
Generate sertifikat digital dengan QR code verification untuk UKK dan PKL.

## 📝 Catatan

- Pastikan PHP version 8.0 atau lebih tinggi
- Extension PDO MySQL harus aktif
- Untuk production, ganti password default dan aktifkan HTTPS

## 📄 License

Proprietary - SMK Kesehatan SDM Sumedang

---

**© 2024 LMS SMK Kesehatan SDM Sumedang**
