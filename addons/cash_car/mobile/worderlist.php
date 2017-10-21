<?php
/**
 * 洗车工订单管理
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
$from_user = $this->_fromuser;
$pindex = max(1, intval($_GPC['page']));
$psize = 10;

//判断当前用户是否工作人员
$is_worker = pdo_fetch("SELECT * FROM " . tablename($this->table_worker) . " WHERE weid='{$weid}' AND openid = '$from_user'");
if(empty($is_worker)){
	message('您不是工作人员，无权查看该页面！', '', 'error');
}

if($op=='display'){
	$condition = " weid='{$weid}' AND worker_openid='{$from_user}' ";
	if($_GPC['is_all']==1){
		$title = '洗车工全部订单';
		$condition .= " AND (status=2 OR status=3) ";
	}else{
		$title = '待洗车订单';
		$condition .= " AND status=2 ";
	}

	$orderlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_order). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	foreach($orderlist as $key=>$value){
		$ordergoods = pdo_fetchall("SELECT title FROM " .tablename($this->table_order_goods). " WHERE orderid='{$value['id']}'");
		foreach($ordergoods as $goods){
			$goodsname .= $goods['title'].'+';
		}
		$orderlist[$key]['goodsname'] = trim($goodsname, '+');
		unset($goodsname);
		$orderlist[$key]['store'] = pdo_fetch("SELECT title AS store_name FROM " . tablename($this->table_store) . " WHERE id=:storeid", array(":storeid"=>$value['storeid']));
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$condition}");
	$pager = $this->mpagination($total, $pindex, $psize);

}elseif($op=='wait'){
	$title = '待接洗车订单';

	$worker = pdo_fetchall("SELECT storeid FROM ". tablename($this->table_worker). " WHERE weid='{$weid}' AND openid='{$from_user}'");
	$store_arr = array();
	foreach($worker as $key=>$value){
		$store_arr[$key] = $value['storeid'];
	}

	$condition = " weid='{$weid}' AND status=1 ";
	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_order). " WHERE {$condition} ORDER BY id DESC");
	
	$orderlist = array();
	foreach($list as $k=>$v){
		if(in_array($v['storeid'], $store_arr)){
			$orderlist[$k] = $v;
			$orderlist[$k]['store'] = pdo_fetch("SELECT title AS store_name FROM " . tablename($this->table_store) . " WHERE id=:storeid", array(":storeid"=>$v['storeid']));
			$ordergoods = pdo_fetchall("SELECT title FROM " .tablename($this->table_order_goods). " WHERE orderid='{$v['id']}'");
			foreach($ordergoods as $goods){
				$goodsname .= $goods['title'].'+';
			}
			$orderlist[$k]['goodsname'] = trim($goodsname, '+');
			unset($goodsname);
		}
	}

}elseif($op=='wcancelorder'){
	$orderid = intval($_GPC['orderid']);
	$check = pdo_fetch("SELECT * FROM " .tablename($this->table_order). " WHERE weid='{$weid}' AND id='{$orderid}' AND worker_openid='{$from_user}' AND status=2");
	if(empty($check)){
		message("您无权操作此订单！", $this->createMobileUrl('worderlist'), "error");
	}
	
	//订单信息
	$order = pdo_fetch("SELECT a.*,b.uid,c.credit2 FROM " . tablename($this->table_order) . " a LEFT JOIN " .tablename('mc_mapping_fans'). " b ON a.from_user=b.openid LEFT JOIN " .tablename('mc_members'). " c ON b.uid=c.uid WHERE a.id=:id LIMIT 1", array(':id' => $orderid));

	//执行取消操作
	$result = pdo_update($this->table_order, array('status' => -1), array('id' => $orderid));
	if($result && $order['status']==2){
		$order_goods = pdo_fetchall("SELECT * FROM " .tablename($this->table_order_goods). " WHERE orderid='{$orderid}'");
		if(in_array($order['status'], array("1","2"))){
			foreach($order_goods as $goods){
				if(!empty($goods['onlycard'])){
					//洗车卡支付
					$membercard =  pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid  AND onlycard=:onlycard", array(':uid' => $order['uid'], ':weid' => $weid, 'onlycard'=>$goods['onlycard']));
					
					$number = array('number'=>$membercard['number']+1);
					$condition = array(
						'uid'      => $order['uid'],
						'weid'     => $weid,
						'onlycard' => $goods['onlycard'],
					);
					pdo_update($this->table_member_onecard, $number, $condition);
					
					//添加会员洗车卡明细
					$onecard_record = array(
						'weid'      => $weid,
						'uid'       => $membercard['uid'],
						'openid'    => $order['from_user'],
						'title'     => $membercard['title'],
						'reduce'    => '1',
						'total'     => $membercard['number']+1,
						'remark'    => "洗车工取消洗车订单[".$order['ordersn']."]",
						'add_time'  => time(),
					);
					pdo_insert($this->table_onecard_record, $onecard_record);
				}else{
					$reftotal += $goods['price'];
				}
				unset($membercard);
			}


			if($reftotal>0){
				//添加用户退款金额到账户余额
				pdo_update('mc_members', array('credit2'=>$order['credit2']+$reftotal), array('uid'=>$order['uid']));
				//添加用户余额日志
				$credits_log = array(
					   'uid'        => $order['uid'],
					   'uniacid'    => $weid,
					   'credittype' => 'credit2', //credit2代表余额
					   'num'        => +$reftotal,
					   'operator'   => '0',
					   'createtime' => time(),
					   'remark'     => '取消洗车订单：'.$order['id'],
				 );
				 pdo_insert('mc_credits_record', $credits_log);
			}

			//发送取消订单模版消息
			if($setting['istplnotice']){
				$meal_date = date('Y-m-d',$order['meal_date']);
				$goodsid = pdo_fetchall("SELECT goodsid FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$order['id']}'", array(), 'goodsid');
				$goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
				foreach($goods as $val){
					$goods_name .= $val['title'].'+';
				}
				$goods_name = trim($goods_name, '+');

				//用户模版消息
				$userMessage = array(
					'touser'      => $order['from_user'],
					'template_id' => $setting['cmessage'],
					'url'         => $_W['siteroot'] .'app/'. $this->createMobileUrl('orderlist'),
					'topcolor'    => "#7B68EE",
					'data'        => array(
						'first'   => array(
							 'value' => urlencode("洗车工取消预约订单通知"),
							 'color' => "#E43935",
						 ),
						 'OrderSn'=> array(
							 'value' => urlencode($order['ordersn']),
							 'color' => "#428BCA",
						 ),
						 'OrderStatus'  => array(
							 'value' => urlencode("已取消"),
							 'color' => "#2E3A42",
						 ),
						 'remark' => array(
							 'value' => urlencode("\\n订单详细信息\\n用户姓名：{$order['username']}\\n预约日期：{$meal_date}\\n预约时段：{$order['meal_time']}\\n预约车牌：{$order['mycard']}\\n手机号码：{$order['tel']}\\n洗车地址：{$order['address']}\\n服务项目：{$goods_name}"),
							 'color' => "#428BCA",
						 ),
				 
					  )
				);
				$this->send_template_message(urldecode(json_encode($userMessage)));

				//短信发送
				$t4 = pdo_fetch("SELECT * FROM " .tablename($this->table_sms_template). " WHERE weid='{$weid}' AND userscene=4");
				if(!empty($t4['content']) && $t4['status']==1){
					$store = pdo_fetch("SELECT title FROM " .tablename($this->table_store). " WHERE id='{$order['storeid']}'");
					$content = str_replace("【ordersn】", $order['ordersn'],$t4['content']);
					$content = str_replace("【username】", $order['username'],$content);
					$content = str_replace("【carnum】", $order['mycard'],$content);
					$content = str_replace("【storename】", $store['title'],$content);

					$geturl = str_replace("【getmobile】", $order['tel'],$setting['smsurl']);
					$geturl = str_replace("【getcontent】", $content,$geturl);
					$this->http_request($geturl);
				}
			}
		}
	}

	message("取消订单成功！", $this->createMobileUrl('worderlist'), "success");

}

include $this->template('worderlist');