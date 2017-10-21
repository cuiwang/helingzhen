<?php
global $_W, $_GPC;
$member['openid'] = $_W['fans']['from_user'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {	
	$total1 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 0");
	$allpage1 = ceil($total1/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$myorderlist1 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 0 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($myorderlist1 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$myorderlist1[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$myorderlist1[$k]['goods'] = $goods;
	}
	
	$total2 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 1");
	$allpage2 = ceil($total2/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$myorderlist2 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 1 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($myorderlist2 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$myorderlist2[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$myorderlist2[$k]['goods'] = $goods;
	}
	
	$total3 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 2");
	$allpage3 = ceil($total3/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$myorderlist3 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 2 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($myorderlist3 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$myorderlist3[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$myorderlist3[$k]['goods'] = $goods;
	}
	
	$total4 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 4");
	$allpage4 = ceil($total4/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$myorderlist4 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 4 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($myorderlist4 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$myorderlist4[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$myorderlist4[$k]['goods'] = $goods;
	}
	
	$total5 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 5");
	$allpage5 = ceil($total5/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$myorderlist5 = pdo_fetchall("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 5 ORDER BY createtime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($myorderlist5 as $k=>$v){
		$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$v['id']}");
		$myorderlist5[$k]['ordergoods'] = $ordergoods;
		$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
		$thumbs = unserialize($goods['thumb_url']);
		$goods['thumb'] = $thumbs[0];
		$myorderlist5[$k]['goods'] = $goods;
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
			$merchantorderlist = $merchantorderlist4;
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
							<div class="storename textellipsis1">店铺：'.getmerchantname($v['merchant_id']).'</div>
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
							<a href="'.$this->createMobileUrl('myorder',array('op'=>'detail','id'=>$v['id'])).'" class="pay text-c">订单详情</a>
						</div>
					</div>';
		}
		echo $html;
		exit;
	}else{
		include $this->template('all_orders');
	}
}elseif ($operation == 'detail') {
	$id = intval($_GPC['id']);
	$item = pdo_fetch("SELECT * FROM ".tablename('cygoodssale_order')." WHERE id = {$id} AND weid = {$_W['uniacid']}");
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
	include $this->template('myorderdetail');
}elseif($operation == 'pingjia'){
	$orderid = intval($_GPC['id']);
	$ordergoods = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER_GOODS)." WHERE weid = {$_W['uniacid']} AND orderid = {$orderid}");
	$goods = pdo_fetch("SELECT title,thumb_url,id FROM ".tablename(BEST_GOODS)." WHERE weid = {$_W['uniacid']} AND id = {$ordergoods['goodsid']}");
	$thumbs = unserialize($goods['thumb_url']);
	$goods['thumb'] = $thumbs[0];
	include $this->template('comment');
}elseif($operation == 'docomment'){
	$content = trim($_GPC['content']);
	$orderid = intval($_GPC['orderid']);
	if(empty($content)){
		$resArr['error'] = 1;
		$resArr['message'] = '请输入评论内容！';
		echo json_encode($resArr);
		exit();
	}
	$data['content'] = $content;
	$data['goodsid'] = intval($_GPC['goodsid']);
	$data['star'] = intval($_GPC['star']);
	$data['isniming'] = intval($_GPC['isniming']);
	$data['from_user'] = $member['openid'];
	$data['createtime'] = TIMESTAMP;
	$data['orderid'] = $orderid;
	pdo_insert('cygoodssale_goods_comment',$data);
	pdo_query("UPDATE ".tablename('cygoodssale_order')." SET status = 5 WHERE id = {$orderid}");
	$resArr['error'] = 0;
	$resArr['message'] = '评论成功！';
	echo json_encode($resArr);
	exit();
}elseif($operation == 'shouhuo'){
	$id = intval($_GPC['id']);
	$order = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER)." WHERE id = {$id} AND from_user = '{$_W['fans']['from_user']}'");
	if($order['status'] != 2){
		$resArr['error'] = 1;
		$resArr['message'] = '订单当前状态不能使用该操作！';
		echo json_encode($resArr);
		exit();
	}else{
		$res = pdo_update(BEST_ORDER, array('status' => 4), array('id' => $order['id'], 'weid' => $_W['uniacid']));
		if($res){
			if($order['merchant_id'] != 0){
				$dataaccount = array(
					'weid'=>$_W['uniacid'],
					'merchant_id'=>$order['merchant_id'],
					'price'=>($order['price']-$order['fenxiaoprice']),
					'time'=>TIMESTAMP,
					'remark'=>"订单号为".$order['ordersn']."获得",
				);
				pdo_insert('cygoodssale_merchantaccount',$dataaccount);
			}
			if($order['shareopenid'] != '' && $order['fenxiaoprice'] > 0){
				$dataaaccount = array(
					'weid'=>$_W['uniacid'],
					'openid'=>$order['shareopenid'],
					'money'=>$order['fenxiaoprice'],
					'time'=>TIMESTAMP,
					'explain'=>"订单号为".$order['ordersn']."分销获得",
					'orderid'=>$order['id'],
				);
				pdo_insert(BEST_ACCOUNT,$dataaaccount);
			}
			$resArr['error'] = 0;
			$resArr['message'] = '确认收货成功！';
			echo json_encode($resArr);
			exit();
		}else{
			$resArr['error'] = 1;
			$resArr['message'] = '确认收货失败！';
			echo json_encode($resArr);
			exit();
		}
	}
}elseif($operation == 'delete'){
	$id = intval($_GPC['id']);
	$order = pdo_fetch("SELECT * FROM ".tablename(BEST_ORDER)." WHERE weid = {$_W['uniacid']} AND from_user = '{$member['openid']}' AND status = 0 AND id = {$id}");
	if(empty($order)){
		$resArr['error'] = 1;
		$resArr['message'] = '不存在该订单！';
		echo json_encode($resArr);
		exit();
	}
	pdo_delete(BEST_ORDER,array('id'=>$id));
	pdo_delete(BEST_ORDER_GOODS,array('orderid'=>$id));
	$resArr['error'] = 0;
	$resArr['message'] = '恭喜您，删除订单成功！';
	echo json_encode($resArr);
	exit();
}elseif($operation == 'hexiao'){
	$openid =$_W['fans']['from_user'];
	$ordersn = trim($_GPC['ordersn']);
	$order = pdo_fetch("SELECT * FROM ".tablename('cygoodssale_order')." WHERE weid = {$_W['uniacid']} AND ordersn = '{$ordersn}'");
	if($order['ishexiao'] != 1 || $order['status'] != 1){
		message('抱歉，该订单不能扫码核销！');
	}
	$ordergoods = pdo_fetch("SELECT * FROM ".tablename('cygoodssale_order_goods')." WHERE weid = {$_W['uniacid']} AND orderid = {$order['id']}");
	$goods = pdo_fetch("SELECT hexiaocon,isallhexiao FROM ".tablename('cygoodssale_goods')." WHERE id = {$ordergoods['goodsid']} AND weid = {$_W['uniacid']}");
	$hexiaoarr = explode("|",$goods['hexiaocon']);
	if(!in_array($openid,$hexiaoarr) && $goods['isallhexiao'] == 0){
		message('抱歉，您不是核销工作人员！');
	}else{
		if(!in_array($openid,$hexiaoarr) && $goods['isallhexiao'] == 1){
			$ismerchant = pdo_fetch("SELECT id FROM ".tablename('cygoodssale_merchant')." WHERE weid = {$_W['uniacid']} AND openid = '{$openid}' AND status = 1");
			if(empty($ismerchant)){
				message('抱歉，您不是商户不能全局核销！');
			}
		}
		$data['status'] = 4;
		pdo_update('cygoodssale_order',$data,array('id'=>$order['id'],'weid'=>$_W['uniacid']));
		if($order['merchant_id'] != 0){
			$dataaccount = array(
				'weid'=>$_W['uniacid'],
				'merchant_id'=>$order['merchant_id'],
				'price'=>($order['price']-$order['fenxiaoprice']),
				'time'=>TIMESTAMP,
				'remark'=>"订单号为".$ordersn."获得",
			);
			pdo_insert('cygoodssale_merchantaccount',$dataaccount);
		}
		if($order['shareopenid'] != '' && $order['fenxiaoprice'] > 0){
			$dataaaccount = array(
				'weid'=>$_W['uniacid'],
				'openid'=>$order['shareopenid'],
				'money'=>$order['fenxiaoprice'],
				'time'=>TIMESTAMP,
				'explain'=>"订单号为".$ordersn."分销获得",
				'orderid'=>$order['id'],
			);
			pdo_insert(BEST_ACCOUNT,$dataaaccount);
		}
		if($ordergoods['optionid'] > 0){
			pdo_query("update ".tablename('cygoodssale_goods_option')." set stock=stock-:stock where id=:id", array(":stock" => $ordergoods['total'], ":id" => $ordergoods['optionid']));
		}
		pdo_query("update ".tablename('cygoodssale_goods')." set total=total-:stock,sales=sales+:stock where id=:id", array(":stock" => $ordergoods['total'], ":id" => $ordergoods['goodsid']));
		include $this->template('hexiaosuccess');
	}
}
function getmerchantname($merchant_id){
	$merchant = pdo_fetch("SELECT name FROM ".tablename(BEST_MERCHANT)." WHERE id = {$merchant_id}");
	return $merchant['name'];
}
?>