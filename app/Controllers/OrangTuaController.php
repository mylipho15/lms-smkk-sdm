<?php
/**
 * OrangTuaController - Orang Tua Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class OrangTuaController extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->isAuthenticated() || $this->session->get('role') !== 'orang_tua') {
            $this->redirect('/login');
        }
    }
    
    public function dashboard() {
        $data = ['title' => 'Dashboard Orang Tua - LMS SMKK SDM', 'page' => 'orang-tua-dashboard'];
        ob_start(); $this->view('orang-tua/dashboard', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function dataAnak() {
        $data = ['title' => 'Data Anak - LMS SMKK SDM', 'page' => 'orang-tua-anak'];
        ob_start(); $this->view('orang-tua/anak', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function lihatNilai() {
        $data = ['title' => 'Nilai Anak - LMS SMKK SDM', 'page' => 'orang-tua-nilai'];
        ob_start(); $this->view('orang-tua/nilai', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function lihatAbsensi() {
        $data = ['title' => 'Absensi Anak - LMS SMKK SDM', 'page' => 'orang-tua-absensi'];
        ob_start(); $this->view('orang-tua/absensi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function lihatTugas() {
        $data = ['title' => 'Tugas Anak - LMS SMKK SDM', 'page' => 'orang-tua-tugas'];
        ob_start(); $this->view('orang-tua/tugas', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function kirimPesan() { $this->redirect('/orang-tua'); }
    
    public function notifikasi() {
        $data = ['title' => 'Notifikasi - LMS SMKK SDM', 'page' => 'orang-tua-notifikasi'];
        ob_start(); $this->view('orang-tua/notifikasi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
}
