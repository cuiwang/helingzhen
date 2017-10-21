<?php
 global $_W,$_GPC;
 $table_price = 'meepo_lottery_images';
 load()->func('db');
 load()->func('pdo');
 $sql = ' SELECT * FROM '.tablename($table_price).'WHERE images_status = :status AND uniacid = :uniacid ORDER BY images_number asc';
 $params = array(
 	':status' => 0,
    ':uniacid' =>$_W['uniacid']
 );
 $result_price = pdo_fetchall($sql,$params);
  
 $index =array();
 $percent =array();
 foreach($result_price as $key=>$val){
 	if($result_price[$key]['price_status']==1 && $result_price[$key]['images_status'] == 0){
 		array_push($index,$result_price[$key]['images_number']-1);
 		array_push($percent,$result_price[$key]['price_percent']);
 	}
 }


array_unshift($percent,0); 
class Lottery {
    /*
     * 中奖概率数组，自动判断奖项数目
     * 数组键值和为100，自动计算出不中奖的概率，若初始是超过100抛出一个错误
     */
 
    protected $_rate = array();
 
    /*
     * 设置中奖概率，
     * @param Array,中奖概率，以数组形式传入
     */
 
    public function setRate($rate = array(12.1, 34)) {
        $this->_rate = $rate;
        if (array_sum($this->_rate) > 100)//检测概率设置是否有问题
            throw new Exception('Winning rate upto 100%');
        if (array_sum($this->_rate) < 100)
        //定义未中奖情况的概率，用户给的概率只和为100时，则忽略0
            $this->_rate[] = 100 - array_sum($this->_rate);
    }
 
    /*
     * 随机生成一个1-10000的整数种子，提交给中奖判断函数
     * @return int,按传入的概率排序，返回中奖的项数
     */
 
    public function runOnce() {
        return $this->judge(mt_rand(0, 10000));
    }
 
    /*
     * 按所设置的概率，判断一个传入的随机值是否中奖
     * @param int,$seed 10000以内的随机数
     * @return int,$i 按传入的概率排序，返回中奖的项数
     */
 
    protected function judge($seed) {
        foreach ($this->_rate as $key => $value) {
            $tmpArr[$key + 1] = $value * 100;
        }
        //将概率乘十后累计，以便随机选择，组合成
        $tmpArr[0] = 0;
        foreach ($tmpArr as $key => $value) {
            if ($key > 0) {
                $tmpArr[$key] += $tmpArr[$key - 1];
            }
        }
        for ($i = 1; $i < count($tmpArr); $i++) {
            if ($tmpArr[$i - 1] < $seed && $seed <= $tmpArr[$i]) {
                return $i; //返回中奖的项数（按概率的设置顺序）
            }
        }
    }
 
}
// print_r($percent);
$a = new Lottery;
$a->setRate($percent);

$b = $a->runOnce();//返回中奖项数
    // @$rewards[$b]++;

$price_index = $index[$b-2];
die(json_encode($price_index));
// $price_index = $price_index +1 ;
// echo $b;

