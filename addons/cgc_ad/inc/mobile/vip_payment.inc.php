<?php
global $_W,$_GPC;
$op=empty($_GPC['op'])?"display":$_GPC['op'];
$uniacid=$_W['uniacid'];
$config = $this ->settings; 
$member=$this->get_member();
$from_user=$member['openid'];
$subscribe=$member['follow'];
$quan=$this->get_quan();
if ($op=="display"){	
	
	
	 if ($config['pay_type']!=2) {
	   $this->returnError('支付方式不对！'); 
	 }

 if ($member['openid']!=$from_user) {
	   $this->returnError('用户不对！'); 
	 }
	
$id=substr($_GPC['tid'], 13);
  
  $order = pdo_fetch("select * from " . tablename('cgc_ad_vip_pay') . " where id=:id  and `weid` = :uniacid", array(':uniacid'=>$uniacid,':id'=>$id));	
  if (!empty($order['status'])) {
    $this->returnError('抱歉，您的订单已经付款或是被关闭，请重新进入付款！'); 
  }	
  $params['tid'] = ($_GPC['tid']);
  $params['user'] = $member['openid'];
  $params['fee'] =$order["vip_recharge"];
  $params['title'] = $quan['aname']."充值vip";
  $params['ordersn'] = $_GPC['tid'];
  $this->pay($params);
	
  
}  
     
 	