<?php



/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */



global $_GPC;

global $_W;

if (!$this->is_weixin()) {

    message('请在微信中打开');

}

$level = $this->hotel_level;
  $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

    if($_GPC['op'] == 'display'){
        $condition=" and isbest=1 ";
    }else if($_GPC['op'] == 'chosen'){
        $condition=" and ischosen=1 ";
    }


 $list = pdo_fetchall('SELECT * FROM ' . tablename($this->room) . 'WHERE  
 status = 1  and uniacid =' . $this->_weid.$condition.' order by id desc ');
 $time = time();
$mtime = strtotime(date('Y-m-d', strtotime('+1 day')));
function cmpPrice($a, $b)
{
    return ($a['mprice'] < $b['mprice']) ? -1 : 1;
}

function cmpCredit($a, $b)
{
    return ($a['credit'] > $b['credit']) ? -1 : 1;
}


 foreach ($list as $k => $val) {
    $sql = 'SELECT count(*) as total FROM ' . tablename($this->order) . ' WHERE uniacid = ' . $this->_weid . 
    ' and hotelid =' . $val['hotelid'] . ' and roomid =' . $val['id'] . 
    ' and order_status !=4 and order_status !=5 and order_status !=6 and sintdate <=' . $time . ' and soutdate >=' . $mtime;
    $order_coune = pdo_fetch($sql);
    $list[$k]['ordercount'] = $order_coune['total'];
    $list[$k]['ccprice'] = $room[$k]['mprice'];
}

if($_GPC['desc']){
    if($_GPC['desc'] == '1'){
        usort($list, "cmpPrice");
    }
    if($_GPC['desc'] == '2'){
        usort($list, "cmpCredit");
    }
}

 include $this->template('best');
 exit;



