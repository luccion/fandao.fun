<?php
/*
file msgModel.class.php
留言板操作类
*/
defined('ACC') || exit('ACC Denied');

class msgModel extends model
{
	/**
	 * 过滤用户提交正文,包含骰子函数和链接函数	
	 * @param string $cont
	 * @return string
	 */
	protected function filterCont($cont)
	{
		function getDice($dice)
		{

			if ($dice[2] > 0) {
				$num = random_int(1, $dice[2]);
			} else if ($dice[2] < 0) {
				$num = random_int($dice[2], -1);
			} else {
				$num = 0;
			}
			$result = '<span class="dice" data-toggle="tooltip" data-placement="top" title="D' . $dice[2] . '骰——掷出前可编辑为需要的骰子，比如D8、D20等。"><small><i class="fa-solid fa-dice-d6"></i>&nbsp;</small><b>' . $num . '</b><small> (D' . $dice[2] . ')</small></span>';
			return $result;
		}
		function filterURL($content)
		{
			$sup = '<sup><i class="fa-solid fa-arrow-up-right-from-square"></i></sup>';
			$url = $content[1];
			if ($content[5]) {
				$result = '<a class="fandaoLink" href="' . $url . '" target="_blank">' . $content[6] . $sup . '</a>';
			} else {
				$result = '<a class="fandaoLink" href="' . $url . '" target="_blank">' . $url . $sup . '</a>';
			}
			$result = str_replace("&amp;", "&", $result);
			return $result;
		}

		$cont = htmlspecialchars($cont); //获取html格式				
		$cont = preg_replace('/(\r\n|\r|\n)/', '<br/>', $cont); //过滤换行
		$cont = preg_replace('/\s/', '&nbsp;', $cont); //过滤空格	
		$cont = preg_replace('/&gt;&gt;\d+(&gt;\d+)?/', '<span class="quote">$0</span>', $cont); //过滤引用
		$cont =	preg_replace_callback('/\[\[(d|dice|r|roll)([-]?[0-9]+)\]\]/i', 'getDice', $cont); //过滤骰子
		$cont =	preg_replace('/\[\[doodle:([a-fA-F0-9]+\.[a-z]+)\]\]/', '<div class="fandaoDoodle"><img class="fandaoImg" src="upload/$1" alt="fandaoDoodle" title="$1"></div>', $cont); //涂鸦地址转换
		$cont =	preg_replace('/\[\[img:([a-fA-F0-9]+\.[a-z]+)\]\]/', '<img class="fandaoImg" src="upload/$1" alt="fandaoIMG" title="$1">', $cont); //图片地址转换
		$cont = preg_replace_callback('/\[\[(((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.([a-zA-Z0-9&amp;\%\.\/\?\:@\-_=#])*)(\|)?(.+)?\]\]/', 'filterURL', $cont); //地址链接转换，放在最后是防止图片链接被替换
		return $cont;
	}

	//过滤用户提交标题
	protected function filterTitle($title)
	{
		$title = htmlspecialchars($title);
		$title = preg_replace('/(\r\n|\r|\n)/', '', $title);
		return $title;
	}

	/**
	 *	修改主题
	 *	@param int		$tid
	 *	@param int		$cat
	 *	@param string	$title
	 *	@param string	$content
	 *	@return bool
	 */
	public function editThread($tid, $cat, $title, $content)
	{
		$title = base64_encode($this->filterTitle($title));
		$content = base64_encode($this->filterCont($content));
		$sql = "update thread set cat=$cat,title='$title',content='$content' where tid=$tid";
		return $this->db->query($sql);
	}

	/** 
	 *	修改回复
	 *	@param int $tid
	 *	@param int $floor
	 *	@param string $content
	 *	@return bool
	 */
	public function editReply($tid, $floor, $content)
	{
		$content = $this->filterCont($content);
		$sql = "update reply set content='$content' where tid=$tid and floor=$floor";
		return $this->db->query($sql);
	}

	/**
	 *	返回指定栏目下指定数量范围新发布主题
	 *	@param int $start	开始位置
	 *	@param int $cat_id		栏目
	 *	@param int $num		获取数量，默认10
	 *	@return array
	 */
	public function getTopThreads($start, $cat_id = 0, $num = 10)
	{
		$sql = 'select * from thread where cat in (' . implode(',', catModel::getCatTreeId($cat_id)) . ') order by lastreptime desc limit ' . $start . ',' . $num;
		return $this->db->getAll($sql);
	}

	/*
		返回指定tid主题
		* @param int $tid
		*@return array
	 */
	public function getThread($tid)
	{
		$sql = "select * from thread where tid=$tid";
		return $this->db->getRow($sql);
	}

	/**
	 *	删除指定tid主题及该主题所有回复
	 * @param int $tid
	 * @return array(bool,bool)
	 */
	public function delThread($tid)
	{
		$re = array();
		//删除主题
		$sql = "delete from thread where tid=$tid";
		$re[] = $this->db->query($sql);
		//删除回复
		$sql = "delete from reply where tid=$tid";
		$re[] = $this->db->query($sql);
		return $re;
	}

	/**
	 *返回指定tid及楼层回复
	 * @param int	$rid
	 * @param int	$floor
	 * @return array
	 */
	public function getReply($tid, $floor)
	{
		$sql = "select * from reply where tid=$tid and floor=$floor";
		return $this->db->getRow($sql);
	}

	/**
	 * 删除指定tid和楼层的回复
	 * @param int	$rid
	 * @param int	$floor
	 * @return bool
	 */
	public function delReply($tid, $floor)
	{
		$sql = "delete from reply where tid=$tid and floor=$floor";
		return $this->db->query($sql);
	}

	/**
	 *返回指定主题指定数量范围新发布回复,ASC 升序,DESC 降序
	 * @param int $tid		被回复主题tid
	 * @param int $start	开始位置
	 * @param int $num		最大数量
	 *@return array		
	 */
	public function getTopReplies($tid, $start, $num = 5)
	{
		$sql = "select * from reply where tid=$tid order by rid ASC limit $start,$num";
		return $this->db->getAll($sql);
	}

	/**
	 *返回指定主题下一楼层数
	 * @param int $tid		主题tid
	 * @return int
	 */
	public function getNextFloor($tid)
	{
		$sql = "select floor from reply where tid=$tid order by rid desc limit 0,1";
		$r = $this->db->getOne($sql);
		$r = !$r ? 1 : $r + 1;
		return $r;
	}

	/** 
	 * 返回指定主题指定数量范围顺序发布回复
	 * @param int $tid		被回复主题tid
	 * @param int $start	开始位置
	 * @param int $num		最大数量
	 * @return array
	 */
	public function getReplies($tid, $start, $num = 5)
	{
		$sql = "select username,content,reptime from reply where tid=$tid limit $start,$num";
		return $this->db->getAll($sql);
	}

	/*
		获取主题数量
		param int	$cat
		return int
	 */
	public function countThreads($cat)
	{
		$cat = catModel::getCatTreeId($cat);
		$sql = 'select count(*) from thread where cat in (' . implode(',', $cat) . ')';
		return $this->db->getOne($sql);
	}
	/*
		获取喜欢数量
		* @param int $tid		被回复主题tid
		return int
	 */
	public function countLikes($tid)
	{
		$sql = "select count(*) from thread_likes where tid=$tid";
		return $this->db->getOne($sql);
	}

	/*
		获取回复数量
		* @param int $tid		被回复主题tid
		return int
	 */
	public function countReplies($tid)
	{
		$sql = "select count(*) from reply where tid=$tid";
		return $this->db->getOne($sql);
	}

	/*
		获取指定用户主题数量
		* @param int $uid		被用户uid
		return int
	 */
	public function countUserThreads($uid)
	{
		$sql = "select count(*) from thread where uid=$uid";
		return $this->db->getOne($sql);
	}

	/*
		获取指定用户发送主题
		* @param int $uid		被用户uid
		param int $start	开始位置
		param int $num		最大数量
		return int
	 */
	public function getUserThreads($uid, $start, $num = 5)
	{
		$sql = "select * from thread where uid=$uid order by tid desc limit $start,$num";
		return $this->db->getAll($sql);
	}

	/*
		获取指定用户回复数量
		* @param int $uid		被用户uid
		return int
	 */
	public function countUserReplies($uid)
	{
		$sql = "select count(*) from (select distinct tid from reply where uid=$uid)tids";
		return $this->db->getOne($sql);
	}

	/*
		获取指定用户发送的回复及所在主题
		* @param int $uid		被用户uid
		param int $start	开始位置
		param int $num		最大数量
		return int
	 */
	public function getUserReplies($uid, $start, $num = 5)
	{
		$sql = "select distinct tid from reply where uid=$uid order by tid desc limit $start,$num";
		$tids = $this->db->getAll($sql);
		$threads = array();
		foreach ($tids as $v) {
			$t = $this->getThread($v['tid']);
			$sql2 = "select * from reply where uid=$uid and tid={$v['tid']} order by rid desc";
			$t['replies'] = $this->db->getAll($sql2);
			$threads[] = $t;
		}
		return $threads;
	}

	/**
	 *增加新主题
	 * @param array $data 格式['cat'=>'栏目id','uid=>'用户uid','name'=>'昵称','title'=>'标题','content'=>'发布内容']
	 */
	public function addThread($data)
	{
		$data['content'] = base64_encode($this->filterCont($data['content']));
		$data['title'] = base64_encode($this->filterTitle($data['title']));
		$data['pubtime'] = time();
		$data['lastreptime'] = $data['pubtime'];
		$this->db->autoExecute('thread', $data);
		return $this->db->getLastInsertID();
	}
	public function regThreadGID($tid, $gid)
	{
		$sql = 'update thread SET gid=' . $gid . ' where tid=' . $tid;
		return $this->db->query($sql);
	}

	/*
		增加新回复
		* @param array $data 格式['tid'=>0,'uid=>'用户uid'',name'=>'昵称','content'=>'发布内容']
		return bool
	 */
	public function addReply($data)
	{
		$data['content'] = base64_encode($this->filterCont($data['content']));
		$data['reptime'] = time();
		if (!$this->isSAGE($data['tid'])) {
			$sql = 'update thread set lastreptime=' . $data['reptime'] . ' where tid="' . $data['tid'] . '"';
			$this->db->query($sql);
		}

		//当增加新回复时，会给PO主发送一个通知，该通知将会记录在PO的数据库中，前端访问后删除
		$sql1 = 'select uid from thread where tid=' . $data['tid'];
		$threaduid = $this->db->getOne($sql1);
		if ($threaduid != $data['uid']) {
			$sql2 = 'update user set notifications=notifications+1 where uid=' . $threaduid;
			$this->db->query($sql2);
		}

		return $this->db->autoExecute('reply', $data);
	}

	/*
		通知已读
		* @param int tid 
		return bool
	 */
	public static function clearNotification($uid)
	{
		$db = model::db();
		$sql = 'update user set notifications=0 where uid=' . $uid;
		$db->query($sql);
	}
	/*
		查询指定主题是否SAGE
		* @param int $tid
		return bool
	 */
	public function isSAGE($tid)
	{
		$sql = 'select SAGE from thread where tid=' . $tid;
		return !!$this->db->getOne($sql);
	}

	/*
		SAGE指定主题(该主题将不会因新回复而被顶到页首)
		* @param int $tid
		return bool
	 */
	public function SAGE($tid)
	{
		$sql = 'update thread set SAGE=1 where tid=' . $tid;
		return $this->db->query($sql);
	}

	/*
		解除下沉指定主题(该主题将会因新回复而被顶到页首)
		* @param int $tid
		return bool
	 */
	public function UNSAGE($tid)
	{
		$sql = 'update thread set SAGE=0 where tid=' . $tid;
		return $this->db->query($sql);
	}
	public function contribution($tid, $amount)
	{
		$sql = 'update thread set contribution=contribution+' . $amount . ' where tid=' . $tid;
		return $this->db->query($sql);
	}
}
