<?php
/**
 * SiswaController - Siswa Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class SiswaController extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->isAuthenticated() || !in_array($this->session->get('role'), ['siswa', 'admin', 'super_admin'])) {
            $this->redirect('/login');
        }
    }
    
    public function dashboard() {
        $data = ['title' => 'Dashboard Siswa - LMS SMKK SDM', 'page' => 'siswa-dashboard'];
        ob_start(); $this->view('siswa/dashboard', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function kursus() {
        $data = ['title' => 'Mata Pelajaran - LMS SMKK SDM', 'page' => 'siswa-kursus'];
        ob_start(); $this->view('siswa/kursus', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function detailKursus() {
        $data = ['title' => 'Detail Mata Pelajaran - LMS SMKK SDM', 'page' => 'siswa-detail-kursus'];
        ob_start(); $this->view('siswa/detail-kursus', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function materi() {
        $data = ['title' => 'Materi Pembelajaran - LMS SMKK SDM', 'page' => 'siswa-materi'];
        ob_start(); $this->view('siswa/materi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function tugas() {
        $data = ['title' => 'Tugas - LMS SMKK SDM', 'page' => 'siswa-tugas'];
        ob_start(); $this->view('siswa/tugas', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function submitTugas() { $this->redirect('/siswa/tugas'); }
    public function detailTugas() {
        $data = ['title' => 'Detail Tugas - LMS SMKK SDM', 'page' => 'siswa-detail-tugas'];
        ob_start(); $this->view('siswa/detail-tugas', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function kuis() {
        $data = ['title' => 'Kuis - LMS SMKK SDM', 'page' => 'siswa-kuis'];
        ob_start(); $this->view('siswa/kuis', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function kerjakanKuis() {
        $data = ['title' => 'Kerjakan Kuis - LMS SMKK SDM', 'page' => 'siswa-kerjakan-kuis'];
        ob_start(); $this->view('siswa/kerjakan-kuis', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function submitKuis() { $this->redirect('/siswa/kuis'); }
    
    public function nilai() {
        $data = ['title' => 'Nilai Saya - LMS SMKK SDM', 'page' => 'siswa-nilai'];
        ob_start(); $this->view('siswa/nilai', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function absensi() {
        $data = ['title' => 'Absensi Saya - LMS SMKK SDM', 'page' => 'siswa-absensi'];
        ob_start(); $this->view('siswa/absensi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function jadwal() {
        $data = ['title' => 'Jadwal Pelajaran - LMS SMKK SDM', 'page' => 'siswa-jadwal'];
        ob_start(); $this->view('siswa/jadwal', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function forum() {
        $data = ['title' => 'Forum Diskusi - LMS SMKK SDM', 'page' => 'siswa-forum'];
        ob_start(); $this->view('siswa/forum', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function postForum() { $this->redirect('/siswa/forum'); }
    public function replyForum() { $this->redirect('/siswa/forum'); }
    
    public function pesan() {
        $data = ['title' => 'Pesan - LMS SMKK SDM', 'page' => 'siswa-pesan'];
        ob_start(); $this->view('siswa/pesan', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function kirimPesan() { $this->redirect('/siswa/pesan'); }
}
