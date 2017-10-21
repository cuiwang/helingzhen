<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * goods.ctrl
 * 商品详情控制器
 */
defined('IN_IA') or exit('Access Denied');
wl_load()->model('goods');
wl_load()->model('merchant');
wl_load()->model('order');
wl_load()->model('account');
load()->func('communication');

$ops = array('display', 'post');
$op = in_array($op, $ops) ? $op : 'display';

$pagetitle = '订单提交';
$openid = $_W['openid'];
session_start();

$id = $_SESSION['goodsid'] =  isset($_GPC['id']) ? intval($_GPC['id']) : $_SESSION['goodsid'];
$tuan_id = $_SESSION['tuan_id'] = isset($_GPC['tuan_id']) ? intval($_GPC['tuan_id']) : $_SESSION['tuan_id'];
$groupnum = $_SESSION['groupnum'] = isset($_GPC['groupnum']) ? intval($_GPC['groupnum']) : $_SESSION['groupnum'];
$optionid = $_SESSION['optionid'] = isset($_GPC['optionid']) ? intval($_GPC['optionid']) : $_SESSION['optionid'];
if(empty($_GPC['num'])){
	$_GPC['num'] = 1;
}
$_SESSION['num'] = $num = isset($_GPC['num']) ? intval($_GPC['num']) : $_SESSION['num'];
$addrid = isset($_GPC['addrid']) ? intval($_GPC['addrid']) : 0;

//查询这个团是否支付成功参加
if($tuan_id){
	$myorder = order_get_by_params(" tuan_id = {$tuan_id} and openid = '{$openid}' and status in(1,2,3,4,6,7) ");
	if(!empty($myorder)){
		$tuan_id = '';
	}
}

//判断商品id
if (!empty($id)) {
	$goods = goods_get_by_params(" id = {$id} ");
} else {
	message('抱歉出错了哦，请返回重试！');
}

//阶梯团和规格的判断
if($goods['group_level_status']==2){
	$param_level = unserialize($goods['group_level']);
	foreach($param_level as$k=>$v){
		if($groupnum == $v['groupnum']){
			$goods['gprice'] = $v['groupprice'];
			break;
		}
	}		
}elseif($goods['hasoption']==1){
	if (!empty($optionid)) {
		$option = pdo_fetch("select title,productprice,marketprice from" . tablename("tg_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
	} else {
		wl_message('抱歉出错了哦，请返回重试！');
	}
	$goods['gprice'] = $option['marketprice'];
	$goods['oprice'] = $option['productprice'];
	$goods['optionname'] = $option['title'];
}

//判断团长优惠
if($goods['is_discount'] == 0){
	if(empty($goods['firstdiscount'])){
		$goods['firstdiscount'] = 0;
	}
	$firstdiscount = $goods['firstdiscount'];
}
if(!empty($groupnum)){
	if($groupnum > 1){
		$price = $goods['gprice'];
		$is_tuan = 1;
		if(empty($tuan_id)){
			$tuan_first = 1;
		}else{
			$tuan_first = 0;
			$firstdiscount = 0;
		}
	}else{
		$price = $goods['oprice'];
		$is_tuan = 0;
		$tuan_first = 0;
		$firstdiscount=0;
	}
} else {
	wl_message('抱歉出错了哦，请返回重试！');
}

//	$data = order_get_list(array('openid' => $openid,'status' => '1,2,3,4,5'));
//	$times = $data['total'];

/*判断地区邮费*/
$freight=0;
if($addrid){
	$adress_fee = pdo_fetch("select * from ".tablename('tg_address')."where id = '{$addrid}'");
}else{
	$adress_fee = pdo_fetch("select * from ".tablename('tg_address')."where openid = '$openid' and status = 1");
}
//if(empty($adress_fee)){
//	header("location:".app_url('address/createadd'));
//}
$p = $adress_fee['province'];
$c = $adress_fee['city'];
$d = $adress_fee['county'];

$province_fee = pdo_fetch("select first_fee from " . tablename("tg_delivery_price") . " WHERE  province = '{$p}' and template_id={$goods['yunfei_id']}");
$city_fee = pdo_fetch("select first_fee from " . tablename("tg_delivery_price") . " WHERE  province = '{$p}' and  city = '{$c}'  and template_id={$goods['yunfei_id']}");
$district_fee = pdo_fetch("select first_fee from " . tablename("tg_delivery_price") . " WHERE  province = '{$p}' and  city = '{$c}' and district='{$d}' and template_id={$goods['yunfei_id']}");
if(!empty($province_fee['first_fee'])){
	$freight = $province_fee['first_fee'];
}
if(!empty($city_fee['first_fee'])){
	$freight = $city_fee['first_fee'];
}
if(!empty($district_fee['first_fee'])){
	$freight = $district_fee['first_fee'];
}

//支付价格
$pay_price = sprintf("%.2f", $price * $num + $freight - $firstdiscount);

if ($_W['isajax']) {
	$typeid = intval($_GPC['typeid']);
	$str='';
	
	if($typeid == 1){
		$is_hexiao = 0;
	}elseif($typeid == 2){
		$is_hexiao = 1;
		$chars = '0123456789';
		for ($i = 0; $i < 8; $i++) {
			$str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		$adress_fee['cname'] = $_GPC['name'];
		$adress_fee['tel'] = $_GPC['mobile'];
		$bdeltime = strtotime($_GPC['gettime']);
		$pay_price = $pay_price - $freight;
		$freight = 0;
	}

	$data = array(
		'uniacid'     => $_W['uniacid'],
		'gnum'        => $num,
		'openid'      => $openid,
        'ptime'       => '',//支付成功时间
		'orderno'     => date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99)),
		'pay_price'   => $pay_price,
		'goodsprice'  => $price*$num,
		'freight'     => $freight,
		'status'      => 0,//订单状态：0未支,1支付，2待发货，3已发货，4已签收，5已取消，6待退款，7已退款
		'addressid'   => $adress_fee['id'],
		'addresstype' => $adress_fee['type'],//1公司2家庭
		'addname'     => $adress_fee['cname'],
		'mobile'      => $adress_fee['tel'],
		'address'     => $adress_fee['province'].$adress_fee['city'].$adress_fee['county'].$adress_fee['detailed_address'],
		'g_id'        => $id,
		'tuan_id'     => $tuan_id,
		'is_tuan'     => $is_tuan,
		'tuan_first'  => $tuan_first,
		'starttime'   => TIMESTAMP,
		'remark'      => $_GPC['remark'],
		'endtime'     => $goods['endtime'],
		'is_hexiao'   => $is_hexiao,
		'hexiaoma'    => $str,
		'credits'     => $goods['credits'],
		'optionname'  => $goods['optionname'],
		'optionid'    => $optionid,
		'is_usecard'  => 0,
		'createtime'  => TIMESTAMP,
		'bdeltime'    => $bdeltime,
		'merchantid'  => $goods['merchantid']
	);
	pdo_insert('tg_order', $data);
	$orderid = pdo_insertid();
	
	if($typeid == 2){
		/*二维码*/
		wl_load()->classs('qrcode');
		$createqrcode =  new creat_qrcode();
		$createqrcode->creategroupQrcode($data['orderno']);
	}
	
	if(empty($tuan_id)){
		$groupnumber = $orderid;
		if($data['is_tuan']==1){
			$starttime = time();
			$endtime = $starttime + $goods['endtime']*3600;
			$data2 = array(
				'uniacid'     => $_W['uniacid'],
				'groupnumber' => $groupnumber,
				'groupstatus' => 3,//订单状态,1组团失败，2组团成功，3,组团中
				'goodsid'     => $goods['id'],
				'goodsname'   => $goods['gname'],
				'neednum'     => $groupnum,
				'lacknum'     => $groupnum,
				'starttime'   => $starttime,
				'endtime'     => $endtime,
				'price'       => $price,
				'merchantid'  => $goods['merchantid']
			);
			pdo_insert('tg_group', $data2);
			pdo_update('tg_order',array('tuan_id' => $orderid), array('id' => $orderid));
		}
	}
	
	$params['ordersn'] = $data['orderno'];
	$params['pay_price'] = $data['pay_price'];
	wl_json(1,$params);
}
include wl_template('order/order_confirm');
