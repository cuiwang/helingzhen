<?php
global $_W, $_GPC;
$merchant = $this->checkmergentauth();
$express = array(
	0=>array(
		'pinyin'=>'yunda',
		'value'=>'韵达快递',
	),
	1=>array(
		'pinyin'=>'yuantong',
		'value'=>'圆通速递',
	),
	2=>array(
		'pinyin'=>'shentong',
		'value'=>'申通速递',
	),
	3=>array(
		'pinyin'=>'shunfeng',
		'value'=>'顺丰速递',
	),
	4=>array(
		'pinyin'=>'tiantian',
		'value'=>'天天快递',
	),
	5=>array(
		'pinyin'=>'youzhengguonei',
		'value'=>'邮政包裹',
	),
	6=>array(
		'pinyin'=>'ems',
		'value'=>'中通快递',
	),
	7=>array(
		'pinyin'=>'zhongtong',
		'value'=>'EMS',
	),
	8=>array(
		'pinyin'=>'quanfengkuaidi',
		'value'=>'全峰快递',
	),
	9=>array(
		'pinyin'=>'huitongkuaidi',
		'value'=>'百世快递',
	),
);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$total1 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 0");
	$allpage1 = ceil($total1/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$merchantorderlist1 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 0 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($merchantorderlist1 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$merchantorderlist1[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$merchantorderlist1[$k]['goods'] = $goods;
	}
	
	$total2 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 1");
	$allpage2 = ceil($total2/10)+1;
	$merchantorderlist2 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 1 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($merchantorderlist2 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$merchantorderlist2[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$merchantorderlist2[$k]['goods'] = $goods;
	}
	
	$total3 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 2");
	$allpage3 = ceil($total3/10)+1;
	$merchantorderlist3 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 2 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($merchantorderlist3 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$merchantorderlist3[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$merchantorderlist3[$k]['goods'] = $goods;
	}
	
	$total4 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 4");
	$allpage4= ceil($total4/10)+1;
	$merchantorderlist4 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 4 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($merchantorderlist4 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$merchantorderlist4[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$merchantorderlist4[$k]['goods'] = $goods;
	}
	
	$total5 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 5");
	$allpage5= ceil($total5/10)+1;
	$merchantorderlist5 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND merchant_id = {$merchant['id']} AND status = 5 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($merchantorderlist5 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$merchantorderlist5[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$merchantorderlist5[$k]['goods'] = $goods;
	}
	
	$isajax = intval($_GPC['isajax']);
	if($isajax == 1){
		$status = intval($_GPC['status']);
		if($status == 0){
			$merchantorderlist = $merchantorderlist1;
			$statustext = '等待买家付款';
		}
		if($status == 1){
			$merchantorderlist = $merchantorderlist2;
			$statustext = '待发货/核销';
		}
		if($status == 2){
			$merchantorderlist = $merchantorderlist3;
			$statustext = '等待买家收货';
		}
		if($status == 4){
			$merchantorderlist = $merchantorderlist4;
			$statustext = '待评价';
		}
		if($status == 5){
			$merchantorderlist = $merchantorderlist5;
			$statustext = '已完成';
		}
		$html = '';
		foreach($merchantorderlist as $k=>$v){
			if($v['status'] == 1){
				if($v['ishexiao'] == 1){
					$statustext = '待核销';
				}else{
					$statustext = '待发货';
				}
			}
			$html .= '<div class="item">
						<div class="top">
							<div class="storename textellipsis1">店铺：'.$merchant['name'].'</div>
							<div class="status text-r">'.$statustext.'</div>
							<div class="ordersn">订单编号：'.$v['ordersn'].'</div>
						</div>
						<a href="'.$this->createMobileUrl('detail',array('id'=>$v['goods']['id'])).'">
						<div class="goods">
							<div class="img"><img src="'.tomedia($v['goods']['thumb']).'" /></div>
							<div class="goodsmsg">
								<div class="title textellipsis1">'.$v['goods']['title'].'</div>
								<div class="option">'.$v['ordergoods']['optionname'].'</div>
								<div class="pricenum">
									<div class="price">￥'.$v['ordergoods']['price'].'</div>
									<div class="num text-r">×'.$v['ordergoods']['total'].'</div>
								</div>
							</div>
						</div>
						</a>
						<div class="fee text-r">合计：<span class="c-orange">￥'.$v['price'].'</span></div>
						<div class="btns">
							<a href="'.$this->createMobileUrl('merchantorder',array('op'=>'detail','id'=>$v['id'])).'" class="pay text-c">订单详情</a>
						</div>
					</div>';
		}
		echo $html;
		exit;
	}else{
		include $this->template('merchantorder');
	}
} elseif ($operation == 'detail') {
	$id = intval($_GPC['id']);
	$item = pdo_fetch("SELECT * FROM ".tablename('cygoodssale_order')." WHERE id = {$id} AND merchant_id = {$merchant['id']} AND weid = {$_W['uniacid']}");
	if (empty($item)) {
		message("抱歉，您没有该订单!", referer(), "error");
	}
	// 收货地址信息
	$item['user'] = explode('|', $item['address']);
	//商品信息
	$goods = pdo_fetch("SELECT g.*, o.total,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('cygoodssale_order_goods') .
			" o left join " . tablename('cygoodssale_goods') . " g on o.goodsid = g.id " . " WHERE o.orderid = {$id}");
	$thumbs = unserialize($goods['thumb_url']);
	$goods['thumb'] = $thumbs[0];
	include $this->template('merchantorderdetail');
}elseif($operation == 'fahuo'){
	$id = intval($_GPC['id']);
	$item = pdo_fetch("SELECT * FROM ".tablename('cygoodssale_order')." WHERE id = {$id} AND merchant_id = {$merchant['id']} AND weid = {$_W['uniacid']} AND (status = 1 OR status = 2)");
	if (empty($item)) {
		message("抱歉，您没有需要发货的订单!", referer(), "error");
	}
	if ($_GPC['confirmsend'] == 1) {
		if (empty($_GPC['expresscom']) || empty($_GPC['express']) || empty($_GPC['expresssn'])) {
			$resArr['error'] = 1;
			$resArr['message'] = '请选择选择快递公司并且输入快递单号！';
			echo json_encode($resArr);
			exit();
		}
		if($item['status'] != 1 && $item['status'] != 2){
			$resArr['error'] = 1;
			$resArr['message'] = '订单当前状态不能使用该操作！';
			echo json_encode($resArr);
			exit();
		}
		pdo_update(
			'cygoodssale_order',
			array(
				'status' => 2,
				'express' => $_GPC['express'],
				'expresscom' => $_GPC['expresscom'],
				'expresssn' => $_GPC['expresssn'],
			),
			array('id' => $id)
		);
		$tpllist = pdo_fetch("SELECT id FROM".tablename('cygoodssale_tplmessage_tpllist')." WHERE tplbh = 'OPENTM200565259' AND uniacid = {$_W['uniacid']}");
		$setting = $this->setting;
		if(!empty($tpllist) && $setting['istplon'] == 1){
			$arrmsg = array(
				'openid'=>$item['from_user'],
				'topcolor'=>'#980000',
				'first'=>'订单发货通知',
				'firstcolor'=>'',
				'keyword1'=>$item['ordersn'],
				'keyword1color'=>'',
				'keyword2'=>$_GPC['express'],
				'keyword2color'=>'',
				'keyword3'=>$_GPC['expresssn'],
				'keyword3color'=>'',
				'remark'=>'',
				'remarkcolor'=>'',
				'url'=>$_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("myorder",array('merchant_id'=>$item['merchant_id'],'status'=>2))),
			);
			$this->sendtemmsg($tpllist['id'],$arrmsg);
		}
		$resArr['error'] = 0;
		$resArr['message'] = '操作成功！';
		echo json_encode($resArr);
		exit();
	}
	include $this->template('merchantorderfahuo');
}
?>