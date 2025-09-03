<?php
defined('ACC') || die('ACC Denied');

class threadlikesModel extends model
{
    //table:'thread_likes'
    public function add($tid)
    {
        $data = array();
        $data['tid'] = $tid;
        $data['uid'] = $_SESSION['uid'];
        return $this->db->autoexecute('thread_likes', $data);
    }
    public function del($tid)
    {
        $sql = 'delete from thread_likes where uid=' . $_SESSION['uid'] . ' and tid=' . $tid;
        return $this->db->query($sql);
    }

    public function count()
    {
        $sql = 'select count(*) from thread_likes where uid=' . $_SESSION['uid'];
        return $this->db->getOne($sql);
    }
    public static function isLiked($tid)
    {
        $conf = conf::getInstance();
        if ($conf->use_mysqli) {
            $db = mysql_::getInstance();
        } else {
            $db = mysql::getInstance();
        }
        $sql = 'select count(*) from thread_likes where uid=' . $_SESSION['uid'] . ' and tid=' . $tid;
        return $db->getOne($sql) > 0;
    }
}
