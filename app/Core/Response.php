<?php
/**
 * Response.php - Menangani Output Response
 * 
 * Menangani redirect, JSON response untuk LMS SMK Kesehatan SDM Sumedang
 */

namespace App\Core;

class Response {
    /**
     * Redirect to URL
     * 
     * @param string $url Destination URL
     * @param int $statusCode HTTP status code
     * @return void
     */
    public function redirect($url, $statusCode = 302) {
        header("Location: {$url}", true, $statusCode);
        exit;
    }
    
    /**
     * Return JSON response
     * 
     * @param mixed $data Data to encode
     * @param int $statusCode HTTP status code
     * @param array $headers Additional headers
     * @return void
     */
    public function json($data, $statusCode = 200, $headers = []) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        
        foreach ($headers as $name => $value) {
            header("{$name}: {$value}");
        }
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Return HTML response
     * 
     * @param string $content HTML content
     * @param int $statusCode HTTP status code
     * @return void
     */
    public function html($content, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: text/html; charset=utf-8');
        echo $content;
        exit;
    }
    
    /**
     * Return file download
     * 
     * @param string $filePath Path to file
     * @param string $filename Download filename
     * @return void
     */
    public function download($filePath, $filename) {
        if (!file_exists($filePath)) {
            http_response_code(404);
            echo "File not found";
            exit;
        }
        
        http_response_code(200);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
        exit;
    }
    
    /**
     * Set HTTP status code
     * 
     * @param int $statusCode HTTP status code
     * @return void
     */
    public function setStatusCode($statusCode) {
        http_response_code($statusCode);
    }
    
    /**
     * Set header
     * 
     * @param string $name Header name
     * @param string $value Header value
     * @return void
     */
    public function header($name, $value) {
        header("{$name}: {$value}");
    }
    
    /**
     * Send no-cache headers
     * 
     * @return void
     */
    public function noCache() {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Expires: 0');
    }
    
    /**
     * Send CORS headers
     * 
     * @param string $origin Allowed origin
     * @return void
     */
    public function cors($origin = '*') {
        header("Access-Control-Allow-Origin: {$origin}");
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');
    }
}
