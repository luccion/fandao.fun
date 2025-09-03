<?php
/*
file model.class.php
Model基类
*/

defined('ACC') || exit('ACC Denied');
class model
{
	protected $table = NULL;
	protected $db = NULL;
	public $itemTypeName = ['user', 'currency', 'stock', 'thread', 'avatar', 'item', 'equipment', 'artifact', 'planet'];
	public function __construct()
	{
		$conf = conf::getInstance();
		if ($conf->use_mysqli) {
			$this->db = mysql_::getInstance();
		} else {
			$this->db = mysql::getInstance();
		}
	}
	public function table($table)
	{
		$this->table = $table;
	}
	public static function db()
	{
		$conf = conf::getInstance();
		if ($conf->use_mysqli) {
			$db = mysql_::getInstance();
		} else {
			$db = mysql::getInstance();
		}
		return $db;
	}

	public function get_nft_type($gid)
	{
		$sql = 'select type from global where gid=' . $gid;
		return $this->db->getOne($sql);
	}
}
