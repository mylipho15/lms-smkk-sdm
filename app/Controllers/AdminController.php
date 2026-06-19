<?php
/**
 * AdminController - Admin Sekolah Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class AdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->isAuthenticated() || !in_array($this->session->get('role'), ['admin', 'super_admin'])) {
            $this->redirect('/login');
        }
    }
    
    public function dashboard() {
        $data = ['title' => 'Dashboard Admin - LMS SMKK SDM', 'page' => 'admin-dashboard'];
        ob_start();
        $this->view('admin/dashboard', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function tahunAjaran() {
        $data = ['title' => 'Tahun Ajaran - LMS SMKK SDM', 'page' => 'admin-tahun-ajaran'];
        ob_start();
        $this->view('admin/tahun-ajaran', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function createTahunAjaran() { $this->redirect('/admin/tahun-ajaran'); }
    public function updateTahunAjaran() { $this->redirect('/admin/tahun-ajaran'); }
    public function deleteTahunAjaran() { $this->redirect('/admin/tahun-ajaran'); }
    
    public function jurusan() {
        $data = ['title' => 'Jurusan - LMS SMKK SDM', 'page' => 'admin-jurusan'];
        ob_start();
        $this->view('admin/jurusan', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function createJurusan() { $this->redirect('/admin/jurusan'); }
    public function updateJurusan() { $this->redirect('/admin/jurusan'); }
    public function deleteJurusan() { $this->redirect('/admin/jurusan'); }
    
    public function kelas() {
        $data = ['title' => 'Kelas - LMS SMKK SDM', 'page' => 'admin-kelas'];
        ob_start();
        $this->view('admin/kelas', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function createKelas() { $this->redirect('/admin/kelas'); }
    public function updateKelas() { $this->redirect('/admin/kelas'); }
    public function deleteKelas() { $this->redirect('/admin/kelas'); }
    
    public function guru() {
        $data = ['title' => 'Data Guru - LMS SMKK SDM', 'page' => 'admin-guru'];
        ob_start();
        $this->view('admin/guru', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function createGuru() { $this->redirect('/admin/guru'); }
    public function updateGuru() { $this->redirect('/admin/guru'); }
    public function deleteGuru() { $this->redirect('/admin/guru'); }
    
    public function siswa() {
        $data = ['title' => 'Data Siswa - LMS SMKK SDM', 'page' => 'admin-siswa'];
        ob_start();
        $this->view('admin/siswa', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function createSiswa() { $this->redirect('/admin/siswa'); }
    public function updateSiswa() { $this->redirect('/admin/siswa'); }
    public function deleteSiswa() { $this->redirect('/admin/siswa'); }
    
    public function jadwal() {
        $data = ['title' => 'Jadwal Pelajaran - LMS SMKK SDM', 'page' => 'admin-jadwal'];
        ob_start();
        $this->view('admin/jadwal', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function createJadwal() { $this->redirect('/admin/jadwal'); }
    public function updateJadwal() { $this->redirect('/admin/jadwal'); }
    public function deleteJadwal() { $this->redirect('/admin/jadwal'); }
    
    public function pengumuman() {
        $data = ['title' => 'Pengumuman - LMS SMKK SDM', 'page' => 'admin-pengumuman'];
        ob_start();
        $this->view('admin/pengumuman', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function createPengumuman() { $this->redirect('/admin/pengumuman'); }
    public function updatePengumuman() { $this->redirect('/admin/pengumuman'); }
    public function deletePengumuman() { $this->redirect('/admin/pengumuman'); }
    
    public function waliKelas() {
        $data = ['title' => 'Wali Kelas - LMS SMKK SDM', 'page' => 'admin-wali-kelas'];
        ob_start();
        $this->view('admin/wali-kelas', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function assignWaliKelas() { $this->redirect('/admin/wali-kelas'); }
}
