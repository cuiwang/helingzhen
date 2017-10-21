<?php
/**
 * 洗车工完成订单
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
$title = '洗车工完成订单';

//微信配置信息
$signPackage = $_W['account']['jssdkconfig'];

//订单信息
$orderid = intval($_GPC['orderid']);
$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $orderid));

if(empty($order)){
	message('抱歉，您的订单不存在！', '', 'error');
}
if($from_user != $order['worker_openid']){
	message('抱歉，您没有权限操作此订单！', '', 'error');
}
if(!empty($order['images'])){
	$images = explode(",", $order['images']);
}

//服务项目
$goodsid = pdo_fetchall("SELECT goodsid FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$order['id']}'", array(), 'goodsid');
$goods = pdo_fetchall("SELECT title FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
$store = pdo_fetch("SELECT title FROM " . tablename($this->table_store) . " WHERE id=:storeid", array(":storeid"=>$order['storeid']));

//完成订单操作
if($_GPC['finish']=='1'  && $order['status']==2 ){
	//上传图片并完成订单
	$images = $this->uploadpic($_FILES['image']); //上传图片
	
	$result = pdo_update($this->table_order, array('status'=>3,'isfinish'=>1,'finisher'=>$from_user,'finish_time'=>time(), 'images'=>trim($images,",")), array('id'=>$orderid));
	if($result){
		//如果订单赠送积分大于0，则进行积分赠送
		if($order['totalintegral']>0){
			load()->model('mc');
			$uid = mc_openid2uid($order['from_user']);
			mc_credit_update($uid, 'credit1', $order['totalintegral'], array('1'=>'洗车订单:'.$order['id']));
		}

		//给用户发送模版消息
		$umessageDatas = array(
			'touser'      => $order['from_user'],
			'template_id' => $setting['cmessage'],
			'url'         => $_W['siteroot'] .'app/'. $this->createMobileUrl('orderlist',array('status'=>'complete')),
			'topcolor'    => "#7B68EE",
			'data'        => array(
				'first'   => array(
					 'value' => urlencode("您的洗车订单已完成！"),
					 'color' => "#008000",
				 ),
				 'OrderSn'=> array(
					 'value' => urlencode($order['ordersn']),
					 'color' => "#428BCA",
				 ),
				 'OrderStatus'  => array(
					 'value' => urlencode("已完成"),
					 'color' => "#428BCA",
				 ),
				 'remark' => array(
					 'value' => urlencode("您的订单已完成，感谢您对我们的支持，祝您生活愉快！"),
					 'color' => "#428BCA",
				 ),
		 
			  )
		);
		$this->send_template_message(urldecode(json_encode($umessageDatas)));

		//短信发送
		$t3 = pdo_fetch("SELECT * FROM " .tablename($this->table_sms_template). " WHERE weid='{$weid}' AND userscene=3");
		if(!empty($t3['content']) && $t3['status']==1){
			$content = str_replace("【ordersn】", $order['ordersn'],$t3['content']);
			$content = str_replace("【username】", $order['username'],$content);
			$content = str_replace("【carnum】", $order['mycard'],$content);
			$content = str_replace("【storename】", $store['title'],$content);

			$geturl = str_replace("【getmobile】", $order['tel'],$setting['smsurl']);
			$geturl = str_replace("【getcontent】", $content,$geturl);
			$this->http_request($geturl);
		}

		message('完成订单成功！', $this->createMobileUrl('worderlist', array('orderid' => $order['id'])), 'success');
	}else{
		message('完成订单失败！', $this->createMobileUrl('workerorder', array('orderid' => $order['id'])), 'error');
	}
}elseif($_GPC['finish']=='2'){
	//上传图片
	$images = $this->uploadpic($_FILES['image']); //上传图片
	
	$result = pdo_update($this->table_order, array('images'=>trim($images,",")), array('id'=>$orderid));
	if($result){
		message('上传图片成功！', $this->createMobileUrl('worderlist', array('orderid' => $order['id'])), 'success');
	}else{
		message('上传图片失败！', $this->createMobileUrl('workerorder', array('orderid' => $order['id'])), 'error');
	}
}

include $this->template('workerorder');