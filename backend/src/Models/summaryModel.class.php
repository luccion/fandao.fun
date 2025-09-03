<?php
defined('ACC') || exit('ACC Denied');
class summaryModel extends model
{

    public static function getAllUsers()
    {
        $db = model::db();
        $sql = 'select uid,type,username,regtime,lastlogin from user';
        $row = $db->getAll($sql);
        return $row;
    }

    public static function countThreads()
    {
        $db = model::db();
        $sql = 'select count(tid) from thread';
        $row = $db->getOne($sql);
        return $row;
    }
    public static function countReplies()
    {
        $db = model::db();
        $sql = 'select count(rid) from reply';
        $row = $db->getOne($sql);
        return $row;
    }

    public static function countAvatars()
    {
        $db = model::db();
        $sql = 'select count(aid) from avatar';
        $row = $db->getOne($sql);
        return $row;
    }

    /* 统计当前达成过的交易数量 */
    public static function countTransfer()
    {
        $db = model::db();
        $sql = 'select count(trid) from transfer';
        $row = $db->getOne($sql);
        return $row;
    }

    /* 统计当前饭岛中流动的总资金 */
    public static function countFunds()
    {
        $db = model::db();
        $sql = 'select SUM(amount) from transfer where (fromWallet,gid)=("ISSUED",1)';
        $issued = $db->getOne($sql);
        $fundation = transferModel::calculate_amount("fandao");
        $result = $issued - $fundation;
        return $result;
    }

    /* 统计当前已上传的图片数量 */
    public static function countFilesUploaded()
    {
        $file = "./upload";
        $filen = 0;
        $dir = opendir($file);
        while ($filename = readdir($dir)) {
            if ($filename != "." && $filename != "..") {
                $filename = $file . "/" . $filename;
                if (!is_dir($filename)) {
                    $filen++;
                }
            }
        }
        closedir($dir);
        return $filen;
    }
}
