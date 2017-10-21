<?php
/**
 * 工作人员抢单
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
$orderid = intval($_GPC['orderid']);

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('SendOrder', array('orderid'=>$orderid));
if (isset($_COOKIE[$this->_auth2_openid])) {
	$from_user = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
		if (!empty($userinfo)) {
			$from_user = $userinfo["openid"];
			$nickname = $userinfo["nickname"];
			$headimgurl = $userinfo["headimgurl"];
		} else {
			message('授权失败!');
		}
	} else {
		if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
	}
}

//订单详情
$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = '{$orderid}'");
if(empty($order)){
	message("该订单不存在或已被删除！");
}

//当前工作人员详情
$worker = pdo_fetch("SELECT * FROM " . tablename($this->table_worker) . " WHERE weid = '{$weid}' AND openid='{$from_user}' AND storeid='{$order['storeid']}'");
if(empty($order)){
	message("该订单不存在！");
}
if(empty($worker)){
	message("您不是该服务点工作人员，无法接收订单！");
}
if($order['status']>=2){
	if($order['worker_openid']!=$from_user){
		message("该订单已被接收！");
	}else{
		header("Location:".$_W['siteroot'] .'app/'. $this->createMobileUrl('WorkerOrder', array('orderid'=>$orderid)));
		exit();
	}
}elseif($order['status']==-1){
	message("该订单已取消！");
}

//增加该订单的工作人员信息
pdo_update($this->table_order, array('status'=>2,'worker_openid'=>$from_user), array('id' => $orderid));

//给用户发送订单已被接收回执
$umessageDatas = array(
	'touser'      => $order['from_user'],
	'template_id' => $setting['wmessage'],
	'url'         => $_W['siteroot'] .'app/'. $this->createMobileUrl('orderlist', array('status'=>'alreadypay')),
	'topcolor'    => "#7B68EE",
	'data'        => array(
		'first'   => array(
			 'value' => urlencode("您的洗车订单已预约成功！"),
			 'color' => "#008000",
		 ),
		 'keyword1'=> array(
			 'value' => urlencode(date('Y-m-d',$order['meal_date'])),
			 'color' => "#428BCA",
		 ),
		 'keyword2'  => array(
			 'value' => urlencode($order['meal_time']),
			 'color' => "#428BCA",
		 ),
		 'keyword3'  => array(
			 'value' => urlencode($order['mycard']),
			 'color' => "#428BCA",
		 ),
		 'remark' => array(
			 'value' => urlencode("\\n洗车工：{$worker['name']}\\n手机号码：{$worker['mobile']}\\n洗车地址：{$order['address']}\\n工作人员将尽快为您服务，请保持您的手机畅通！"),
			 'color' => "#428BCA",
		 ),
 
	  )
);
$this->send_template_message(urldecode(json_encode($umessageDatas)));

//短信发送
$t2 = pdo_fetch("SELECT * FROM " .tablename($this->table_sms_template). " WHERE weid='{$weid}' AND userscene=2");
if(!empty($t2['content']) && $t2['status']==1){
	$store = pdo_fetch("SELECT title FROM " .tablename($this->table_store). " WHERE id='{$order['storeid']}'");
	$content = str_replace("【ordersn】", $order['ordersn'],$t2['content']);
	$content = str_replace("【username】", $order['username'],$content);
	$content = str_replace("【carnum】", $order['mycard'],$content);
	$content = str_replace("【storename】", $store['title'],$content);

	$geturl = str_replace("【getmobile】", $order['tel'],$setting['smsurl']);
	$geturl = str_replace("【getcontent】", $content,$geturl);
	$this->http_request($geturl);
}

message('抢单成功，请按时为客户服务！' , $this->createMobileUrl('WorkerOrder', array('orderid'=>$orderid)), 'success');