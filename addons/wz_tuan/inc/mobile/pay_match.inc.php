<?php
		global $_GPC, $_W;
		$this->getuserinfo();
		//未校验订单
		$allorders_no =  pdo_fetchall("select * from".tablename('wz_tuan_order')."where uniacid={$_W['uniacid']} and check=0 ");
		foreach($allorders_no as $key =>$value){
			$pay_info = pdo_fetch("select * from".tablename('core_paylog')."where uniacid={$_W['uniacid']} and tid='{$value['orderno']}'");
			$transidinfo = iunserializer($pay_info['tag']);
			$transid = $transidinfo['transaction_id'];
			if($value['pay_type']==2){
				if(empty($value['transid'])){
					$norder['transid'] = $transid;
					$norder['status'] = 1;
					$norder['ptime'] = TIMESTAMP;
					pdo_update('wz_tuan_order', $norder, array('id' => $value['id']));
				}
			}
		}
?>