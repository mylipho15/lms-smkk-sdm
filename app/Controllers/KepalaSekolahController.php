<?php
/**
 * KepalaSekolahController - Kepala Sekolah Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class KepalaSekolahController extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->isAuthenticated() || $this->session->get('role') !== 'kepala_sekolah') {
            $this->redirect('/login');
        }
    }
    
    public function dashboard() {
        $data = ['title' => 'Dashboard Kepala Sekolah - LMS SMKK SDM', 'page' => 'kepala-sekolah-dashboard'];
        ob_start(); $this->view('kepala-sekolah/dashboard', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function analytics() {
        $data = ['title' => 'Analytics - LMS SMKK SDM', 'page' => 'kepala-sekolah-analytics'];
        ob_start(); $this->view('kepala-sekolah/analytics', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function laporan() {
        $data = ['title' => 'Laporan - LMS SMKK SDM', 'page' => 'kepala-sekolah-laporan'];
        ob_start(); $this->view('kepala-sekolah/laporan', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function exportLaporan() { $this->redirect('/kepala-sekolah/laporan'); }
    
    public function akreditasi() {
        $data = ['title' => 'Akreditasi - LMS SMKK SDM', 'page' => 'kepala-sekolah-akreditasi'];
        ob_start(); $this->view('kepala-sekolah/akreditasi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function monitoringKompetensi() {
        $data = ['title' => 'Monitoring Kompetensi - LMS SMKK SDM', 'page' => 'kepala-sekolah-kompetensi'];
        ob_start(); $this->view('kepala-sekolah/kompetensi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function statistikKelulusan() {
        $data = ['title' => 'Statistik Kelulusan - LMS SMKK SDM', 'page' => 'kepala-sekolah-kelulusan'];
        ob_start(); $this->view('kepala-sekolah/kelulusan', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
}
