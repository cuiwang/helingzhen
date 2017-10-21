<?php
require_once IA_ROOT . "/addons/".$this->modulename."/inc/common.php"; 
global $_W, $_GPC;
$op=!empty($_GPC['op'])?$_GPC['op']:"display";
$from_user=$_W['fans']['from_user'];
$settings=$this->module['config'];
$uniacid=$_W['uniacid'];
$modulename=$this->modulename;

if ($settings['more_activity']){
  message("无权限");
}

$userinfo=getFromUser($settings,$modulename);
$userinfo=json_decode($userinfo,true);

if (empty($userinfo['openid'])){
  message("没抓到用户信息，可能借用授权服务号没配置好，或者入口错误");
}

$from_user=$userinfo['openid'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid=$_W["uniacid"];

  	
if ($op=='display') { 		
  $id = $_GPC['id'];
  $cgc_baoming_user = new cgc_baoming_user();
  $bao_user = $cgc_baoming_user->getOne($id);
  if (!empty($bao_user['is_return'])){
  	message('退款申请已提交！', '', 'error');
  }
  if ($bao_user['hx_status']==1){
  	message('已核销不能申请退款！', '', 'error');
  }
  
  $cgc_baoming_activity = new cgc_baoming_activity();
$activity = $cgc_baoming_activity->getOne($bao_user['activity_id']);
  
  if ($activity['support_return']==0){
  	message('没有退款权限！', '', 'error');
  }
  
  
  
  
  include $this->template('refund'); 
}else if ($op == 'save'){
	$user_id = $_GPC['user_id'];
	$cgc_baoming_user = new cgc_baoming_user();
	$bao_user = $cgc_baoming_user->getOne($user_id);
	if (!empty($bao_user['is_return'])){
		message('退款申请已提交！', '', 'error');
	}
	if ($bao_user['hx_status']==1){
		message('已核销不能申请退款！', '', 'error');
	}
	$refund = array (
			"uniacid" => $_W['uniacid'],
			"user_id" => $user_id,
			"activity_id" => $bao_user['activity_id'],
			"openid" => $userinfo['openid'],
			"nickname" => $userinfo['nickname'],
			"headimgurl" => $userinfo['headimgurl'],
			"createtime" => TIMESTAMP,
			"ret_money" => $bao_user['pay_money'],
			"ordersn" => $bao_user['ordersn'],
			"wx_ordersn" => $bao_user['wx_ordersn'],
			"refund_type"=>$_GPC['refund_type'],	
			"description"=>$_GPC['description'],	
			"is_return"=>1,	
		);
	
	$cgc_baoming_refund = new cgc_baoming_refund();
	
	$temp = $cgc_baoming_refund->insert($refund);
	
	if ($temp){
		$cgc_baoming_user->modify($user_id, array('is_return'=>1));
		 message('退款申请成功！', $this->createMobileUrl('my', array('op' => 'display')), 'success');
	}else{
		message('退款申请失败！', '', 'error');
	}
	
}



        
     