<?php
/**
 * WaliKelasController - Wali Kelas Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class WaliKelasController extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->isAuthenticated() || !in_array($this->session->get('role'), ['guru', 'admin'])) {
            $this->redirect('/login');
        }
    }
    
    public function dashboard() {
        $data = ['title' => 'Dashboard Wali Kelas - LMS SMKK SDM', 'page' => 'wali-kelas-dashboard'];
        ob_start(); $this->view('wali-kelas/dashboard', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function absensiKelas() {
        $data = ['title' => 'Absensi Kelas - LMS SMKK SDM', 'page' => 'wali-kelas-absensi'];
        ob_start(); $this->view('wali-kelas/absensi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function nilaiKelas() {
        $data = ['title' => 'Nilai Kelas - LMS SMKK SDM', 'page' => 'wali-kelas-nilai'];
        ob_start(); $this->view('wali-kelas/nilai', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function inputCatatanPerilaku() { $this->redirect('/wali-kelas'); }
    
    public function daftarOrangTua() {
        $data = ['title' => 'Daftar Orang Tua - LMS SMKK SDM', 'page' => 'wali-kelas-orang-tua'];
        ob_start(); $this->view('wali-kelas/orang-tua', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function kirimPesanOrangTua() { $this->redirect('/wali-kelas/orang-tua'); }
    
    public function generateLaporan() {
        $data = ['title' => 'Laporan - LMS SMKK SDM', 'page' => 'wali-kelas-laporan'];
        ob_start(); $this->view('wali-kelas/laporan', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
}
