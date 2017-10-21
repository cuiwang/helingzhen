<?php
   /**
	* 结算管理
	*/

   global $_GPC, $_W;
   $weid = $_W['uniacid'];

   $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $psize = 15;
   $pindex = max(1, intval($_GPC['page']));
   
   if($op=='display'){
	   /**
	    * 结算汇总
		*/
	   $settle_sn = pdo_fetchall("SELECT distinct settle_sn FROM " .tablename($this->table_car_settle). " WHERE weid='{$weid}'");

	   foreach($settle_sn as $key=>$value){
		   $list = pdo_fetchall("SELECT * FROM " .tablename($this->table_car_settle). " WHERE weid='{$weid}' AND settle_sn='{$value[settle_sn]}'");
		   foreach($list as $val){
			   $business_amount    += $val['business_amount'];
			   $commission_amount  += $val['commission_amount'];
			   $integralAmount     += $val['integralAmount'];
			   $settle_amount      += $val['settle_amount'];
		   }
		   $settlelist[$key] = array(
			   'settle_sn'         => $value['settle_sn'],
			   'business_amount'   => $business_amount,
			   'commission_amount' => $commission_amount,
			   'integralAmount'    => $integralAmount,
			   'settle_amount'     => $settle_amount,
			   'settle_date'       => $list[0]['settle_date'],
		   );
		   unset($business_amount);
		   unset($commission_amount);
		   unset($settle_amount);
	   }

   }elseif($op=='details'){
	   /**
	    * 加盟商结算单
		*/
	   $settle_sn = intval($_GPC['settle_sn']);
	   if(empty($settle_sn)){
		   message("参数缺失！", "", "error");
	   }
	   $refurl = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	   $condition = " a.weid='{$weid}' AND a.settle_sn='{$settle_sn}'";
	   
	   $settle_list = pdo_fetchall("SELECT a.*, b.contact,b.mobile,b.alipay,b.realname FROM " . tablename($this->table_car_settle) . " a LEFT JOIN ". tablename($this->table_car_account). " b ON a.accountid=b.id WHERE {$condition} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	   $total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_car_settle) . " a LEFT JOIN ". tablename($this->table_car_account). " b ON a.accountid=b.id WHERE {$condition}");
	   $pager = pagination($total, $pindex, $psize);

	   //导出excel表格
		if($_GPC['out_put']=='output'){
			$i = 0;
			foreach ($settle_list as $key => $value) {
				$arr[$i]['settle_sn']         = $value['settle_sn'];
				$arr[$i]['username']          = $value['username'];
				$arr[$i]['contact']           = $value['contact'];
				$arr[$i]['mobile']            = $value['mobile'];
				$arr[$i]['business_amount']   = $value['business_amount'].'元';
				$arr[$i]['commission_amount'] = $value['commission_amount'].'元';
				$arr[$i]['totalIntegral']     = $value['totalIntegral'].'积分';
				$arr[$i]['conver']            = $value['conver'].'%';
				$arr[$i]['integralAmount']    = $value['integralAmount'].'元';
				$arr[$i]['settle_amount']     = $value['settle_amount'].'元';
				$arr[$i]['alipay']			  = $value['alipay'];
				$arr[$i]['realname']		  = $value['realname'];
				$arr[$i]['settle_status']     = $value['settle_status']==1?'已出账':'已完成';
				$arr[$i]['settle_date']       = $value['settle_date'];
				$arr[$i]['add_time']          = date('Y-m-d H:i:s', $value['add_time']);
				$i++;
			}

			$this->exportexcel($arr, array('账单单号', '加盟商用户名', '联系人', '手机号码', '营业总额', '佣金总额', '赠送总积分','积分成本率', '积分总额','结算金额','支付宝帐号', '收款人姓名', '结算状态', '结算阶段', '账单生成时间'), substr($settle_list[0]['settle_sn'],0,4).'年'.substr($settle_list[0]['settle_sn'],3,1).'月账单汇总');
			exit();
		}

   }elseif($op=='confirmPayAll'){
	   /**
	    * 批量确认支付
		*/
		$idarr = $_GPC['payid'];
		if(!is_array($idarr) || empty($idarr)){
			message("未选择任何选项！", "", "error");
		}
		foreach($idarr as $value){
			pdo_update($this->table_car_settle, array('settle_status'=>2), array('id'=>$value));
		}
		message("批量确认支付成功！", "referer", "success");
   }

   include $this->template('web/settle');

