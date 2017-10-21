<?php
   /**
    *
	* 系统自动生成结算账单
	*
	*/
   ignore_user_abort();
   set_time_limit(0);

   global $_GPC, $_W;
   $weid = $_W['uniacid'];
   $fysetting = pdo_fetch("SELECT * FROM " .tablename($this->table_car_setting) ." WHERE weid='{$weid}'");

   $start = date('Y-m-d', mktime(0,0,0,date('m')-1,1,date('Y'))); //上个月的开始日期
   $t = date('t',strtotime($start)); //上个月共多少天
   $endtime = mktime(0,0,0,date('m')-1,$t,date('Y'))+86399; //上个月结束时间戳
   $starttime = strtotime($start); //上个月开始时间戳

   $settle_sn = date('Ymd',$starttime).date('md',$endtime); //账单编号


/**
 * 账单结算
 * 
 **/

   //加盟商id分组列表
   $accountarr = pdo_fetchall("SELECT accountid FROM " .tablename($this->table_store). " WHERE weid='{$weid}' AND accountid>0 GROUP BY accountid");
   foreach($accountarr as $acc){
	   //初始化数据
	   $business_amount = 0;
	   $commission_amount = 0;
	   unset($amount);

	   //服务点列表
	   $storelist = pdo_fetchall("SELECT * FROM " .tablename($this->table_store). " WHERE weid='{$weid}' AND accountid='{$acc['accountid']}'");
	   
	   //加盟商信息
	   $account = pdo_fetch("SELECT * FROM " .tablename($this->table_car_account). " WHERE id='{$acc['accountid']}'");
	   foreach($storelist as $key=>$store){
		   $business          = 0; //服务点营业总额
		   $giveIntegral      = 0; //服务点赠送总积分
	       $commission        = 0; //服务点佣金总额
		   $orderlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_order). " WHERE storeid='{$store['id']}' AND status=3 AND finish_time>='{$starttime}' AND finish_time<'{$endtime}'");
		   foreach($orderlist as $order){
			  //洗车订单
			  $ordergoods = pdo_fetchall("SELECT * FROM " .tablename($this->table_order_goods). " WHERE orderid='{$order['id']}'");
			  foreach($ordergoods as $value){
				  $business += $value['price'];
			  }
			  $giveIntegral += $order['totalintegral'];
		   }
		   $commission += round($business*$store['commission']*0.01, 2);
		   $amount[$key]['business_amount'] += $business;
		   $amount[$key]['commission_amount'] += $commission;
		   $amount[$key]['integral_amount'] += $giveIntegral;

	   }

	   foreach($amount as $total){
		   $business_amount   += $total['business_amount'];
		   $commission_amount += $total['commission_amount'];
		   $integral_amount   += $total['integral_amount'];
	   }
	   
	   //整理结算账单数据
	   $settle_data = array(
		  'weid'              => $weid,
		  'accountid'         => $account['id'],
		  'username'          => $account['account'],
		  'settle_sn'         => $settle_sn,
		  'business_amount'   => $business_amount,
		  'commission_amount' => $commission_amount,
		  'conver'            => $fysetting['conver'],
		  'totalIntegral'     => $integral_amount,
		  'integralAmount'    => round($integral_amount*$fysetting['conver']*0.01, 2),
		  'settle_amount'     => $business_amount-$commission_amount-round($integral_amount*$fysetting['conver']*0.01, 2),
		  'settle_date'       => date('Y-m-d',$starttime).'至'.date('Y-m-d',$endtime),
		  'settle_status'     => 1, //1.已出账 2.已结算
		  'add_time'          => time(),
	   );
	   $check_settle = pdo_fetch("SELECT * FROM " .tablename($this->table_car_settle). " WHERE weid='{$weid}' AND accountid='{$acc['accountid']}' AND settle_sn='{$settle_sn}'");
	   if(empty($check_settle)){
		  pdo_insert($this->table_car_settle, $settle_data);
	   }
   }

  echo "结算成功，结算时间：".date('Y-m-d H:i:s');
  exit();
