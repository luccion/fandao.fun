<?php
/**
 * HomeController - Handles the main discussion listing page
 */
class HomeController
{
    public function index()
    {
        $xportal = $_GET['x'] ?? null;

        // Initialize message board operations
        $msg = new msgModel();
        
        // Get all categories
        $allCats = catModel::getCatTree();
        
        // Get current category
        $curr_cat_id = empty($_GET['cat']) ? 1 : (int)$_GET['cat'];
        $curr_cat_id < 1 && $curr_cat_id = 1;
        $curr_cat = catModel::getInfo($curr_cat_id);

        $cat_name_array = catModel::getAllInfo();

        // Calculate total pages
        $all = ceil($msg->countThreads($curr_cat_id) / 20);

        // Get current page
        $page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
        $page < 1 && $page = 1;
        $page > $all && $page = $all;

        // Get discussions for current page
        $start = ($page - 1) * 20;
        $discussions = $msg->getThreads($curr_cat_id, $start, 20);

        // Set template variables
        $data = [
            'xportal' => $xportal,
            'allCats' => $allCats,
            'curr_cat_id' => $curr_cat_id,
            'curr_cat' => $curr_cat,
            'cat_name_array' => $cat_name_array,
            'page' => $page,
            'all' => $all,
            'discussions' => $discussions
        ];

        // Extract variables for the view
        extract($data);

        // Load the view
        require(ROOT . "view/index.php");
    }
}