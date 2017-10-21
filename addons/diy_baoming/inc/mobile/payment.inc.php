<?php
global $_W,$_GPC;
$op=empty($_GPC['op'])?"display":$_GPC['op'];
$uniacid=$_W['uniacid'];
$config = $this ->settings;
$from_user = $_W['fans']['from_user'];

$activity_id=intval($_GPC['activity_id']);
$cgc_baoming_activity = new cgc_baoming_activity();
$activity = $cgc_baoming_activity->getOne($activity_id);

if ($activity['pay_num']>0){
 /* $cgc_baoming_user=new cgc_baoming_user();
  $user_count=$cgc_baoming_user->selectPay_count($activity_id);*/
  if ($activity['pay_numed']>=$activity['pay_num']) {
    message('抱歉，报名名额已满！');
  }
}

$tid=intval($_GPC['tid']);
if ($op=="display"){	
  $tid=intval($_GPC['tid']);
  $order = pdo_fetch("select * from " . tablename('cgc_baoming_user') . " where id=:id  and `uniacid` = :uniacid", array(':id'=>$tid,':uniacid'=>$uniacid));  
  if (!empty($order['is_pay'])) {
    message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！');
  }	
  
  if (empty($order)) {
    message('没找到订单');
  }	
  
  $params['tid'] = $order['ordersn'];
  $params['user'] = $from_user;
  $params['fee'] =$order["pay_money"];
  $params['title'] = $activity['title']."报名费用";
  $params['ordersn'] = $order['ordersn'];
  $this->pay($params);
}  
     
 	