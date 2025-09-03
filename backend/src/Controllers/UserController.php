<?php
/**
 * UserController - Handles user profile and settings
 */
class UserController
{
    public function profile()
    {
        // Check authentication
        userModel::isLogin() || showMsg('请先登陆!', true, './index.php');

        $TM = new transferModel();
        $money = $TM->calculate_amount($_SESSION['wallet']);
        $property = $TM->get_all_of($_SESSION['uid']);

        $cat = empty($_GET['cat']) ? 1 : (int)$_GET['cat'];

        switch ($cat) {
            // User's discussions
            case 1:
                $msg = new msgModel();
                $all = ceil($msg->countUserThreads($_SESSION['uid']) / 10);
                $curr_page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
                $curr_page < 1 && $curr_page = 1;
                $curr_page > $all && $curr_page = $all;
                $start = ($curr_page - 1) * 10;
                $discussions = $msg->getUserThreads($_SESSION['uid'], $start, 10);
                break;
                
            // User's replies
            case 2:
                $msg = new msgModel();
                $all = ceil($msg->countUserReplies($_SESSION['uid']) / 10);
                $curr_page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
                $curr_page < 1 && $curr_page = 1;
                $curr_page > $all && $curr_page = $all;
                $start = ($curr_page - 1) * 10;
                $replies = $msg->getUserReplies($_SESSION['uid'], $start, 10);
                break;
                
            default:
                $discussions = [];
                $replies = [];
                break;
        }

        // Set template variables
        $data = [
            'TM' => $TM,
            'money' => $money,
            'property' => $property,
            'cat' => $cat,
            'all' => $all ?? 0,
            'curr_page' => $curr_page ?? 1,
            'discussions' => $discussions ?? [],
            'replies' => $replies ?? []
        ];

        // Extract variables for the view
        extract($data);

        // Load the view
        require(ROOT . "view/me.php");
    }
}