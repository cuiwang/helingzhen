<?php
/**
 * 保存洗车订单
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
$weid = $this->_weid;
$from_user = $_GPC['from_user'];
$this->_fromuser = $from_user;

$storeid    = intval($_GPC['storeid']);
$guest_name = trim($_GPC['guest_name']); //姓名
$tel        = trim($_GPC['tel']); //电话
$mycard     = trim($_GPC['mycard']); //洗车车牌
$meal_date  = trim($_GPC['meal_date']); //洗车日期
$meal_time  = trim($_GPC['meal_time']); //预约时间
$address    = trim($_GPC['address']); //地址
$remark     = trim($_GPC['remark']); //备注
$lng        = trim($_GPC['lng']); //坐标经度
$lat        = trim($_GPC['lat']); //坐标纬度
$usecard    = trim($_GPC['usecard']); //1.使用洗车卡

if (empty($from_user)) {
	$this->showMessageAjax('请重新发送关键字进入系统!');
}
if (empty($storeid)) {
	$this->showMessageAjax('请先选择服务点!');
}


//检查服务点订单是否已满
$meal_date = strtotime($meal_date); //预约日期
$strtime = explode("~", $meal_time);
$starttime = $meal_date + $strtime[0] * 3600; //预约时间段开始时间戳
$this->checkorder($meal_date, $starttime, $storeid, $type='new',$order);

//查询购物车
$where = " a.weid='{$weid}' AND a.from_user='{$from_user}' AND a.total>0 ";
if($setting['store_model']==1){
	$where .= " AND a.storeid=0 ";
}elseif($setting['store_model']==2){
	$where .= " AND a.storeid='{$storeid}' ";
}
$cart = pdo_fetchall("SELECT * FROM " . tablename($this->table_cart) . " a LEFT JOIN " . tablename($this->table_goods) . " b ON a.goodsid=b.id WHERE {$where}");


if (empty($cart)) { //购物车为空
	$this->showMessageAjax('您还没有选择服务项目!');
} else {
	$goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE id IN ('" . implode("','", array_keys($cart)) . "')");
}

//更新粉丝信息
fans_update($from_user, array('realname' => $guest_name, 'address' => $address));
//用户信息判断
if (empty($guest_name)) {
	$this->showMessageAjax('请输入用户姓名!');
}
if (empty($tel)) {
	$this->showMessageAjax('请输入手机号码!');
}
if (empty($mycard)) {
	$this->showMessageAjax('请输入洗车车牌!');
}
if (empty($address)) {
	$this->showMessageAjax('请输入洗车地址!');
}

//服务点信息
$store = pdo_fetch("SELECT * FROM " . tablename($this->table_store) . " WHERE weid=:weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $storeid));

/* 判断上门洗车地址距离*/
if($store['store_type']==1){
	if($store['map_type']==1){
		//腾讯地图模式
		$distance = $this->getDistance($lng,$lat,$store['txlat'],$store['txlng']);
	}elseif($store['map_type']==2){
		//百度地图模式
		$distance = $this->getDistance($lng,$lat,$store['lng'],$store['lat']);
	}

	if($distance > intval($store['radius'])){
		$this->showMessageAjax('您的洗车地址已超出服务范围！');
	}
}

//查询用户信息
$user = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " WHERE uid=:uid", array(':uid' => $_W['fans']['uid']));

//保存新订单
$totalnum = 0;
$totalprice = 0;
$goodsprice = 0;
$totalintegral = 0;

foreach ($cart as $value) {
	$totalnum = $totalnum + intval($value['total']);
	$goodsprice = $goodsprice + (intval($value['total']) * floatval($value['price']));
	$totalintegral = $totalintegral + $value['integral'];
}

$totalprice = $goodsprice;

$fansid = $_W['fans']['id'];
$data = array(
	'weid' => $weid,
	'storeid' => $storeid,
	'usecard' => $usecard,
	'from_user' => $from_user,
	'order_type' => $store['store_type'],
	'ordersn' => date('md') . sprintf("%04d", $fansid) . random(4, 1), //订单号
	'totalnum' => $totalnum, //产品数量
	'totalprice' => $totalprice, //总价
	'totalintegral' => $totalintegral, //赠送总积分
	'status' => 0, //订单状态
	'paytype' => 0, //付款类型
	'username' => $guest_name, //用户名
	'address' => $address, //地址
	'lng' => $_GPC['lng'], //地址经度
	'lat' => $_GPC['lat'], //地址纬度
	'map_type' => $store['map_type'], //地图模式
	'tel' => $tel,
	'mycard' => $mycard, //车牌号码
	'meal_date' => $meal_date, //预约日期
	'meal_time' => $meal_time, //预约时间段
	'meal_timestamp' => $starttime, //预约时间段开始时间戳			
	'remark' => $remark, //备注
	'dateline' => time(),
);

//保存订单
pdo_insert($this->table_order, $data);
$orderid = pdo_insertid();

//保存新订单商品
foreach ($cart as $row) {
	if (empty($row) || empty($row['total'])) {
		continue;
	}

	//用户使用洗车卡支付
	if($usecard==1){
		//查询用户洗车卡
		if(!empty($row['onlycard'])){
			$membercard =  pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid AND onlycard=:onlycard AND number>0 AND validity>:validity", array(':uid' => $user['uid'], ':weid' => $weid, 'onlycard'=>$row['onlycard'], ':validity'=>time()));
		}
	}

	$success = pdo_insert($this->table_order_goods, array(
		'weid'     => $_W['uniacid'],
		'storeid'  => $storeid,
		'orderid'  => $orderid,
		'goodsid'  => $row['goodsid'],
		'title'    => $row['title'],
		'price'    => $membercard['price']?$membercard['price']:$row['price'],
		'integral' => $row['integral'],
		'total'    => $row['total'],
		'onlycard' => $membercard['onlycard'],
		'dateline' => TIMESTAMP,
	));
	$order_goodsid = pdo_insertid();
	if($success){
		if($usecard==1 && !empty($membercard)){
			$condition = array(
				'uid'      => $user['uid'],
				'weid'     => $weid,
				'onlycard' => $membercard['onlycard'],
			);
			pdo_update($this->table_member_onecard, array('number'=>$membercard['number']-1), $condition);
			$cardcount += $row['price'];

			//添加会员洗车卡明细
			$onecard_record = array(
				'weid'      => $weid,
				'uid'       => $user['uid'],
				'openid'    => $from_user,
				'title'     => $membercard['title'],
				'reduce'    => '-1',
				'total'     => $membercard['number']-1,
				'remark'    => "洗车下单[".$data['ordersn']."]",
				'add_time'  => time(),
			);
			pdo_insert($this->table_onecard_record, $onecard_record);
		}elseif($usecard==1 && empty($membercard) && $row['free_card']=='1' && $user['free_number']=='1'){
			pdo_update('mc_members', array('free_number'=>'0'), array('uid'=>$user['uid']));
			pdo_update($this->table_order_goods, array('onlycard'=>'free_card'), array('id'=>$order_goodsid));
			$cardcount += $row['price'];
		}
	}
	unset($membercard);
}
//更新订单总价 = 原价-洗车卡支付金额
pdo_update($this->table_order, array('totalprice'=>$totalprice-$cardcount), array('id'=>$orderid));

//清空购物车
$clearstoreid = $setting['store_model']==1?'0':$storeid;
pdo_delete($this->table_cart, array('weid' => $weid, 'from_user' => $from_user, 'storeid'=>$clearstoreid));
$result['orderid'] = $orderid;
$result['code'] = $this->msg_status_success;
$result['msg'] = '操作成功';
message($result, '', 'ajax');