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



 $roomid=intval($_GPC['id']);


$level = $this->hotel_level;

$list = array();
$room = pdo_fetch('SELECT * FROM ' . tablename($this->room) . ' WHERE uniacid =' . $this->_weid . ' and id=' . $roomid .' and status = 1  ');

$hotelid = $room ['hotelid'];
$hotel = pdo_fetch('SELECT * FROM ' . tablename($this->hotel) . 'WHERE id=' . $hotelid . ' and status = 1 and uniacid =' . $this->_weid);



$hotel['level'] = $level[$hotel['level']];

$hotel['thumbs_count'] = count(iunserializer($hotel['thumbs'])) + 1;

$time = time();

$hotel['time'] = date('Y-m-d', $time);

$hotel['k'] = date('Y-m-d', $time);

$mtime = strtotime(date('Y-m-d', strtotime('+1 day')));

$hotel['mtime'] = date('Y-m-d', $mtime);

$hotel['m'] = date('Y-m-d', $mtime);



$devices = iunserializer($hotel['show_device']);



    $sql = 'SELECT count(*) as total FROM ' . tablename($this->order) . ' WHERE uniacid = ' . $this->_weid . 
    ' and hotelid =' . $room['hotelid'] . ' and roomid =' . $room['id'] . 
    ' and order_status !=4 and order_status !=5 and order_status !=6 and sintdate <=' . $time . ' and soutdate >=' . $mtime;

    $order_coune = pdo_fetch($sql);
    $room['ordercount'] = $order_coune['total'];
    $room['ccprice'] = $room['mprice'];

require_once IA_ROOT . "/addons/yc_hotelmanger/jssdk.php";

$signPackage = getSignPackage($_W['uniacid'], array("appsecret" => $_W['account']['secret'], "appid" => $_W['account']['key']));


 load()->model("mc"); 
    $uid= mc_openid2uid($this->openid);
    if(!$uid){
        $uid=$_W['fans']['uid'];
    }    
    $userjf= pdo_fetch('SELECT uid,credit3 FROM ' . tablename($this->members) . ' WHERE uniacid = ' . $this->_weid . ' and uid= '.$uid);
    $levelt=pdo_fetchall('SELECT levelname,ordercount,discount FROM ' . tablename($this->mlevel) . ' WHERE uniacid = ' . $this->_weid .' ORDER BY ordercount DESC'); 
    $zk='';
    foreach ($levelt as $key => $level) {
        if($level['ordercount']>$userjf['credit3']){
            continue;
        }else{
            $zk= $level['discount'];
            break;
        }
    } 
$zk=$zk==0?1:$zk;

include $this->template('detail');

