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

$hotelName = $_GPC['hotelName'];

$cityName = $_GPC['cityName'];

$search = $_GPC['search'];

$singlehotel   = pdo_fetch("select * from " . tablename($this->hotelmanager) . " where status=1 and uniacid= '{$_W['uniacid']}' ");
if(!empty($singlehotel)){
	$url =$this->createMobileUrl('hotelindex',array('hotelid'=> $singlehotel['hotelid']));
	Header("Location:$url");
	exit;
}

if($cityName){
    $hotel = pdo_fetchall('SELECT * FROM ' . tablename($this->hotel) . 'WHERE  status = 1 and uniacid =' . $this->_weid.' and city like \'%'.$hotelName.'%\' ');
    include $this->template('nearHotel');
    exit;
}else if($hotelName){
   // $hotelName = '%'.$hotelName.'%';
    $hotel = pdo_fetchall('SELECT * FROM ' . tablename($this->hotel) . 'WHERE  status = 1 and uniacid =' . $this->_weid.' and title like \'%'.$hotelName.'%\' ');
   
}else if($search){
    $hotel = pdo_fetchall('SELECT * FROM ' . tablename($this->hotel) . 'WHERE  status = 1 and uniacid =' . $this->_weid.' and city like \'%'.$search.'%\' ');
    $hotels = pdo_fetchall('SELECT * FROM ' . tablename($this->hotel) . 'WHERE  status = 1 and uniacid =' . $this->_weid.' and title like \'%'.$search.'%\' ');
   /* if(!empty($hotel)){
		$hotel['search'] = $search;
	}
	if(!empty($hotels)){
		$hotels['search'] = $search;
	}*/
    include $this->template('nearHotel');
    exit;
}else{
    $hotel = pdo_fetchall('SELECT * FROM ' . tablename($this->hotel) . 'WHERE  status = 1 and uniacid =' . $this->_weid);
    
}

// 广告开始
$advs      = pdo_fetchall("select * from " . tablename($this->adv) . " where enabled=1 and uniacid= '{$_W['uniacid']}' and type=1 order by displayorder asc");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = $adv['link'];
			}
		}
		unset($adv);
		$advs2      = pdo_fetchall("select * from " . tablename($this->adv) . " where enabled=1 and uniacid= '{$_W['uniacid']}' and type=2 order by displayorder asc");
		if($advs2){
		foreach ($advs2 as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = $adv['link'];
			}
		}
		unset($adv);
		}
		$advs3      = pdo_fetchall("select * from " . tablename($this->adv) . " where enabled=1 and uniacid= '{$_W['uniacid']}' and type=3 order by displayorder asc");
		if($advs3){
		foreach ($advs3 as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = $adv['link'];
			}
		}
		unset($adv);	
		}

        // 广告结束
    $xsms = pdo_fetchall('SELECT * FROM ' . tablename($this->room) . 'WHERE
 status = 1 
 and uniacid =' . $this->_weid . '
 and istime =1  
 and ' . time() . ">=timestart and " . time() . '<=timeend');
    include $this->template('index');
    exit;




