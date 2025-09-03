<?php

/**
 *  日志链钱包类 
 * -----------------USER-------------------------------------------
 * get_wallet               通过uid获取钱包地址
 * wid2uid                  通过钱包地址获取uid
 * calculate_amount         通过日志链计算钱包数额
 * get_last_owner           查询某物品的最后一个拥有者
 * get_info_from_gid        查询某全局ID对应的物品信息
 * get_gid_amount           查询某用户名下的某gid物品数量
 * if_onsale                查询某用户的某gid物品是否上架
 * get_all_of               获取某uid用户全部物品信息
 * get_all_of_type          获取某uid用户全部某类型物品
 * list_all_users_property  获取全部用户的资产列表
 * -----------------TRANSFER---------------------------------------
 * transfer                 通过日志链从发起者到接收者传递
 * transfer_forced          强制传递，忽略数额和对象
 * transfer_log             记录传递
 * transfer_log_get_all     获取全部记录
 * check_chain_integrity    检测链完整性 
 * -----------------TRADE------------------------------------------
 * trade                    在市场创建一个交易委托
 * trade_close              根据tdid删除交易单
 * trade_close_by_openid    根据openid删除交易单
 * trade_close_by_gid       根据gid删除交易单
 * trade_get_one            根据tdid获取一条交易单
 * trade_get_all            获取全部商品信息
 * trade_update             更新交易单（进行了交易）
 * trade_purge              交易单超时下架
 * deal                     进行交易（不更新交易单）
 * -----------------ITEMS------------------------------------------
 * add_new_item             添加新物品
 * reg_item_gid             注册物品gid
 * reg_nft                  注册NFT，上链
 * count_nft                统计链上的所有NFT     
 * openid2gid               通过openid查找gid
 * gid2openid               通过gid查找openid
 * get_nft_type             获取gid物品类型id
 * get_transfer_log         获取gid物品交易记录
 * get_price_history        获取gid物品历史价格
 * get_meta                 根据gid获取物品元数据
 * get_abstract             根据gid获取物品摘要
 * decompose                分解资产
 * estimation               估值
 * -----------------FOUNDATION-------------------------------------
 * issue_to                 通过基金会发钱给用户
 * charge_from              通过基金会向用户收钱
 * issue_to_all             通过基金会发钱给所有用户
 * lottery_issue_to         通过彩票基金会发钱给用户
 * lottery_charge_from      通过彩票基金会向用户收钱
 * 
 * **/
defined('ACC') || exit('ACC Denied');
class transferModel extends userModel
{
    /**
     *通过uid获取钱包地址wid
     * @param int $uid uid
     * @return string wid 
     **/
    public static function get_wallet($uid)
    {
        $db = self::db();
        $sql = 'SELECT wid from wallet WHERE uid=' . $uid;
        return $db->getOne($sql);
    }
    /**
     *通过钱包地址wid获取用户id uid
     * @param string $wid wid
     * @return int uid 
     **/
    public static function wid2uid($wid)
    {
        $db = self::db();
        $sql = 'SELECT uid from wallet WHERE wid="' . $wid . '"';
        return $db->getOne($sql);
    }

    /**
     *通过日志链从发起者到接收者传递💴
     * @param int $payer Uid
     * @param int $reciever Uid
     * @param int $amount
     * @param string $note
     * @return array $checkCount 
     **/

    public function transfer($payer, $reciever, $gid = 1, $amount, $note = "")
    {
        $type = $this->get_nft_type($gid);
        if ($payer == $reciever) {
            return false;
        } else if ($amount <= 0) {
            return false;
        }
        //通过日志链获取钱包余额
        $payerWallet = $this->get_wallet($payer);
        $recieverWallet = $this->get_wallet($reciever);
        $checkCount["payer"] = $this->calculate_amount($payerWallet, $gid);
        $checkCount["status"] = false;
        //检查是否还有余额，如果不足以转账，则结束	
        if ($checkCount["payer"] < $amount) {
            $checkCount["status"] = false;
            return $checkCount;
        } else {
            //转移成功后输出双方余额和转账结果…… 
            $checkCount["payer"] = $checkCount["payer"] - $amount;
            $checkCount["status"] = true;
            //并添加一条记录			
            if ($this->transfer_log($payerWallet, $recieverWallet, $gid, $amount, $note, $type)) {
                return $checkCount;
            } else {
                return false;
            };
        }
    }
    /**
     * ⛔慎用，强制传递，忽略数额和对象
     * @param int $payer pid
     * @param int $reciever pid
     * @param int $amount
     * @param string $note
     * @return array $checkCount 
     **/

    public function transfer_forced($payer, $reciever, $gid = 1, $amount = 0, $note = "", $type = 1)
    {
        $payerWallet = $this->get_wallet($payer);
        $recieverWallet = $this->get_wallet($reciever);
        if ($this->transfer_log($payerWallet, $recieverWallet, $gid, $amount, $note, $type)) {
            return true;
        } else {
            return false;
        };
    }

    /**
     * 记录传递     
     *@param string $fromWallet wid
     *@param string $toWallet wid
     *@param int $gid  全局id，1为薯条
     *@param int $amount 数额，默认为1
     *@param string $note 备注
     *@return bool
     **/
    public function transfer_log($fromWallet, $toWallet, $gid = 1, $amount = 1, $note = "", $type = 1, $time = "")
    {
        $sql = 'SELECT * from transfer order by trid desc limit 1';
        $row = $this->db->getRow($sql);
        // $this->check_chain_integrity(); //可能会导致高开销
        $arr = array();
        $arr['fromWallet'] = $fromWallet;
        $arr['toWallet'] = $toWallet;
        $arr['gid'] = $gid;
        $arr['type'] = $type;
        $arr['amount'] = $amount;
        $arr['time'] = $time ?: time();
        $arr['note'] = base64_encode($note);
        $arr['digest'] = hash('sha256', implode($row));
        return $this->db->autoExecute('transfer', $arr);
    }
    /**
     * 检测链完整性   
     * @return bool
     **/
    public function check_chain_integrity()
    {
        $sql = 'SELECT * from transfer order by trid desc';
        $all = $this->db->getAll($sql);
        for ($i = 0; $i < (count($all) - 1); $i++) {
            if ($all[$i]['digest'] != hash('sha256', implode($all[$i + 1]))) {
                return false;
            }
        }
        return true;
    }
    /**
     *  获取全部记录
     * 	@param string $wid
     * 	@return array
     **/
    public static function transfer_log_get_all($wid)
    {
        $db = self::db();
        $sql = 'SELECT * from transfer WHERE toWallet="' . $wid . '" or fromWallet= "' . $wid . '"';
        $row = $db->getAll($sql);
        return $row;
    }

    /**
     * 通过日志链计算钱包数额	
     * @param string $wid	
     * @return int
     **/
    public static function calculate_amount($wid, $gid = 1)
    {
        $db = self::db();
        $sql = 'SELECT amount from transfer WHERE (toWallet,gid)=("' . $wid . '",' . $gid . ')';
        $row = $db->getAll($sql);
        $toWallet = array();
        for ($i = 0; $i < sizeof($row); $i++) {
            $toWallet[$i] = $row[$i]['amount'];
        }
        unset($row);
        $sql = 'SELECT amount from transfer WHERE (fromWallet,gid)=("' . $wid . '",' . $gid . ')';
        $row = $db->getAll($sql);
        $fromWallet = array();
        for ($i = 0; $i < sizeof($row); $i++) {
            $fromWallet[$i] = $row[$i]['amount'];
        }
        $amount = array_sum($toWallet) - array_sum($fromWallet);
        return $amount;
    }

    /**
     * 通过基金会发钱给用户
     * @param int $uid
     * @param int $amount
     * @param string $note	
     * @return array $checkCount
     **/
    public function issue_to($uid, $amount, $note = "")
    {
        $recieverWallet = $this->get_wallet($uid);
        $this->transfer_log("fandao", $recieverWallet, 1, $amount, $note, 1);
        return;
    }

    /** 通过基金会向用户收钱
     * @param int $uid 用户ID
     * @param int $amount 数额	
     * @param string $note 备注
     * @return array $checkCount
     **/
    public function charge_from($uid, $amount, $note = "")
    {
        $payerWallet = $this->get_wallet($uid);
        $recieverWallet = "fandao";
        $checkCount["payer"] = $this->calculate_amount($payerWallet);
        if ($checkCount["payer"] < $amount) {
            $checkCount["status"] = false;
            return $checkCount;
        } else {
            $checkCount["payer"] = $checkCount["payer"] - $amount;
            $checkCount["status"] = true;
            $this->transfer_log($payerWallet, $recieverWallet, 1, $amount, $note, 1);
            return $checkCount;
        }
    }
    /**
     * 通过基金会发钱给所有用户，定时活动需要
     * @param int $amount
     * @param string $note	
     * @return array $checkCount
     **/
    public function issue_to_all($amount, $note)
    {
        $sql = 'SELECT * from wallet';
        $row = $this->db->getAll($sql);
        foreach ($row as $t) {
            $recieverWallet = $t['wid'];
            $checkCount["status"] = true;
            $this->transfer_log("fandao", $recieverWallet, 1, $amount, $note, 1);
        }
        return $checkCount;
    }

    /**
     * 通过彩票基金会发钱给用户
     * @param int $uid
     * @param int $amount
     * @param string $note	
     * @return array $checkCount
     **/
    public function lottery_issue_to($uid, $amount, $note)
    {
        $recieverWallet = $this->get_wallet($uid);
        $this->transfer_log("lottery", $recieverWallet, 1, $amount, $note, 1);
        return;
    }

    /**
     * 通过彩票基金会向用户收钱
     * @param int $uid
     * @param int $amount
     * @param string $note	
     * @return array $checkCount
     **/
    public function lottery_charge_from($uid, $amount, $note = "")
    {
        $payerWallet = $this->get_wallet($uid);
        $checkCount["payer"] = $this->calculate_amount($payerWallet);
        if ($checkCount["payer"] < $amount) {
            $checkCount["status"] = false;
            return $checkCount;
        } else {
            $checkCount["payer"] = $checkCount["payer"] - $amount;
            $checkCount["status"] = true;
            $this->transfer_log($payerWallet, "lottery", 1, $amount, $note, 1);
            return $checkCount;
        }
    }
    /**
     * 查询某物品的最后一个拥有者
     * @param int $gid 全局ID
     * @return string 钱包
     **/
    public static function get_last_owner($gid)
    {
        $db = self::db();
        $sql = 'SELECT toWallet from transfer WHERE gid=' . $gid . ' order by trid desc limit 1';
        $one = $db->getOne($sql);
        return $one;
    }
    /**
     * 查询某全局ID对应的物品信息
     * @param int $gid 全局ID
     * @param string typeName 类型名
     * @return array
     **/
    public static function get_info_from_gid($gid, $typeName)
    {
        $db = self::db();
        $sql = "SELECT * FROM `$typeName` WHERE gid = " . $gid;
        return $db->getRow($sql);
    }
    protected function if_onsale($gid, $uid)
    {
        $sql = 'SELECT amount from trade WHERE (gid,uid)=("' . $gid . '",' . $uid . ')';
        return $this->db->getOne($sql);
    }
    /**
     * 💛查询某uid用户的所有物品，数量，并检测是否已经上架
     * @param int $uid uid
     * @return array 
     **/
    public function get_all_of($uid)
    {

        $userWallet = $this->get_wallet($uid);
        $sql = 'SELECT gid,type FROM transfer WHERE toWallet=\'' . $userWallet . '\' AND type!=1';
        $all = $this->db->getAll($sql);
        $all = array_reverse(array_map('unserialize', array_unique(array_map('serialize', array_reverse($all)))));
        $i = 0;
        foreach ($all as $a) {
            if (($a['type'] != 1) && ($a['type'] != 2) && ($a['type'] != 5)) {
                $itemOwnerWallet = $this->get_last_owner($a['gid']);
                if ($itemOwnerWallet == $userWallet) {
                    $gid[$i] = $a['gid'];
                }
            } else {
                $gid[$i] = $a['gid'];
            }
            $i++;
        }
        $i = 0;
        foreach ($gid as $g) {
            $type = $this->get_nft_type($g);
            $typeName = $this->itemTypeName[$type];
            $item[$i] = $this->get_info_from_gid($g, $typeName);
            $item[$i]['type'] = $type;
            $item[$i]['onsale'] = $this->if_onsale($g, $uid);
            if (($type != 3) && ($type != 4) && ($type != 6) && ($type != 7) && ($type != 8)) {
                $item[$i]['amount'] =   $this->calculate_amount($userWallet, $g);
                if ($item[$i]['amount'] == 0) {
                    unset($item[$i]);
                }
            } else {
                $item[$i]['amount'] =  1;
            }
            $i++;
        }

        return $item;
    }

    /**
     * 查询某uid用户的所有某类型物品
     * @param int $uid uid
     * @param int type 类型
     * @return array 返回所有该类型物品
     **/
    public function get_all_of_type($uid, $type = 1)
    {
        define('type', $type);
        function with_type_of($item)
        {
            return $item['type'] == type;
        }
        $items = $this->get_all_of($uid);
        $items_new = array_filter($items, 'with_type_of');
        return $items_new;
    }

    /**
     * 添加新物品
     *
     * @param [array] $arr
     * @return int
     */
    public function add_new_item($arr)
    {
        function filterTitle($title)
        {
            $title = htmlspecialchars($title);
            $title = preg_replace('/(\r\n|\r|\n)/', '', $title);
            return $title;
        }
        $data = array();
        $data['title'] = filterTitle($arr['title']);
        $data['subtitle'] = filterTitle($arr['subtitle']);
        $data['content'] = filterTitle($arr['content']);
        $data['svg'] = $arr['svg'];
        $data['rarity'] = $arr['rarity'];
        $data['price'] = $arr['price'];
        $data['time'] = time();
        $this->db->autoexecute('item', $data);
        return $this->db->getLastInsertID();
    }
    public function reg_item_gid($item_id, $gid)
    {
        $sql = 'update item SET gid=' . $gid . ' WHERE id=' . $item_id;
        return $this->db->query($sql);
    }
    /**
     * 🎵注册NFT，上链。
     * @param string $abstract 摘要，往往是本NFT的名称或关键性描述，具体描述应当在对应数据库中找到。
     * @param string $reciever 接收者，往往是当前用户。
     * @param string $issuer 发行者，可选
     * @param string $note 备注，可选
     * @return int 全局ID
     **/
    public function reg_nft($abstract, $reciever, $issuer, $note, $type, $time = "", $amount = 1)
    {
        //创建唯一ID
        $openid = md5(time() . $abstract);
        $sql = 'insert into global(abstract,type,openid) values(\'' . $abstract . '\',' . $type . ',\'' . $openid . '\')';
        $this->db->query($sql);
        $gid = $this->db->getLastInsertID();
        $recieverWallet = $this->get_wallet($reciever);
        $this->transfer_log($issuer, $recieverWallet, $gid, $amount, $note, $type, $time);
        return $gid;
    }

    public static function count_nft()
    {
        $db = self::db();
        $sql = 'SELECT count(*) from transfer WHERE gid !=1';
        return $db->getOne($sql);
    }

    /**
     * 通过openid查找gid
     */
    public static function openid2gid($openid)
    {
        $db = self::db();
        $sql = 'SELECT gid from global WHERE openid =\'' . $openid . '\'';
        return $db->getOne($sql);
    }
    /**
     * 通过gid查找openid
     */
    public static function gid2openid($gid)
    {
        $db = self::db();
        $sql = 'SELECT openid from global WHERE gid =' . $gid;
        return $db->getOne($sql);
    }

    /**
     * 创建一个交易单，gid和forgid可以互相转换，变为求购单
     * @param int $uid UID
     * @param int $gid GID
     * @param int $for GID
     * @param int $forAmount 
     * @return bool
     */
    public function trade($uid, $gid, $amount = 1, $forAmount = 0, $forGid = 1)
    {
        $arr = array();
        $arr['uid'] = $uid;
        $arr['gid'] = $gid;
        $arr['time'] = time();
        $arr['expiretime'] = time() + 604800;
        $arr['forgid'] = $forGid;
        $arr['amount'] = $amount;
        $arr['foramount'] = $forAmount;
        $fee = floor($forAmount * 0.05); //手续费
        $charge = $this->charge_from($uid, $fee, "FEE");
        if ($charge['status']) {
            return $this->db->autoExecute("trade", $arr);
        } else {
            return false;
        }
    }
    /**
     * 删除交易单
     * @param int $tdid 交易单ID
     * @return bool
     */
    public function trade_close($tdid)
    {
        $sql = 'DELETE from trade WHERE tdid=' . $tdid;
        return $this->db->query($sql);
    }

    public function trade_close_by_openid($openid)
    {
        $gid = $this->openid2gid($openid);
        $sql = 'DELETE from trade WHERE gid=' . $gid;
        return $this->db->query($sql);
    }
    public function trade_close_by_gid($gid)
    {
        $sql = 'DELETE FROM `trade` WHERE `gid`=' . $gid;
        return $this->db->query($sql);
    }
    /**
     * 获取某条商品信息
     */
    public function trade_get_one($tdid)
    {
        $sql = 'SELECT * from trade WHERE tdid=' . $tdid;
        return $this->db->getRow($sql);
    }
    /**
     * 获取全部商品信息
     */
    public function trade_get_all()
    {
        $sql = 'SELECT * from trade';
        $trade = $this->db->getALl($sql);
        foreach ($trade as &$t) {
            $g = $t['gid'];
            $type = $this->get_nft_type($g);
            $typeName = $this->itemTypeName[$type];
            $t['info'] = $this->get_info_from_gid($g, $typeName);
            $t['type'] = $type;
        }
        return $trade;
    }
    public function trade_update($tdid, $tradedAmount)
    {
        $sql = 'update trade SET amount=amount-' . $tradedAmount . ' WHERE tdid=' . $tdid;
        return $this->db->query($sql);
    }

    public function trade_purge()
    {
        //check expire time
        $sql = 'SELECT * from trade';
        $all = $this->db->getAll($sql);
        $now = time();
        foreach ($all as $a) {
            $time = $a['expiretime'];
            if ($now >= $time) {
                $this->trade_close($a['tdid']);
            }
        }
    }
    /**
     * 查询某用户名下的某gid物品数量
     *
     * @param [int] $uid
     * @param [int] $gid
     * @param [int] $amount 满足的最小数量，默认0
     * @return bool 物品数量或False
     */
    public function get_gid_amount($uid, $gid, $amount = 0)
    {

        $wid = $this->get_wallet($uid);
        if ($wid == "avatar") {
            return true;
        }
        $haveAmount = $this->calculate_amount($wid, $gid);
        if ($haveAmount >= $amount) {
            return $haveAmount;
        } else {
            return false;
        }
    }


    /**
     * 获取商品交易记录，不可查询1,通过访问trid以获得其交易价格。交易价格总是位于资产交易记录的前一条
     */

    public function get_transfer_log($gid)
    {
        $sql = 'SELECT * from global WHERE gid=' . $gid;
        $row = $this->db->getRow($sql);
        if ($row['type'] == 1 || $row['type'] == 2 || $row['type'] == 5) {
            return;
        }
        $sql = 'SELECT trid,fromWallet,toWallet,amount,time,note from transfer WHERE gid=' . $gid;
        $all = $this->db->getAll($sql);
        foreach ($all as &$a) {
            if (strlen($a['fromWallet']) == 32) {
                $a['fromWallet'] = "anonymous";
            }
            if (strlen($a['toWallet']) == 32) {
                $a['toWallet'] = "anonymous";
            }
            $sql = 'SELECT amount from transfer WHERE trid=' . ($a['trid'] - 1);
            $a['price'] = $this->db->getOne($sql);
        }
        return $all;
    }
    /**
     * 获取商品历史价格
     */
    public function get_price_history($gid)
    {
        $sql = 'SELECT * FROM global WHERE gid=' . $gid;
        $row = $this->db->getRow($sql);
        if ($row['type'] == 1) {
            return;
        }
        $sql = 'SELECT trid,time,amount FROM transfer WHERE gid=' . $gid . ' AND note=\'' . "VFJBREVORlQ=" . '\'';
        $all = $this->db->getAll($sql);
        foreach ($all as &$a) {
            $sql = 'SELECT amount FROM transfer WHERE trid=' . ($a['trid'] - 1);
            $sumPrice = $this->db->getOne($sql);

            $a['price'] = $sumPrice / $a['amount'];
        }
        return $all;
    }

    /**
     * 根据gid-type获取商品元数据
     */
    public function get_meta($gid)
    {
        $typeName = $this->itemTypeName[$this->get_nft_type($gid)];
        $sql = "SELECT * FROM `$typeName` WHERE gid = " . $gid;
        return $this->db->getRow($sql);
    }

    public static function get_abstract($gid)
    {
        $db = self::db();
        $sql = "SELECT abstract FROM global WHERE gid = " . $gid;
        return $db->getOne($sql);
    }
    //获取gid的type信息，并进入相应表格查询


    /**
     * 🎉进行交易
     * @param int $from 卖家UID
     * @param int $to 买家UID
     * @param int $gid item GID
     * @param int $forGid for GID,money usually
     * @param int $foramount amount forGID
     * @return bool
     */
    public function deal($from, $to, $gid, $amount, $forGid, $foramount, $note = "")
    {
        $payment = $this->transfer($to, $from, $forGid, $foramount, $note);
        if ($payment['status']) { //先转移钱
            $itemtransfer =  $this->transfer($from, $to, $gid, $amount, $note);
            if ($itemtransfer['status']) { //再转移货                
                return true;
            }
            return;
        }
    }

    /**
     * 分解资产
     */
    public function decompose($uid, $gid, $amount = 1)
    {
        $type = $this->get_nft_type($gid);
        $estimation = $this->estimation($gid);
        $itemtransfer = $this->transfer($uid, 125, $gid, $amount, "DECOMPOSE");
        switch ($type) {
            case 1:
                return $estimation;
            default:
                if ($itemtransfer['status']) {
                    $this->issue_to($uid, $estimation, "DECOMPOSE");
                    return $estimation;
                }
        }
    }

    /**
     * 估值v1.0
     * 应根据物品的稀有度、创建时间、版本、交易历史价格等综合评估
     */
    public function estimation($gid)
    {
        $type = $this->get_nft_type($gid);
        switch ($type) {
            case 0:     //user
                return 1;
            case 1:     //currency
                return 1;
            case 2:     //stock
                return 100;
            case 3:     //works
                return 1;
            case 4:     //avatar
                return 25;
            case 5:     //commom_item
                return 1;
            case 6:     //equipment
                return 1;
            case 7:     //artifact
                return 1;
            case 8:     //planet
                return 2000;
        }
    }

    /* 🌐星球专栏🌐 */

    public function add_planet($chinese, $english, $svg)
    {
        $data = array();
        $data['chinese'] = $chinese;
        $data['english'] = $english;
        $data['createtime'] = time();
        $data['svg'] = $svg;
        $this->db->autoexecute('planet', $data);
        return $this->db->getLastInsertID();
    }
    public function reg_planet_GID($plid, $gid)
    {
        $sql = 'update planet SET gid=' . $gid . ' where plid=' . $plid;
        return $this->db->query($sql);
    }
    public function get_planet_GID($wvplid)
    {
        $sql = 'SELECT `gid` FROM `planet` WHERE wvplid=' . $wvplid;
        return $this->db->getOne($sql);
    }
    public function get_all_planets()
    {
        $sql = 'SELECT * FROM `planet`';
        return $this->db->getAll($sql);
    }

    /**
     * 获取全部用户的资产列表
     *
     * @return array
     */
    public function list_all_users_property()
    {
        $all = array();
        $template = array('title', 'subtitle', 'amount');
        $sql = 'SELECT `uid`,`username` FROM `user`';
        $result = $this->db->getAll($sql);
        foreach ($result as $r) {
            $temp = $this->get_all_of($r['uid']);
            foreach ($temp as &$t) {
                unset($t['aid'], $t['id'], $t['plid'], $t['wvplid'], $t['content'],  $t['gid'], $t['type'], $t['rarity'], $t['onsale'], $t['price'], $t['time'], $t['createtime'], $t['svg'],);
                $t = array_combine($template, $t);
            }
            array_push($all, ['username' => $r['username'], 'frenchfries' => $this->calculate_amount($this->get_wallet($r['uid'])), 'property' =>  $temp]);
        }
        return $all;
    }

    public function distribute_planet_card()
    {
        //获取爱发电支持者列表
        require('include/model/afdianModel.class.php');
        define('USERID', '5c86c6dc28fe11ecae1852540025c377');
        define('TOKEN', 'vfFSmTEg7j6M5bRNadAWxUGqeBurCY3X');
        define('PLAN_A', '74a87ca02c3311eca3a852540025c377');
        define('PLAN_B', '5bfe847a2c3211ec98fd52540025c377');
        define('PLANET_CARD', 168);

        $afdian = new Afdian(USERID, TOKEN);
        $all = $afdian->getAllSponsors();
        $a = $all["data"]["list"];
        $users = array();
        foreach ($a as $b) {
            if ((int)$b['all_sum_amount'] >= 600)
                array_push($users, $b['user']['user_id']);
        }
        /*  $all = $afdian->getAllOrders(); 
         foreach ($all['data']['list'] as $a) {
            if ($a['plan_id'] == PLAN_A || $a['plan_id'] == PLAN_B) {
                array_push($users, $a['user_id']);
            }
        }
        array_unique($users); */

        $sql = 'SELECT * FROM `user` WHERE `aifadian_id` IS NOT NULL';
        $result = $this->db->getALL($sql);
        $sent = array();
        foreach ($users as $u) {
            foreach ($result as $r) {
                if ($r['aifadian_id'] == $u && $r['planet_card_sent'] == 0) {
                    array_push($sent, 'sent a planet card: ' . $r['uid']);
                    $this->transfer(1, $r['uid'], PLANET_CARD, 1, 'PLANETCARDSENT');
                    $sql = 'UPDATE `user` SET `planet_card_sent`=1 WHERE `uid`=' . $r['uid'];
                    $this->db->query($sql);
                }
            }
        }
        return $sent;
    }
}
