<?php
/**
 * HomeController - Halaman Depan Publik
 */

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    
    public function index() {
        $data = [
            'title' => 'Beranda - LMS SMKK SDM',
            'page' => 'home'
        ];
        
        // Render content view first
        ob_start();
        $this->view('home/index', $data);
        $content = ob_get_clean();
        
        // Then render with layout
        $this->renderLayout('main', $content, $data);
    }
}
