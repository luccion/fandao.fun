<?php
/*
file proposalModel.class.php
留言板操作类
*/
defined('ACC') || exit('ACC Denied');

class proposalModel extends model
{
	//过滤用户提交正文
	protected function filterCout($cont)
	{
		$cont = htmlspecialchars($cont);
		$cont = preg_replace('/(\r\n|\r|\n)/', '<br>', $cont);
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
	 *	返回指定栏目下指定数量范围新发布决议 ASC 升序	DESC 降序
	 *	@param int $start	开始位置
	 *	@param int $num		获取数量
	 *	@return array|bool
	 */
	public function getTopProposals()
	{
		$sql = 'select * from proposal order by proposetime desc';
		return $this->db->getAll($sql);
	}

	/*
		返回指定pid决议
		* @param int $pid
		return array
	 */
	public function getProposal($pid)
	{
		$sql = "select * from proposal where pid=$pid";
		return $this->db->getRow($sql);
	}

	/*
		删除指定pid决议及该决议所有投票
		* @param int $pid
		return array(bool,bool)
	 */
	public function delProposal($pid)
	{
		$re = array();
		$sql = "delete from proposal where pid=$pid";
		$sql2 = "delete from proposal_votes where pid=$pid";
		$this->db->query($sql);
		$this->db->query($sql2);
	}


	/*
		获取决议数量		
		return int
	 */
	public function countProposals()
	{

		$sql = 'select count(*) from proposal';
		return $this->db->getOne($sql);
	}
	/*
		获取支持数量
		* @param int $pid		被回复决议pid
		return int
	 */
	public function countPros($pid)
	{
		$sql = "select count(*) from proposal_votes where pid=$pid and position=1";
		return $this->db->getOne($sql);
	}
	/*
		获取反对数量
		* @param int $pid		被回复决议pid
		return int
	 */
	public function countCons($pid)
	{
		$sql = "select count(*) from proposal_votes where pid=$pid and position=0";
		return $this->db->getOne($sql);
	}


	/*
		获取指定用户决议数量
		* @param int $uid		被用户uid
		return int
	 */
	public function countUserProposals($uid)
	{
		$sql = "select count(*) from proposal where uid=$uid";
		return $this->db->getOne($sql);
	}

	/*
		获取指定用户发送决议
		* @param int $uid		被用户uid
		param int $start	开始位置
		param int $num		最大数量
		return int
	 */
	public function getUserProposals($uid, $start, $num = 5)
	{
		$sql = "select * from proposal where uid=$uid order by pid desc limit $start,$num";
		return $this->db->getAll($sql);
	}

	/*
		增加新决议
		* @param array $data 格式['uid=>'用户uid','title'=>'标题','content'=>'发布内容']
	 */
	public function addProposal($data)
	{

		$data['content'] = base64_encode($this->filterCout($data['content']));
		$data['title'] = base64_encode($this->filterTitle($data['title']));
		$data['proposetime'] = time();
		return $this->db->autoExecute('proposal', $data);
	}

	/*
		查询指定决议是否被否决
		* @param int $pid
		return bool
	 */
	public function isRejected($pid)
	{
		$sql = 'select rejected from proposal where pid=' . $pid;
		return $this->db->getOne($sql);
	}

	/*
		通过指定决议，会取代否决状态
		* @param int $pid
		return bool
	 */
	public function pass($pid)
	{
		$sql = 'update proposal set rejected=0,passed=1 where pid=' . $pid;
		return $this->db->query($sql);
	}

	/*
		添加决议立场
		* @param int $pid
		* @param int $position (0=>反对,1=>支持)
		return bool
	 */
	public function addProposalVote($pid, $position)
	{
		$data = array();
		$data['pid'] = $pid;
		$data['uid'] = $_SESSION['uid'];
		$data['position'] = $position;
		return $this->db->autoexecute('proposal_votes', $data);
	}

	/*
		清除决议立场
		* @param int $pid		
		return bool
	 */
	public function delProposalVote($pid)
	{
		$sql = 'delete from proposal_votes where uid=' . $_SESSION['uid'] . ' and pid=' . $pid;
		return $this->db->query($sql);
	}
	/*
		检测是否投票
		* @param int $pid		
		return bool
	 */
	public static function isVotedPro($pid)
	{
		$conf = conf::getInstance();
		if ($conf->use_mysqli) {
			$db = mysql_::getInstance();
		} else {
			$db = mysql::getInstance();
		}
		$sql = 'select count(*) from proposal_votes where uid=' . $_SESSION['uid'] . ' and pid=' . $pid . ' and position=1';
		return $db->getOne($sql) > 0;
	}
	public static function isVotedCon($pid)
	{
		$conf = conf::getInstance();
		if ($conf->use_mysqli) {
			$db = mysql_::getInstance();
		} else {
			$db = mysql::getInstance();
		}
		$sql = 'select count(*) from proposal_votes where uid=' . $_SESSION['uid'] . ' and pid=' . $pid . ' and position=0';
		return $db->getOne($sql) > 0;
	}
}
