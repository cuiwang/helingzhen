<?php
/**
 * 用户取消订单
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

$today = strtotime("today");
$orderid = intval($_GPC['orderid']);
$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $orderid));

if(empty($order)){
	message('抱歉，您的订单不存在！', $this->createMobileUrl('orderlist', array('storeid' => $order['storeid'])), 'error');
}

//如果订单已被接收，请联系洗车工取消
if($order['status']==2){
	message('您的订单已被接收，请联系洗车工！', $this->createMobileUrl('orderlist', array('status' => $order['alreadypay'])), 'error');
}

$uporder = array();	
$uporder['status'] = '-1';
$uporder['cancel_time'] = $today;
$result = pdo_update($this->table_order, $uporder, array('id'=>$orderid));
$user = fans_search($order['from_user']);
if($result){
	$order_goods = pdo_fetchall("SELECT * FROM " .tablename($this->table_order_goods). " WHERE orderid='{$order[id]}'");
	//已付款订单取消
	if(in_array($order['status'],array('1','2'))){
		foreach($order_goods as $goods){
			if(!empty($goods['onlycard'])){
				//洗车卡支付
				$membercard =  pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid AND onlycard=:onlycard", array(':uid' => $user['uid'], ':weid' => $weid, 'onlycard'=>$goods['onlycard']));
				
				$number = array('number'=>$membercard['number']+1);
				$condition = array(
					'uid'      => $user['uid'],
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
					'remark'    => "取消洗车订单[".$order['ordersn']."]",
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
			pdo_update('mc_members', array('credit2'=>$user['credit2']+$reftotal), array('uid'=>$user['uid']));
			//添加用户余额日志
			$credits_log = array(
				   'uid'        => $user['uid'],
				   'uniacid'    => $weid,
				   'credittype' => 'credit2', //credit2代表余额
				   'num'        => +$reftotal,
				   'operator'   => $user['uid'],
				   'module'     => $this->module['name'],
				   'createtime' => time(),
				   'remark'     => '用户取消洗车订单：'.$order['id'],
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
			//工作人员模版消息
			$messageDatas = array(
				'touser'      => $order['worker_openid'],
				'template_id' => $setting['cmessage'],
				'url'         => "",
				'topcolor'    => "#7B68EE",
				'data'        => array(
					'first'   => array(
						 'value' => urlencode("用户取消预约订单通知"),
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

			  $this->send_template_message(urldecode(json_encode($messageDatas)));
		}
	}

	if($order['status']==0 && $order['usecard']==1){
		foreach($order_goods as $goods){
			if(!empty($goods['onlycard'])){
				//洗车卡支付
				$membercard =  pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid AND onlycard=:onlycard", array(':uid' => $user['uid'], ':weid' => $weid, 'onlycard'=>$goods['onlycard']));
				
				$number = array('number'=>$membercard['number']+1);
				$condition = array(
					'uid'      => $user['uid'],
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
					'remark'    => "取消洗车订单[".$order['ordersn']."]",
					'add_time'  => time(),
				);
				pdo_insert($this->table_onecard_record, $onecard_record);
			}
			unset($membercard);
		}
	}

	message('取消订单成功！', $this->createMobileUrl('orderlist'), 'success');
}else{
	message('取消订单失败！', $this->createMobileUrl('orderlist'), 'error');
}