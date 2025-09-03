<?php
/**
 * ArticleController - Handles article display
 */
class ArticleController
{
    public function show()
    {
        $msg = new msgModel();
        $allCats = catModel::getCatTree();
        $curr_cat_id = empty($_GET['cat']) ? 1 : (int)$_GET['cat'];
        $curr_cat_id < 1 && $curr_cat_id = 1;
        $curr_cat = catModel::getInfo($curr_cat_id);

        $title = $_GET['title'] ?? '';
        $article = $_GET['article'] ?? '';

        // Set template variables
        $data = [
            'msg' => $msg,
            'allCats' => $allCats,
            'curr_cat_id' => $curr_cat_id,
            'curr_cat' => $curr_cat,
            'title' => $title,
            'article' => $article
        ];

        // Extract variables for the view
        extract($data);

        // Load the view
        require(ROOT . "view/article.php");
    }
}