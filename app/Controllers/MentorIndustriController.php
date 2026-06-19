<?php
/**
 * MentorIndustriController - Mentor Industri Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class MentorIndustriController extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->isAuthenticated() || $this->session->get('role') !== 'mentor_industri') {
            $this->redirect('/login');
        }
    }
    
    public function dashboard() {
        $data = ['title' => 'Dashboard Mentor Industri - LMS SMKK SDM', 'page' => 'mentor-industri-dashboard'];
        ob_start(); $this->view('mentor-industri/dashboard', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function pesertaPKL() {
        $data = ['title' => 'Peserta PKL - LMS SMKK SDM', 'page' => 'mentor-industri-peserta-pkl'];
        ob_start(); $this->view('mentor-industri/peserta-pkl', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function inputPenilaian() { $this->redirect('/mentor-industri/peserta-pkl'); }
    
    public function verifikasiLogbook() {
        $data = ['title' => 'Verifikasi Logbook - LMS SMKK SDM', 'page' => 'mentor-industri-logbook'];
        ob_start(); $this->view('mentor-industri/logbook', $data); $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function verifyLogbook() { $this->redirect('/mentor-industri/logbook'); }
    public function beriFeedback() { $this->redirect('/mentor-industri/peserta-pkl'); }
    public function usulSertifikat() { $this->redirect('/mentor-industri/peserta-pkl'); }
}
