<?php
/**
 * ApiController - API Controller untuk AJAX requests
 */

namespace App\Controllers;

use App\Core\Controller;

class ApiController extends Controller {
    
    public function notificationCount() {
        // Return JSON response
        $this->json(['count' => 0]);
    }
    
    public function notificationList() {
        // Return JSON response
        $this->json(['notifications' => []]);
    }
    
    public function uploadImage() {
        // Handle image upload
        $this->json(['success' => false, 'message' => 'Upload not implemented']);
    }
    
    public function uploadFile() {
        // Handle file upload
        $this->json(['success' => false, 'message' => 'Upload not implemented']);
    }
    
    public function search() {
        // Handle search
        $query = $this->request->get('q');
        $this->json(['results' => [], 'query' => $query]);
    }
}
