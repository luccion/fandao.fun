<?php
// API endpoint for discussions/threads

if ($method === 'GET') {
    // Get query parameters
    $cat = isset($_GET['cat']) ? (int)$_GET['cat'] : 1;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? min((int)$_GET['limit'], 50) : 20;
    
    $cat < 1 && $cat = 1;
    $page < 1 && $page = 1;
    
    // Initialize message model
    $msg = new msgModel();
    
    // Get total count and calculate pagination
    $totalCount = $msg->countThreads($cat);
    $totalPages = ceil($totalCount / $limit);
    $offset = ($page - 1) * $limit;
    
    // Get threads
    $threads = $msg->getTopThreads($offset, $cat, $limit);
    
    // Get replies for each thread
    $threadsWithReplies = [];
    foreach ($threads as $thread) {
        $replies = $msg->getTopReplies($thread['tid'], 0);
        $threadsWithReplies[] = [
            'id' => $thread['tid'],
            'title' => base64_decode($thread['title'], true) ?: $thread['title'],
            'author' => $thread['name'],
            'categoryId' => $thread['cat'],
            'lastReplyTime' => $thread['lastreptime'],
            'replyCount' => count($replies),
            'content' => base64_decode($thread['content'], true) ?: $thread['content'],
            'created' => $thread['dateline'],
            'replies' => array_map(function($reply) {
                return [
                    'id' => $reply['rid'],
                    'content' => base64_decode($reply['content'], true) ?: $reply['content'],
                    'author' => $reply['name'],
                    'created' => $reply['dateline']
                ];
            }, $replies)
        ];
    }
    
    // Get category info
    $category = catModel::getInfo($cat);
    
    echo json_encode([
        'discussions' => $threadsWithReplies,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'limit' => $limit
        ],
        'category' => [
            'id' => $category['cat_id'],
            'name' => $category['cat_name'],
            'description' => $category['intro'],
            'postable' => $category['postable'] == 1
        ]
    ]);
    
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>