<?php
/**
 * UkkController - UKK (Uji Kompetensi Keahlian) Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class UkkController extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->isAuthenticated() || !in_array($this->session->get('role'), ['guru', 'admin'])) {
            $this->redirect('/login');
        }
    }
    
    public function dashboard() {
        $data = ['title' => 'Dashboard UKK - LMS SMKK SDM', 'page' => 'ukk-dashboard'];
        ob_start(); $this->view('ukk/dashboard', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function jadwal() {
        $data = ['title' => 'Jadwal UKK - LMS SMKK SDM', 'page' => 'ukk-jadwal'];
        ob_start(); $this->view('ukk/jadwal', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function createJadwal() { $this->redirect('/ukk/jadwal'); }
    
    public function bankSoal() {
        $data = ['title' => 'Bank Soal UKK - LMS SMKK SDM', 'page' => 'ukk-soal'];
        ob_start(); $this->view('ukk/soal', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function createSoal() { $this->redirect('/ukk/soal'); }
    
    public function ujian() {
        $data = ['title' => 'Ujian UKK - LMS SMKK SDM', 'page' => 'ukk-ujian'];
        ob_start(); $this->view('ukk/ujian', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function inputNilaiUjian() { $this->redirect('/ukk/ujian'); }
    
    public function rekapNilai() {
        $data = ['title' => 'Rekap Nilai UKK - LMS SMKK SDM', 'page' => 'ukk-nilai'];
        ob_start(); $this->view('ukk/nilai', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function generateSertifikat() { $this->redirect('/ukk/sertifikat'); }
    
    public function daftarSertifikat() {
        $data = ['title' => 'Daftar Sertifikat UKK - LMS SMKK SDM', 'page' => 'ukk-sertifikat'];
        ob_start(); $this->view('ukk/sertifikat', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
}
