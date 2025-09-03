<?php
/**
 * Router configuration for fandao.fun
 */

class Router
{
    private $routes = [];
    
    public function __construct()
    {
        $this->setupRoutes();
    }
    
    private function setupRoutes()
    {
        // Main application routes
        $this->routes = [
            '/' => ['controller' => 'HomeController', 'action' => 'index'],
            '/index' => ['controller' => 'HomeController', 'action' => 'index'],
            '/article' => ['controller' => 'ArticleController', 'action' => 'show'],
            '/me' => ['controller' => 'UserController', 'action' => 'profile'],
            '/edit' => ['controller' => 'ThreadController', 'action' => 'edit'],
            '/delete' => ['controller' => 'ThreadController', 'action' => 'delete'],
            '/view' => ['controller' => 'ThreadController', 'action' => 'view'],
            '/hall' => ['controller' => 'HallController', 'action' => 'index'],
            '/market' => ['controller' => 'MarketController', 'action' => 'index'],
            '/lottery' => ['controller' => 'LotteryController', 'action' => 'index'],
            '/exchange' => ['controller' => 'ExchangeController', 'action' => 'index'],
            '/privacy' => ['controller' => 'PageController', 'action' => 'privacy'],
            '/terms' => ['controller' => 'PageController', 'action' => 'terms'],
        ];
    }
    
    public function route($path)
    {
        // Normalize path
        $path = '/' . trim($path, '/');
        if ($path === '/') {
            $path = '/';
        }
        
        if (isset($this->routes[$path])) {
            return $this->routes[$path];
        }
        
        return null;
    }
    
    public function dispatch($path)
    {
        $route = $this->route($path);
        
        if ($route) {
            $controllerFile = BACKEND_ROOT . 'src/Controllers/' . $route['controller'] . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controller = new $route['controller']();
                $action = $route['action'];
                
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return true;
                }
            }
        }
        
        return false;
    }
}