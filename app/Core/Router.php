<?php
/**
 * Router.php - Custom Router
 * 
 * Mapping URL ke Controller dan Method untuk LMS SMK Kesehatan SDM Sumedang
 */

namespace App\Core;

class Router {
    protected $routes = [];
    
    public function __construct() {
        // Load routes from config
        $this->routes = require BASE_PATH . '/config/routes.php';
    }
    
    /**
     * Dispatch request to appropriate controller
     * 
     * @return array|null Route details or null if not found
     */
    public function dispatch() {
        // Get request URI and method
        $uri = $this->getUri();
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Remove query string from URI
        $uri = strtok($uri, '?');
        
        // Normalize URI
        $uri = rtrim($uri, '/') ?: '/';
        
        // Check for exact match first
        $routeKey = "{$method} {$uri}";
        
        if (isset($this->routes[$routeKey])) {
            return $this->parseRoute($this->routes[$routeKey]);
        }
        
        // Check for parameterized routes
        foreach ($this->routes as $key => $value) {
            list($routeMethod, $routePath) = explode(' ', $key, 2);
            
            if ($routeMethod !== $method) {
                continue;
            }
            
            // Convert route path to regex pattern
            $pattern = $this->convertToRegex($routePath);
            
            if (preg_match($pattern, $uri, $matches)) {
                // Remove full match from matches array
                array_shift($matches);
                
                $route = $this->parseRoute($value);
                $route['params'] = $matches;
                
                return $route;
            }
        }
        
        // No route found
        return null;
    }
    
    /**
     * Get current URI
     * 
     * @return string
     */
    protected function getUri() {
        if (isset($_GET['url'])) {
            return filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
        }
        
        return '/';
    }
    
    /**
     * Parse route string to controller, method, and params
     * 
     * @param string $route Route string (Controller@Method)
     * @return array
     */
    protected function parseRoute($route) {
        list($controller, $method) = explode('@', $route);
        
        return [
            'controller' => trim($controller),
            'method' => trim($method),
            'params' => []
        ];
    }
    
    /**
     * Convert route path with parameters to regex pattern
     * 
     * @param string $path Route path
     * @return string Regex pattern
     */
    protected function convertToRegex($path) {
        // Replace {param} with regex capture group
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $path);
        
        // Escape special characters except our capture groups
        $pattern = str_replace('/', '\/', $pattern);
        
        return '#^' . $pattern . '$#';
    }
    
    /**
     * Register a GET route
     * 
     * @param string $path Route path
     * @param string $callback Controller@Method
     * @return void
     */
    public function get($path, $callback) {
        $this->routes["GET {$path}"] = $callback;
    }
    
    /**
     * Register a POST route
     * 
     * @param string $path Route path
     * @param string $callback Controller@Method
     * @return void
     */
    public function post($path, $callback) {
        $this->routes["POST {$path}"] = $callback;
    }
    
    /**
     * Register a PUT route
     * 
     * @param string $path Route path
     * @param string $callback Controller@Method
     * @return void
     */
    public function put($path, $callback) {
        $this->routes["PUT {$path}"] = $callback;
    }
    
    /**
     * Register a DELETE route
     * 
     * @param string $path Route path
     * @param string $callback Controller@Method
     * @return void
     */
    public function delete($path, $callback) {
        $this->routes["DELETE {$path}"] = $callback;
    }
}
