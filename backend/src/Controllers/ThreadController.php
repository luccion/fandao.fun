<?php
/**
 * ThreadController - Handles thread viewing, editing, and deletion
 */
class ThreadController
{
    public function view()
    {
        // Initialize message board operations
        $msg = new msgModel();

        // Get thread ID
        empty($_GET['id']) && showMsg('参数错误!', true, './');
        $tid = (int)$_GET['id'];

        // Get thread data
        ($tmp = $msg->getThread($tid)) || showMsg('参数错误!', true, './');
        $threads = array();
        $threads[] = $tmp;
        $cat_name_array = catModel::getAllInfo();

        // Calculate pagination for replies
        $count = $msg->countReplies($tid);
        $all = ceil($count / 50);
        $curr_page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
        $curr_page < 1 && $curr_page = 1;
        $curr_page > $all && $curr_page = $all;
        $start = ($curr_page - 1) * 50;

        // Get replies
        $replies = $msg->getReplies($tid, $start, 50);

        // Set template variables
        $data = [
            'msg' => $msg,
            'tid' => $tid,
            'threads' => $threads,
            'cat_name_array' => $cat_name_array,
            'count' => $count,
            'all' => $all,
            'curr_page' => $curr_page,
            'replies' => $replies
        ];

        // Extract variables for the view
        extract($data);

        // Load the view
        require(ROOT . "view/view.php");
    }

    public function edit()
    {
        // Implementation for edit functionality
        // This would contain the logic from edit.php
        require(ROOT . "edit.php");
    }

    public function delete()
    {
        // Implementation for delete functionality
        // This would contain the logic from delete.php
        require(ROOT . "delete.php");
    }
}