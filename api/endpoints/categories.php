<?php
// API endpoint for categories

if ($method === 'GET') {
    // Get all categories
    $allCategories = catModel::getCatTree();
    
    $categories = array_map(function($cat) {
        return [
            'id' => $cat['cat_id'],
            'name' => $cat['cat_name'],
            'description' => $cat['intro'] ?? '',
            'postable' => $cat['postable'] == 1,
            'order' => $cat['order'] ?? 0
        ];
    }, $allCategories);
    
    echo json_encode([
        'categories' => $categories
    ]);
    
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>