<?php
/*
file userModel.class.php
用户管理类
*/
defined('ACC') || exit('ACC Denied');

class userModel extends model
{
	protected $table = 'user';

	protected $safe = "lunch";

	/**
	 * 增加新用户，顺便登录和生成钱包
	 * @param string $username 用户名
	 * @param string $password 密码
	 * @param string $email 邮箱
	 * @param int $type 用户组
	 * @param int $current_aid 当前的化身ID
	 * @return array array[0]=>token,array[1]=>uid
	 * 
	 */
	public function add($username, $password, $email, $type = -1, $current_aid)
	{
		$arr = array();
		$arr['regip'] = $_SERVER['REMOTE_ADDR'];
		$arr['regtime'] = time();
		$arr['username'] = $username;
		$arr['password'] = md5($password);
		$arr['email'] = $email;
		$arr['type'] = $type;
		$arr['current_aid'] = $current_aid;
		$arr['token'] = $this->generateToken();
		$re = $this->db->autoExecute($this->table, $arr);
		$uid = $this->db->getLastInsertID();
		if ($re) {
			$this->login($username, $password);
			$this->addWallet($username);
		}
		$array = [$arr['token'], $uid];
		return $array;
	}
	//写入log数据库
	public function log2db()
	{
		$date = date("ymd");
		$str = $_SERVER['REMOTE_ADDR'];
		$str = str_replace(".", "", $str);
		$arr = array();
		$arr['iptime'] = $date . $str;
		$arr['time'] = date("Y-m-d");
		$this->db->autoExecute('log', $arr);
	}
	//读取log数据库,返回n=30天之内的ip访问量
	public static function countIPs($n = 30)
	{
		$db = model::db();
		for ($i = $n; $i >= 0; $i--) {
			$date = date_create();
			date_sub($date, date_interval_create_from_date_string($i . " days"));
			$time = date_format($date, "ymd");
			$sql = 'SELECT count(iptime) from log where time=' . $time;
			$row[$n - $i] = $db->getOne($sql);
		}
		return $row;
	}

	public static function generateToken()
	{
		$arr = array_merge(range('A', 'Z'), range('0', '9'));
		shuffle($arr);
		$arr = array_flip($arr);
		$arr = array_rand($arr, 6);
		$res = '';
		foreach ($arr as $v) {
			$res .= $v;
		}
		$token = substr(md5(time() . "lunch" . $res), -8);
		return $token;
	}

	public function	addWallet($username)
	{
		$data = array();
		$data['wid'] = md5($username . time());
		$data['uid'] = $_SESSION['uid'];
		$this->db->autoexecute('wallet', $data);
		$sql = 'SELECT wid from wallet where uid=' . $_SESSION['uid'];
		$_SESSION['wallet'] =	htmlspecialchars($this->db->getOne($sql));
	}

	public function addAvatar($chinese, $english, $svg)
	{
		$data = array();
		$data['chinese'] = $chinese;
		$data['english'] = $english;
		$data['createtime'] = time();
		$data['svg'] = $svg;
		$this->db->autoexecute('avatar', $data);
		return $this->db->getLastInsertID();
	}
	public function regAvatarGID($aid, $gid)
	{
		$sql = 'update avatar SET gid=' . $gid . ' where aid=' . $aid;
		return $this->db->query($sql);
	}

	public function exists_uname($uname)
	{
		$sql = 'SELECT count(*) from ' . $this->table . ' where username=\'' . $uname . '\'';
		return $this->db->getOne($sql) != 0;
	}

	public function exists_email($email)
	{
		$sql = 'SELECT count(*) from ' . $this->table . ' where email=\'' . $email . '\'';
		return $this->db->getOne($sql) != 0;
	}

	public static function getUsername($uid)
	{
		$db = model::db();
		$sql = 'SELECT username from user where uid=' . $uid;
		return $db->getOne($sql);
	}

	public static function username2uid($username)
	{
		$db = model::db();
		$sql = 'SELECT uid from user where username=\'' . $username . '\'';
		return $db->getOne($sql);
	}

	/* 
		切换化身
		function switchToAid
		param: int aid
		param: int uid
		return bool
	*/
	public static function switchToAid($aid, $uid)
	{
		$db = model::db();
		$sql = 'update user SET current_aid=' . $aid . '  where uid=' . $uid;
		$_SESSION['current_aid'] = $aid;
		return $db->query($sql);
	}

	public static function getUserCurrentAvatarSVG($current_aid)
	{
		$db = model::db();
		$sql = 'SELECT svg from avatar where aid=' . $current_aid;
		return $db->getOne($sql);
	}
	public static function getCurrentAvatarSVG($name)
	{
		$db = model::db();
		$sql = 'SELECT svg from avatar where chinese=\'' . $name . '\'';
		return $db->getOne($sql);
	}


	public function login($user, $pass, $loginContinue = true)
	{
		if ($loginContinue == true) {
			$expireTime = 2592000;
		} else {
			$expireTime = 86400;
		}
		$isLogin = false;
		$sql = 'SELECT * from ' . $this->table . ' where username=\'' . $user . '\'';
		$row = $this->db->getRow($sql);
		if (count($row) > 0) {
			if ($row['username'] === $user && $row['password'] === md5($pass)) {
				$isLogin = true;
				$_SESSION = $row;
				setcookie("FANDAO_TOKEN", $row['token'], time() + $expireTime, "/", "fandao.fun", true, true);
				setcookie("FANDAO_USERNAME", $row['username'], time() + $expireTime, "/", "fandao.fun", true, true);
				$sql2 = 'SELECT chinese from avatar where aid=' . $_SESSION['current_aid'];
				$_SESSION['avatar'] =	htmlspecialchars($this->db->getOne($sql2));
				$sql3 = 'SELECT wid from wallet where uid=' . $_SESSION['uid'];
				$row2 = $this->db->getRow($sql3);
				$_SESSION['wallet'] = $row2['wid'];
				$upsql = 'update ' . $this->table .
					' set lastip=\'' . $_SERVER['REMOTE_ADDR'] .
					'\',lastlogin=\'' . time() .
					'\' where uid=' . $row['uid'];
				$this->db->query($upsql);
			}
		}
		$isLogin || $this->logout();
		return $isLogin;
	}

	public function loginWithCookies()
	{
		$isLogin = false;
		$sql = 'SELECT * from ' . $this->table . ' where username=\'' . $_COOKIE['FANDAO_USERNAME'] . '\'';
		$row = $this->db->getRow($sql);
		if (count($row) > 0) {
			if ($row['username'] === $_COOKIE['FANDAO_USERNAME'] && $row['token'] === $_COOKIE['FANDAO_TOKEN']) {
				$isLogin = true;
				$_SESSION = $row;
				$sql2 = 'SELECT chinese from avatar where aid=' . $_SESSION['current_aid'];
				$_SESSION['avatar'] =	htmlspecialchars($this->db->getOne($sql2));
				$sql3 = 'SELECT wid from wallet where uid=' . $_SESSION['uid'];
				$row2 = $this->db->getRow($sql3);
				$_SESSION['wallet'] = $row2['wid'];
				$upsql = 'update ' . $this->table .
					' set lastip=\'' . $_SERVER['REMOTE_ADDR'] .
					'\',lastlogin=\'' . time() .
					'\' where uid=' . $row['uid'];
				$this->db->query($upsql);
			}
		}
		$isLogin || $this->logout();
		return $isLogin;
	}

	public function update($uid, $data)
	{
		return $this->db->autoexecute($this->table, $data, 'update', 'uid=' . $uid);
	}
	/* 
		自动登录，如果存在正确的Cookies，则通过Token自动登录。
		function autoLogin				
		return bool
	*/
	public function autoLogin()
	{
		if (isset($_COOKIE['FANDAO_USERNAME']) && isset($_COOKIE['FANDAO_TOKEN'])) {
			$sql =	'SELECT token from user where username=\'' . $_COOKIE['FANDAO_USERNAME'] . '\'';
			$one = $this->db->getOne($sql);
			if ($_COOKIE['FANDAO_TOKEN'] == $one) {
				return $this->loginWithCookies();
			} else {
				setcookie("FANDAO_TOKEN", "", time() - 3600, "/", "whiteverse.city", true, true);
				setcookie("FANDAO_USERNAME", "", time() - 3600, "/", "whiteverse.city", true, true);
			}
		}
		return false;
	}

	public static function isLogin()
	{
		return isset($_SESSION['username']);
	}

	public static function logout()
	{
		$temp = isset($_SESSION['template']) ? $_SESSION['template'] : 'default';
		$_SESSION = array();
		$_SESSION['template'] = $temp;
		setcookie("FANDAO_TOKEN", "", time() - 3600, "/", "fandao.fun", true, true);
		setcookie("FANDAO_USERNAME", "", time() - 3600, "/", "fandao.fun", true, true);
	}
	/* 
		验证邮箱，如果token符合则验证成功，设定为已验证
		function deleteAvatar
		param: int aid		
		return bool
	*/
	public function emailVerify($token, $username)
	{
		$sql = 'SELECT token from user where username=\'' . $username . '\'';
		$row = $this->db->getOne($sql);
		if ($token == $row) {
			$sql2 = 'update user set email_verified=1,type=0 where username=\'' . $username . '\'';
			$this->db->query($sql2);
			return true;
		} else {
			return false;
		}
	}
	public static function isBanned($uid)
	{
		$db = model::db();
		$sql = 'SELECT banned from user where uid=' . $uid;
		return $db->getOne($sql);
	}
	public static function banUser($uid, $days) //day
	{
		$expireTime	= time() + $days * 86400;
		$db = model::db();
		$sql = 'update user set banned=1,expiretime=' . $expireTime . ' where uid=' . $uid;
		$db->query($sql);
		return $expireTime;
	}

	public static function unbanUser($uid)
	{
		$db = model::db();
		$sql = 'update user set banned=0,expiretime=0 where uid=' . $uid;
		return $db->query($sql);
	}

	public static function checkBanExpireTime($uid)
	{
		$db = model::db();
		$sql = 'SELECT expiretime from user where uid=' . $uid;
		return $db->getOne($sql); //return UNIX timestamp
	}

	public static function autoUnbanUser()
	{
		$db = model::db();
		$sql = 'SELECT uid,expiretime from user where banned=1';
		$data = $db->getAll($sql);
		foreach ($data as $user) {
			if ($user['expiretime'] <= time()) {
				$sql = 'update user set banned=0 where uid=' . $user['uid'];
				$db->query($sql);
			}
		}
	}

	public static function setGroup($uid, $group)
	{
		$db = model::db();
		$sql = 'update user set type=' . $group . ' where uid=' . $uid;
		return $db->query($sql);
	}
	/* 
	require_once('Multiavatar.php');
	public static function calculateAvatar()
	{
		$db = model::db();
		$sql = 'SELECT english from avatar';
		$row = $db->getAll($sql);
		$i = 0;
		foreach ($row as $k) {
			$str = $k['english'];
			$avatar = new Multiavatar();
			$svgCode[$i] = $avatar($str, null, null);
			$sql = 'update avatar set svg=\'' . $svgCode[$i] . '\' where english=\'' . $str . '\'';
			$db->query($sql);
			$i++;
		}
	} */

	public function check_in($uid)
	{
		$sql = 'update user set checkin=1 where uid=' . $uid;
		return $this->db->query($sql);
	}

	public function check_in_purge()
	{
		$sql = 'update user set checkin=0';
		$this->db->query($sql);
	}

	/**
	 * 清空某用户全部发帖记录，慎用！
	 * @param int $uid
	 */

	public function purge_user($uid)
	{
		$sql = 'delete * from thread where uid=' . $uid . ';delete * from reply where uid=' . $uid . ';delete * from subscription where uid=' . $uid . ';delete * from user where uid=' . $uid;
		$this->db->query($sql);
	}


	public static function set_user_wiki_name($wiki_name, $uid)
	{
		$db = model::db();
		$sql = 'SELECT count(*) from user where wiki_user_name=\'' . $wiki_name . '\'';
		if ($db->getOne($sql) != 0) { //检测是否已经被链接
			return;
		}
		$sql = 'update user set wiki_user_name=\'' . $wiki_name . '\' where uid=' . $uid;
		return $db->query($sql);
	}

	public static function get_wiki_score($wiki_name)
	{
		$servername = "localhost";
		$username = "whiteverse_wiki";
		$password = "newpassword";
		$dbname = "whiteverse_wiki";
		$con = mysqli_connect($servername, $username, $password);
		if (!$con) {
			die('Could not connect');
		}
		mysqli_SELECT_db($con, "$dbname");
		$sql = 'SELECT `actor_id` FROM `wkactor` WHERE `actor_name`=\'' . $wiki_name . '\'';
		$result = $con->query($sql);
		$res = array_values($result->fetch_assoc())[0];
		mysqli_SELECT_db($con, "$dbname");
		$sql = "SELECT (COUNT(DISTINCT revactor_page)+SQRT(COUNT(*)-COUNT(DISTINCT revactor_page))*2) FROM `wkrevision_actor_temp` WHERE `revactor_actor`=" . $res;
		$result = $con->query($sql);
		$score = array_values($result->fetch_assoc())[0];
		return $score;
	}
	public static function	update_wiki_score_and_get_diff($score, $wiki_name)
	{
		$db = self::db();
		$sql = 'SELECT wiki_score from user where wiki_user_name=\'' . $wiki_name . '\'';
		$oldScore = $db->getOne($sql);
		//update score			
		$sql = 'UPDATE user SET wiki_score =' . $score . ' WHERE wiki_user_name=\'' . $wiki_name . '\'';
		$db->query($sql);
		$diff = $score - $oldScore;
		return $diff;
	}
	public static function if_bind_wiki($wiki_name)
	{
		$db = model::db();
		$sql = 'SELECT count(*),`uid` from user where wiki_user_name=\'' . $wiki_name . '\'';
		$r = $db->getRow($sql);
		if ($r) { //检测是否已经被链接
			return $r['uid'];
		}
	}

	public static function send_post($url, $post_data)
	{
		$postdata = http_build_query($post_data);
		$options = array(
			'http' => array(
				'method' => 'POST',
				'header' => 'Content-type:application/x-www-form-urlencoded',
				'content' => $postdata,
				'timeout' => 15 * 60
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}

	public static function has_aifadian_bound($aifadianID)
	{
		$db = self::db();
		$sql = 'SELECT count(*) from `user` where aifadian_id=\'' . $aifadianID . '\'';
		if ($db->getOne($sql) != 0) { //检测是否已经被链接
			return true;
		}
		return false;
	}
	public static function update_aifadian_id_and_rmb($aifadianID, $rmb, $uid)
	{
		$db = model::db();
		$sql = 'UPDATE user SET aifadian_id=\'' . $aifadianID . '\' , exchanged_rmb=' . $rmb . ' WHERE uid=' . $uid;
		return $db->query($sql);
	}

	public static function update_aifadian_rmb($aifadianID, $rmb)
	{
		$db = model::db();
		$sql = 'UPDATE user SET exchanged_rmb=' . $rmb . ' WHERE aifadian_id=\'' . $aifadianID . '\'';
		return $db->query($sql);
	}
	public static function aifadianid2uid($aifadianID)
	{
		$db = self::db();
		$sql = 'SELECT `uid` FROM `user` WHERE aifadian_id=\'' . $aifadianID . '\'';
		return $db->getOne($sql);
	}
}
