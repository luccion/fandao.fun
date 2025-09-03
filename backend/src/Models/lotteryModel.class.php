<?php
defined('ACC') || exit('ACC Denied');
class lotteryModel extends transferModel
{
    /**
     * 保存用户彩票
     * @param int $uid 用户ID
     * @param array $num 选号
     * @return bool
     */
    protected $lot = "lottery";
    protected $lotT = "lottery_tickets";

    public function save_ticket($uid, $num)
    {
        $arr = array();
        $arr['uid'] = $uid;
        $arr['num1'] = $num[0];
        $arr['num2'] = $num[1];
        $arr['num3'] = $num[2];
        $arr['num4'] = $num[3];
        return $this->db->autoExecute($this->lotT, $arr);
    }
    /**
     * 获取用户全部彩票数据
     * @param int $uid 用户ID
     * @return array
     */
    public function get_tickets($uid)
    {
        $sql = 'select num1,num2,num3,num4 from lottery_tickets where uid=' . $uid;

        return $this->db->getAll($sql);
    }
    /**
     * 获取本期彩票数量    
     * @return int
     */
    public function count_tickets()
    {
        $sql = 'select count(*) from lottery_tickets';
        return $this->db->getOne($sql);
    }

    /**
     * 获取历史彩票数据
     * @return array
     */
    public function get_history()
    {
        $sql = 'select * from lottery';
        $history = $this->db->getAll($sql);

        foreach ($history as &$h) {
            $h['winner'] = unserialize($h['winner']);
            $rate = explode('|', $h['summary']);
            $h['rate'] = round(($rate[2] + $rate[3] + $rate[4] + $rate[5]), 2) . "%";
        }
        return $history;
    }
    /**
     * 获取历史彩票数据中的emoji
     * @return array
     */
    public function get_history_emoji_count()
    {
        $sql = 'select num1,num2,num3,num4 from lottery';
        $historyEmoji = $this->db->getAll($sql);
        $str = "";
        foreach ($historyEmoji as $e) {
            $str .= implode("", $e);
        }
        $str = str_split($str, 4);
        $str = array_count_values($str);
        $map = array('🍺' => 0, '🍕' => 0, '🥑' => 0, '🍭' => 0, '🍟' => 0, '🍦' => 0, '🍉' => 0, '🍙' => 0, '🥐' => 0);
        $result = array_merge($map, $str);
        return $result;
    }

    /**
     * 获取当前用户中奖数据
     * @return array
     */
    public function get_user_prize($uid)
    {
        $sql = 'SELECT * FROM `lottery`ORDER BY `time` DESC LIMIT 1';
        $row = $this->db->getRow($sql);
        $winnerInfo = unserialize($row['winner']);
        $result = array();
        foreach ($winnerInfo as $w) {
            if ($w['u'] == $uid) {
                $result[$w['c']] = $w['p'];
            }
        }
        return $result;
    }

    /**
     * 清空彩票数据库
     * @return void
     */
    public static function purge_tickets()
    {
        $db = model::db();
        $sql = 'TRUNCATE TABLE lottery_tickets';
        $db->query($sql);
    }

    /**
     * 1. 随机得出本期乐透中奖号码
     * 2. 判定都有谁中奖中到什么程度
     * 3. 发放奖励
     * 3. 储存至数据库
     * @return 
     */
    public function check_lottery()
    {
        function compare($a, $b)
        {
            if ($a === $b) {
                return 0;
            }
            return ($a > $b) ? 1 : -1;
        }
        function emoji_calculate($array)
        {
            $num = [0, 1, 2, 3, 4, 5, 6, 7, 8];
            $slot = ['🍟' => 0, '🍕' => 0, '🥑' => 0, '🍭' => 0, '🍺' => 0, '🍉' => 0, '🥐' => 0, '🍙' => 0, '🍦' => 0];
            $count = array_count_values($array);
            return array_combine($num, array_merge($slot, $count));
        }

        /* start 定义 */
        $rows = 4;     //老虎机排数
        $pool = $this->calculate_amount('lottery');  //奖池总数
        $p0 = 200;          //特等奖最低限度
        $p0_rate = 0.9;     //特等奖资金池比率
        $p1 = 100;          //一等奖
        $p1_rate = 0.5;     //一等奖资金池比率
        $p2 = 6;
        $p3 = 4;

        $lottery = array();
        $winner = array();
        $arr = array();
        $arr['none'] = 0;
        $arr['once'] = 0;
        $arr['twice'] = 0;
        $arr['threeTimes'] = 0;
        $arr['fourTimes'] = 0;
        $arr['fullHouse'] = 0;
        $need = 0; //需要池
        $loot = array(); //结算池
        $loot['prize0'] = 0;
        $loot['prize1'] = 0;
        $loot['prize2'] = 0;
        $loot['prize3'] = 0;
        $lotteryStr = "";
        $lotteryArray = array();
        $slot = [0, 0, 0, 0, 0, 0, 0, 0, 0];
        $emoji = ['🍟', '🍕', '🥑', '🍭', '🍺', '🍉', '🥐', '🍙', '🍦'];
        /* end 定义 */

        for ($i = 1; $i <= 4; $i++) {
            $p = random_int(0, 8);
            $slot[$p] += 1;
            $lotteryStr .= $emoji[$p];
            $lotteryArray[$i - 1] = $emoji[$p];
        }
        $lotteryCalc = emoji_calculate($lotteryArray);
        for ($i = 1; $i <= 4; $i++) {
            $lottery["num" . $i] = $lotteryArray[$i - 1];                      //制造lottery数组用于最后加入数据库
        }

        /* 获取全部彩票 */
        $sql = 'select * from lottery_tickets';
        $all = $this->db->getAll($sql);

        foreach ($all as $one) {
            $ticketArray = array();                                            //删除$one数组中的用户id等
            for ($c = 0; $c < $rows; $c++) {
                $ticketArray['num' . ($c + 1)] = $one['num' . ($c + 1)];
            }
            $ticketStr = implode("", $ticketArray);                           //获取字符串如"🍟🍟🍟🍟"
            $ticketCalc = emoji_calculate($ticketArray);                      //获取calc   

            if ($ticketStr === $lotteryStr) {                                 //特等奖！
                $block = array('u' => $one['uid'], 'c' => $ticketStr, 'p' => 0);
                array_push($winner, $block);
                $arr['fullHouse']++;
            } else {                                                          //其他奖
                $time = 0;
                for ($i = 0; $i < 9; $i++) {
                    if ($ticketCalc[$i] <= $lotteryCalc[$i]) {
                        $time += $ticketCalc[$i];
                    } else {
                        $time += $lotteryCalc[$i];
                    }
                }
            }
            switch ($time) {
                case 0:      //无奖       
                    $arr['none']++;
                    break;
                case 1:     //无奖
                    $arr['once']++;
                    break;
                case 2:     //三等奖 
                    $block = array('u' => $one['uid'], 'c' => $ticketStr, 'p' => 3);
                    array_push($winner, $block);
                    $arr['twice']++;
                    break;
                case 3:     //二等奖
                    $block = array('u' => $one['uid'], 'c' => $ticketStr, 'p' => 2);
                    array_push($winner, $block);
                    $arr['threeTimes']++;
                    break;
                case 4:     //一等奖
                    $block = array('u' => $one['uid'], 'c' => $ticketStr, 'p' => 1);
                    array_push($winner, $block);
                    $arr['fourTimes']++;
                    break;
            }
        }
        /* payment */

        if ($arr['fullHouse'] != 0) {
            if (($pool * $p0_rate / $arr['fullHouse']) <= $p0 || $pool == 0) {
                $need += abs($pool - ($arr['fullHouse'] * $p0));
                $loot['prize0'] = $arr['fullHouse'] * $p0;
                $pool -= $loot['prize0'];
            } else {
                $loot['prize0'] = $pool *  $p0_rate;
                $pool -= $loot['prize0'];
            }
            if ($pool <= 0) {
                $pool = 0;
            }
        }
        if ($arr['fourTimes'] != 0) {
            if (($pool * $p1_rate / $arr['fourTimes']) <= $p1 || $pool == 0) {
                $need += abs($pool - ($arr['fourTimes'] * $p1));
                $loot['prize1'] = $arr['fourTimes'] * $p1;
                $pool -= $loot['prize1'];
            } else {
                $loot['prize1'] = $pool * $p1_rate;
                $pool -= $loot['prize1'];
            }
            if ($pool <= 0) {
                $pool = 0;
            }
        }
        if ($arr['threeTimes'] != 0) {
            if ($arr['threeTimes'] * $p3 > $pool) {
                $need += abs($pool - ($arr['threeTimes'] * $p2));
            } else {
                $loot['prize2'] = $arr['threeTimes'] * $p2;
                $pool -= $loot['prize2'];
            }
            if ($pool <= 0) {
                $pool = 0;
            }
        }
        if ($arr['twice'] != 0) {
            if ($arr['twice'] * $p3 > $pool) {
                $need += abs($pool - ($arr['twice'] * $p3));
            } else {
                $loot['prize3'] = $arr['twice'] * $p3;
                $pool -= $loot['prize3'];
            }
        }

        if ($need) {
            $this->issue_to(115, $need, "lottery");
        }
        foreach ($winner as $s) {
            switch ($s['p']) {
                case 0:            //特等奖 200                   
                    $this->lottery_issue_to($s['u'], ($loot['prize0'] / $arr['fullHouse']), "lottery@0");
                    break;
                case 1:             //一等奖 100
                    $this->lottery_issue_to($s['u'], ($loot['prize1'] / $arr['fourTimes']), "lottery@1");
                    break;
                case 2:             //二等奖 3                     
                    $this->lottery_issue_to($s['u'], $p2, "lottery@2");
                    break;
                case 3:             //安慰奖 1               
                    $this->lottery_issue_to($s['u'], $p3, "lottery@3");
                    break;
            }
        }
        if ($pool < 500) {
            $target = 500 - $pool;
            $this->issue_to(115, $target, "lottery");
        }

        /* 汇款结束 */
        $lottery['winner'] = serialize($winner);
        /* 统计 */
        $SUM = array_sum($arr) ?: 1;
        $rate[0] = 100 * $arr['none'] / $SUM . '%';
        $rate[1] = 100 * $arr['once'] / $SUM  . '%';
        $rate[2] = 100 * $arr['twice'] / $SUM  . '%';
        $rate[3] = 100 *  $arr['threeTimes'] / $SUM  . '%';
        $rate[4] = 100 *  $arr['fourTimes'] / $SUM  . '%';
        $rate['fullHouse'] = 100 *   $arr['fullHouse'] / $SUM  . '%';
        $lottery['summary'] = implode("|", $rate);
        $lottery['time'] = time();
        $lottery['count'] = $this->count_tickets();
        return $this->db->autoExecute($this->lot, $lottery);
    }
}
