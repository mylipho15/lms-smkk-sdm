<?php
/**
 * PklController - PKL (Praktik Kerja Lapangan) Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class PklController extends Controller {
    
    public function dashboardSiswa() {
        $data = ['title' => 'Dashboard PKL - LMS SMKK SDM', 'page' => 'pkl-dashboard'];
        ob_start(); $this->view('pkl/dashboard', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function inputLogbook() { $this->redirect('/siswa/pkl'); }
    public function lihatLogbook() {
        $data = ['title' => 'Logbook PKL - LMS SMKK SDM', 'page' => 'pkl-logbook'];
        ob_start(); $this->view('pkl/logbook', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function uploadLaporan() { $this->redirect('/siswa/pkl'); }
    public function lihatNilai() {
        $data = ['title' => 'Nilai PKL - LMS SMKK SDM', 'page' => 'pkl-nilai'];
        ob_start(); $this->view('pkl/nilai', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
}
