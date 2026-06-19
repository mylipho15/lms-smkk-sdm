<?php
/**
 * ErrorController - Error Pages Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller {
    
    public function notFound() {
        http_response_code(404);
        $data = ['title' => '404 - Halaman Tidak Ditemukan', 'page' => '404'];
        ob_start();
        $this->view('errors/404', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function forbidden() {
        http_response_code(403);
        $data = ['title' => '403 - Akses Ditolak', 'page' => '403'];
        ob_start();
        $this->view('errors/403', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
    
    public function serverError() {
        http_response_code(500);
        $data = ['title' => '500 - Kesalahan Server', 'page' => '500'];
        ob_start();
        $this->view('errors/500', $data);
        $content = ob_get_clean();
        $this->renderLayout('main', $content, $data);
    }
}
