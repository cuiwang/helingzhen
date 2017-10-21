<?php
   /**
	* 结算管理
	*/

   global $_GPC, $_W;
   $this->checklogin();
   $weid = $_W['uniacid'];

   $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $psize = 15;
   $pindex = max(1, intval($_GPC['page']));

   $fysetting = pdo_fetch("SELECT * FROM " .tablename($this->table_car_setting). " WHERE weid='{$weid}'");
   
   if($op=='display'){
	   $condition = " weid='{$weid}' AND accountid='{$_SESSION['accountid']}' ";
	   if(!empty($_GPC['settle_sn'])){
		   $condition .= " AND settle_sn LIKE '%{$_GPC['settle_sn']}%'";
	   }
	   if(!empty($_GPC['status'])){
		   $condition .= " AND settle_status='{$_GPC['status']}'";
	   }

	   $settlelist = pdo_fetchall("SELECT * FROM " .tablename($this->table_car_settle). " WHERE {$condition} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
	   $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_car_settle). " WHERE {$condition}");
	   $pager = $this->pagination($total, $pindex, $psize);

	   //导出excel表格
	   if($_GPC['out_put']=='output'){
			$i = 0;
			foreach ($settlelist as $key => $value) {
				$arr[$i]['settle_sn']         = $value['settle_sn'];
				$arr[$i]['business_amount']   = $value['business_amount'].'元';
				$arr[$i]['commission_amount'] = $value['commission_amount'].'元';
				$arr[$i]['totalIntegral']     = $value['totalIntegral'].'积分';
				$arr[$i]['conver']            = $value['conver'].'%';
				$arr[$i]['integralAmount']    = $value['integralAmount'].'元';
				$arr[$i]['settle_amount']     = $value['settle_amount'].'元';
				$arr[$i]['settle_status']     = $value['settle_status']==1?'已出账':'已完成';
				$arr[$i]['settle_date']       = $value['settle_date'];
				$arr[$i]['add_time']          = date('Y-m-d H:i:s', $value['add_time']);
				$i++;
			}

			$this->exportexcel($arr, array('账单单号', '营业总额', '佣金总额', '赠送总积分','积分成本率', '积分总额','结算金额', '结算状态', '结算阶段', '账单生成时间'), '账单汇总');
			exit();
		}

   }elseif($op=='details'){
	   $sn = intval($_GPC['sn']);
	   $settle = pdo_fetch("SELECT * FROM " .tablename($this->table_car_settle). " WHERE weid='{$weid}' AND settle_sn='{$sn}' AND accountid='{$_SESSION['accountid']}'");
	   
	   if(empty($settle)){
		   message("该账单不存在！", "", "error");
	   }

	   $refurl = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	   //获取开始和结束时间戳
	   $tmp = explode("至", $settle['settle_date']);
	   $starttime = strtotime($tmp[0]);
	   $endtime   = strtotime($tmp[1])+86399;

	   //服务点列表
	   $storelist = pdo_fetchall("SELECT id,title,commission FROM " .tablename($this->table_store). " WHERE weid='{$weid}' AND accountid='{$_SESSION['accountid']}' ORDER BY id ASC");
	   foreach($storelist as $key=>$store){
		   $business          = 0; //服务点营业总额
	       $commission        = 0; //服务点佣金总额
		   $totalintegral     = 0; //服务点积分总额
		   $orderlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_order). " WHERE storeid='{$store['id']}' AND status=3 AND finish_time>='{$starttime}' AND finish_time<'{$endtime}'");
		   foreach($orderlist as $order){
			  //洗车订单
			  $ordergoods = pdo_fetchall("SELECT * FROM " .tablename($this->table_order_goods). " WHERE orderid='{$order['id']}'");
			  foreach($ordergoods as $value){
				  $business += $value['price'];
			  }
			  $totalintegral += $order['totalintegral'];
		   }
		   $commission += round($business*$store['commission']*0.01, 2);
		   $storelist[$key]['business_amount']   += $business;
		   $storelist[$key]['commission_amount'] += $commission;
		   $storelist[$key]['totalintegral']     += $totalintegral;
	   }

	   //导出excel表格
	   if($_GPC['out_put']=='output'){
			$i = 0;
			foreach ($storelist as $key => $value) {
				$arr[$i]['settle_sn']         = $settle['settle_sn'].'-'.$value['id'];
				$arr[$i]['store_name']        = $value['title'];
				$arr[$i]['business_amount']   = $value['business_amount'].'元';
				$arr[$i]['commission']        = $value['commission'].'%';
				$arr[$i]['commission_amount'] = $value['commission_amount'].'元';
				$arr[$i]['totalIntegral']     = $value['totalintegral'].'积分';
				$arr[$i]['conver']            = $settle['conver'].'%';
				$arr[$i]['integralAmount']    = round($value['totalintegral']*$settle['conver']*0.01,2).'元';
				$arr[$i]['settle_amount']     = round($value['business_amount']-$value['commission_amount']-$value['totalintegral']*$settle['conver']*0.01 ,2).'元';
				$arr[$i]['settle_status']     = $settle['settle_status']==1?'已出账':'已完成';
				$arr[$i]['settle_date']       = $settle['settle_date'];
				$arr[$i]['add_time']          = date('Y-m-d H:i:s', $settle['add_time']);
				$i++;
			}

			$this->exportexcel($arr, array('账单编号', '所属服务点','营业总额', '佣金比例', '佣金总额', '赠送积分','积分换金额率', '应付积分总额','结算金额', '结算状态', '结算阶段', '账单生成时间'), $settle['settle_date'].'结算账单');
			exit();
		}

   }elseif($op=='orders'){
	   $sn = intval($_GPC['sn']);
	   $settle = pdo_fetch("SELECT * FROM " .tablename($this->table_car_settle). " WHERE weid='{$weid}' AND settle_sn='{$sn}' AND accountid='{$_SESSION['accountid']}'");

	   $storeid = intval($_GPC['storeid']);
	   $store = pdo_fetch("SELECT * FROM " .tablename($this->table_store). " WHERE weid='{$weid}' AND id='{$storeid}' AND accountid='{$_SESSION['accountid']}'");
	   
	   if(empty($store)){
		   message("该服务点订单不存在！", "", "error");
	   }

	   //获取开始和结束时间戳
	   $tmp = explode("至", $settle['settle_date']);
	   $starttime = strtotime($tmp[0]);
	   $endtime   = strtotime($tmp[1])+86399;

	   $orderlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_order). " WHERE storeid='{$store['id']}' AND status=3 AND finish_time>='{$starttime}' AND finish_time<'{$endtime}'");
	   foreach($orderlist as $key=>$order){
		  //初始化数据
		  $total = 0;
		  $ordergoods = pdo_fetchall("SELECT * FROM " .tablename($this->table_order_goods). " WHERE orderid='{$order['id']}'");
		  foreach($ordergoods as $value){
			  $total += $value['price'];
		  }
		  $orderlist[$key]['order_total'] = $total;
	   }
	  
   }

   include $this->template('billSettle');

