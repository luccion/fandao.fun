<?php
// API endpoint for user information

if ($method === 'GET') {
    // Auto-login check
    $UM = new userModel();
    $UM->autoLogin();
    
    $isLoggedIn = userModel::isLogin();
    
    if ($isLoggedIn) {
        $userInfo = [
            'isLoggedIn' => true,
            'username' => $_SESSION['name'] ?? '',
            'type' => $_SESSION['type'] ?? 0,
            'avatar' => userModel::getCurrentAvatarSVG($_SESSION['name'] ?? ''),
        ];
    } else {
        $userInfo = [
            'isLoggedIn' => false,
            'username' => '',
            'type' => 0,
            'avatar' => '',
        ];
    }
    
    echo json_encode($userInfo);
    
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>