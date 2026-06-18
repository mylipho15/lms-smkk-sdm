<?php
/**
 * App.php - Bootstrapper Aplikasi
 * 
 * Framework mini buatan sendiri untuk LMS SMK Kesehatan SDM Sumedang
 */

namespace App\Core;

class App {
    protected $controller;
    protected $method;
    protected $params = [];
    
    public function __construct() {
        $router = new Router();
        $route = $router->dispatch();
        
        if ($route) {
            $this->controller = $route['controller'];
            $this->method = $route['method'];
            $this->params = $route['params'];
            
            // Check if controller exists
            $controllerFile = BASE_PATH . "/app/Controllers/{$this->controller}.php";
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                // Instantiate controller
                $controllerClass = "App\\Controllers\\{$this->controller}";
                if (class_exists($controllerClass)) {
                    $this->controller = new $controllerClass();
                    
                    // Check if method exists
                    if (method_exists($this->controller, $this->method)) {
                        // Call method with parameters
                        call_user_func_array([$this->controller, $this->method], $this->params);
                    } else {
                        // Method not found
                        http_response_code(404);
                        echo "Method {$this->method} not found in {$this->controller}";
                    }
                } else {
                    // Controller class not found
                    http_response_code(404);
                    echo "Controller class {$controllerClass} not found";
                }
            } else {
                // Controller file not found
                http_response_code(404);
                echo "Controller {$this->controller} not found";
            }
        } else {
            // No route matched
            http_response_code(404);
            require_once BASE_PATH . '/resources/views/errors/404.php';
        }
    }
}
