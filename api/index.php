<?php
define('ACC', true);
require('../init.php');

// Set JSON response headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get the request path
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

// Remove 'api' from path parts
if ($pathParts[0] === 'api') {
    array_shift($pathParts);
}

$endpoint = $pathParts[0] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($endpoint) {
        case 'discussions':
            require('endpoints/discussions.php');
            break;
        case 'categories':
            require('endpoints/categories.php');
            break;
        case 'user':
            require('endpoints/user.php');
            break;
        case 'health':
            echo json_encode([
                'status' => 'ok',
                'timestamp' => time(),
                'version' => SITEVERSION ?? '0.8.0'
            ]);
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error',
        'message' => APP_DEBUG ? $e->getMessage() : 'Something went wrong'
    ]);
}
?>