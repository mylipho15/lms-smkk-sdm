<?php
/**
 * Helper Functions - Format Helpers
 * 
 * Fungsi pembantu untuk format tanggal, rupiah, dll di LMS SMK Kesehatan SDM Sumedang
 */

/**
 * Format number to Indonesian Rupiah
 * 
 * @param float|int $number Number to format
 * @param bool $includeSymbol Include "Rp" symbol
 * @return string
 */
function format_rupiah($number, $includeSymbol = true) {
    $formatted = number_format($number, 0, ',', '.');
    
    if ($includeSymbol) {
        return 'Rp ' . $formatted;
    }
    
    return $formatted;
}

/**
 * Format date to Indonesian format (DD Month YYYY)
 * 
 * @param string $date Date string (YYYY-MM-DD or timestamp)
 * @param bool $includeTime Include time
 * @return string
 */
function format_date_indonesian($date, $includeTime = false) {
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    
    $day = date('d', $timestamp);
    $month = $months[(int)date('n', $timestamp)];
    $year = date('Y', $timestamp);
    
    $formatted = "{$day} {$month} {$year}";
    
    if ($includeTime) {
        $formatted .= ' ' . date('H:i', $timestamp);
    }
    
    return $formatted;
}

/**
 * Format datetime to Indonesian format with time
 * 
 * @param string $datetime Datetime string
 * @return string
 */
function format_datetime_indonesian($datetime) {
    return format_date_indonesian($datetime, true);
}

/**
 * Get relative time (time ago)
 * 
 * @param string $datetime Datetime string
 * @return string
 */
function time_ago($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return $diff . ' detik yang lalu';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' menit yang lalu';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' jam yang lalu';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' hari yang lalu';
    } elseif ($diff < 2592000) {
        $weeks = floor($diff / 604800);
        return $weeks . ' minggu yang lalu';
    } elseif ($diff < 31536000) {
        $months = floor($diff / 2592000);
        return $months . ' bulan yang lalu';
    } else {
        $years = floor($diff / 31536000);
        return $years . ' tahun yang lalu';
    }
}

/**
 * Format date for input field (YYYY-MM-DD)
 * 
 * @param string $date Date string
 * @return string
 */
function format_date_input($date) {
    if (empty($date)) {
        return '';
    }
    return date('Y-m-d', strtotime($date));
}

/**
 * Format date for display (DD/MM/YYYY)
 * 
 * @param string $date Date string
 * @return string
 */
function format_date_display($date) {
    if (empty($date)) {
        return '-';
    }
    return date('d/m/Y', strtotime($date));
}

/**
 * Get day name in Indonesian
 * 
 * @param string $date Date string
 * @return string
 */
function get_day_name($date) {
    $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    
    $dayName = date('l', strtotime($date));
    return $days[$dayName];
}

/**
 * Truncate text with ellipsis
 * 
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @return string
 */
function truncate($text, $length = 50) {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . '...';
}

/**
 * Format file size to human readable
 * 
 * @param int $bytes File size in bytes
 * @return string
 */
function format_file_size($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Calculate age from birth date
 * 
 * @param string $birthDate Birth date (YYYY-MM-DD)
 * @return int
 */
function calculate_age($birthDate) {
    $birth = new DateTime($birthDate);
    $today = new DateTime('today');
    return $birth->diff($today)->y;
}

/**
 * Generate random string
 * 
 * @param int $length String length
 * @return string
 */
function generate_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

/**
 * Generate unique code/ID
 * 
 * @param string $prefix Prefix for the code
 * @param int $length Length of random part
 * @return string
 */
function generate_unique_code($prefix = '', $length = 8) {
    return $prefix . strtoupper(generate_random_string($length));
}
