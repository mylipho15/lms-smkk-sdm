<?php
/**
 * GuruController - Guru Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class GuruController extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->isAuthenticated() || !in_array($this->session->get('role'), ['guru', 'admin', 'super_admin'])) {
            $this->redirect('/login');
        }
    }
    
    public function dashboard() {
        $data = ['title' => 'Dashboard Guru - LMS SMKK SDM', 'page' => 'guru-dashboard'];
        ob_start(); $this->view('guru/dashboard', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function kursus() {
        $data = ['title' => 'Mata Pelajaran - LMS SMKK SDM', 'page' => 'guru-kursus'];
        ob_start(); $this->view('guru/kursus', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function createKursus() { $this->redirect('/guru/kursus'); }
    public function updateKursus() { $this->redirect('/guru/kursus'); }
    public function detailKursus() {
        $data = ['title' => 'Detail Mata Pelajaran - LMS SMKK SDM', 'page' => 'guru-detail-kursus'];
        ob_start(); $this->view('guru/detail-kursus', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function materi() {
        $data = ['title' => 'Materi Pembelajaran - LMS SMKK SDM', 'page' => 'guru-materi'];
        ob_start(); $this->view('guru/materi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function uploadMateri() { $this->redirect('/guru/materi'); }
    public function deleteMateri() { $this->redirect('/guru/materi'); }
    
    public function tugas() {
        $data = ['title' => 'Tugas - LMS SMKK SDM', 'page' => 'guru-tugas'];
        ob_start(); $this->view('guru/tugas', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function createTugas() { $this->redirect('/guru/tugas'); }
    public function updateTugas() { $this->redirect('/guru/tugas'); }
    public function deleteTugas() { $this->redirect('/guru/tugas'); }
    public function submissionsTugas() {
        $data = ['title' => 'Pengumpulan Tugas - LMS SMKK SDM', 'page' => 'guru-submissions'];
        ob_start(); $this->view('guru/submissions', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function gradeTugas() { $this->redirect('/guru/tugas/submissions'); }
    
    public function kuis() {
        $data = ['title' => 'Kuis - LMS SMKK SDM', 'page' => 'guru-kuis'];
        ob_start(); $this->view('guru/kuis', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function createKuis() { $this->redirect('/guru/kuis'); }
    public function updateKuis() { $this->redirect('/guru/kuis'); }
    public function deleteKuis() { $this->redirect('/guru/kuis'); }
    public function questionsKuis() {
        $data = ['title' => 'Pertanyaan Kuis - LMS SMKK SDM', 'page' => 'guru-questions-kuis'];
        ob_start(); $this->view('guru/questions-kuis', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function addQuestionKuis() { $this->redirect('/guru/kuis/questions'); }
    public function resultsKuis() {
        $data = ['title' => 'Hasil Kuis - LMS SMKK SDM', 'page' => 'guru-results-kuis'];
        ob_start(); $this->view('guru/results-kuis', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function absensi() {
        $data = ['title' => 'Absensi - LMS SMKK SDM', 'page' => 'guru-absensi'];
        ob_start(); $this->view('guru/absensi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function inputAbsensi() { $this->redirect('/guru/absensi'); }
    public function rekapAbsensi() {
        $data = ['title' => 'Rekap Absensi - LMS SMKK SDM', 'page' => 'guru-rekap-absensi'];
        ob_start(); $this->view('guru/rekap-absensi', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function nilai() {
        $data = ['title' => 'Nilai - LMS SMKK SDM', 'page' => 'guru-nilai'];
        ob_start(); $this->view('guru/nilai', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function inputNilai() { $this->redirect('/guru/nilai'); }
    public function rekapNilai() {
        $data = ['title' => 'Rekap Nilai - LMS SMKK SDM', 'page' => 'guru-rekap-nilai'];
        ob_start(); $this->view('guru/rekap-nilai', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function exportNilai() { $this->redirect('/guru/nilai'); }
    
    public function forum() {
        $data = ['title' => 'Forum Diskusi - LMS SMKK SDM', 'page' => 'guru-forum'];
        ob_start(); $this->view('guru/forum', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    public function postForum() { $this->redirect('/guru/forum'); }
    public function replyForum() { $this->redirect('/guru/forum'); }
}
