<?php
/**
 * Modern entry point for fandao.fun backend
 * This file routes requests to appropriate controllers
 */

define('ACC', true);
define('BACKEND_ROOT', dirname(__DIR__) . '/');
define('ROOT', dirname(BACKEND_ROOT) . '/');

// Include the original initialization
require_once ROOT . 'init.php';

// Include the router
require_once BACKEND_ROOT . 'src/Config/Router.php';

// Get the request path
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Remove base path if needed
$basePath = '/backend/public';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Initialize router and dispatch
$router = new Router();

if (!$router->dispatch($path)) {
    // Fallback to original file structure for gradual migration
    $originalFile = ROOT . ltrim($path, '/') . '.php';
    if (file_exists($originalFile)) {
        require_once $originalFile;
    } else {
        http_response_code(404);
        echo "Page not found";
    }
}