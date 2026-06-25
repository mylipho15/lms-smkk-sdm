<?php
/**
 * Database Configuration
 * LMS SMK Kesehatan SDM Sumedang
 */

return [
    'database' => [
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'lms-smkk-sdm',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    
    'app' => [
        'name' => 'LMS SMK Kesehatan SDM Sumedang',
        'url' => 'http://localhost/lms-smk',
        'timezone' => 'Asia/Jakarta',
        'locale' => 'id_ID',
    ],
    
    'session' => [
        'lifetime' => 7200, // 2 hours
        'name' => 'lms_smk_session',
        'secure' => false,
        'httponly' => true,
    ],
    
    'upload' => [
        'max_size' => 10485760, // 10MB
        'allowed_types' => ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'mp4', 'avi'],
        'paths' => [
            'materi' => 'uploads/materi/',
            'tugas' => 'uploads/tugas/',
            'logbook' => 'uploads/logbook/',
            'sertifikat' => 'uploads/sertifikat/',
        ]
    ],
    
    'pagination' => [
        'per_page' => 10,
    ],
];
