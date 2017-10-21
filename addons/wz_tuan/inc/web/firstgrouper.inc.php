<?php
		global $_W,$_GPC;
		load()->func('tpl');
		checklogin();
		$this -> backlists();
		$this->checkmode();
		$status = !empty($_GPC['status'])?$_GPC['status']:1;
		$refundpercent = $this->module['config']['refundpercent'];
		$allgoods = pdo_fetchall("select gname,id from".tablename('wz_tuan_goods')."where uniacid=:uniacid and isshow=:isshow and is_discount=:is_discount",array(':uniacid'=>$_W['uniacid'],':isshow'=>1,':is_discount'=>0));
		/*退款团长列表*/
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$percent = $_GPC['percent'];
		/*初始退款百分比*/
		if(empty($percent)){
			if($refundpercent){
				$percent = $refundpercent;
			}else{
				$percent=50;
			}
		}
		$total = 0;
		$content = '';
		if(trim($_GPC['goodsid'])!=''){
			$content .= " and g_id like '%{$_GPC['goodsid']}%' ";
		}
		if(trim($_GPC['goodsid2'])!=''){
			$content .= " and g_id like '%{$_GPC['goodsid2']}%' ";
		}
		if(intval($status)==1){
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM'.tablename('wz_tuan_order')."where  uniacid='{$_W['uniacid']}' and tuan_first=1 and status in(2,3,4) and is_tuan<>3 and mobile<>'虚拟' $content order by g_id desc ");
			$all_first_orders = pdo_fetchall("select * from".tablename('wz_tuan_order')."where  uniacid='{$_W['uniacid']}' and tuan_first=1 and status in(2,3,4) and is_tuan<>3 and mobile<>'虚拟' $content order by g_id desc ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
			foreach($all_first_orders as$key=> $value){
				$goods = pdo_fetch("select gname,is_discount from".tablename('wz_tuan_goods')."where id='{$value['g_id']}' and uniacid='{$_W['uniacid']}' ");
				if(empty($goods['is_discount'])){
					$refund_fee = $value['price']*$percent*0.01;/*退款金额*/
					$all_first_orders[$key]['refund'] = $refund_fee;
					$all_first_orders[$key]['gname'] = $goods['gname'];
				}else{
					unset($all_first_orders[$key]);
				}
			}

		}elseif(intval($status)==2){
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM'.tablename('wz_tuan_order')."where  uniacid='{$_W['uniacid']}' and tuan_first=1 and status in(2,3,4,7) and is_tuan=3 and mobile<>'虚拟' $content");
			$all_first_orders = pdo_fetchall("select * from".tablename('wz_tuan_order')."where  uniacid='{$_W['uniacid']}' and tuan_first=1 and status in(2,3,4,7) and is_tuan=3 and mobile<>'虚拟' $content order by g_id,tuan_id desc ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
			foreach($all_first_orders as$key=> $value){
				$goods = pdo_fetch("select gname,is_discount from".tablename('wz_tuan_goods')."where id='{$value['g_id']}' and uniacid='{$_W['uniacid']}' ");
				$reund_record = pdo_fetch("select refundfee from".tablename('wz_tuan_refund_record')."where transid='{$value['transid']}' and uniacid='{$_W['uniacid']}' ");
				if(empty($goods['is_discount'])){
					$all_first_orders[$key]['refund'] = $reund_record['refundfee'];
					$all_first_orders[$key]['gname'] = $goods['gname'];
				}else{
					unset($all_first_orders[$key]);
				}
			}
		}elseif(intval($status)==3){
			/*处理退款*/
				$checkboxs = $_GPC['checkbox'];
				$percent = $_GPC['percent'];
				$success_num =0;
				$fail_num =0;
				foreach($checkboxs as$k=>$value){
					$refund_ids = pdo_fetch("select * from".tablename('wz_tuan_order')."where orderno='{$value}'");
					$fee = $refund_ids['price']*100*$percent*0.01;//退款金额
					if($fee<1){
						$fee=1;
					}
					$res = $this->refund($refund_ids['orderno'],$fee,4);
					if($res == 'success'){
						$success_num+=1;
					}else{
						$fail_num+=1;
					}
				}
				message('团长部分退款操作成功，成功'.$success_num.'人,失败'.$fail_num.'人', referer(), 'success');
		}
		$pager = pagination($total, $pindex, $psize);
		include $this->template('web/firstgrouper');
?>