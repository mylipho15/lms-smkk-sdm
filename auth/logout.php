<?php
/**
 * Logout Handler
 * LMS SMK Kesehatan SDM Sumedang
 */

require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../middleware/Auth.php';

// Destroy session and logout
auth()->logout();

// Redirect to login page
redirect('auth/login.php');
