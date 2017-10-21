<?php
	global $_W, $_GPC;
	$openid = m('user') -> getOpenid();
	$recid = $_GPC['recid'];
	$op = $_GPC['op'];
	//查询优惠券信息
	$record= pdo_fetch('SELECT * FROM ' . tablename('weliam_indiana_coupon_record'). " WHERE recid =:recid  and uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid'],':recid'=>$recid));
	$couponmess = pdo_fetch("select * from".tablename('weliam_indiana_coupon')."where couponid={$record['couponid']} and uniacid={$_W['uniacid']}");
	
	$left = $record['couponnum']-1;
	$usedcouponnum = $record['usedcouponnum']+1;
	$is_hexiao_member = FALSE;
	$hexiao = FALSE;
	
	 //*判断是否是核销人员*/
	$hexiao_member = pdo_fetch("select * from".tablename('weliam_indiana_saler')."where openid='{$openid}' and uniacid='{$_W['uniacid']}' and status=1");
	if($hexiao_member){
		if($hexiao_member['storeid']==''){
			$is_hexiao_member = TRUE;
		}else{
			$store_ids = explode(',',substr($hexiao_member['storeid'],0,strlen($hexiao_member['storeid'])-1)); 
				if(in_array($record['merchantid'],$store_ids)){
					$is_hexiao_member = TRUE;
				}
		}
	}

		if($record && $is_hexiao_member && !empty($op)){
			if($left>=0){
				$hexiao = TRUE;
				if(pdo_update("weliam_indiana_coupon_record",array('couponnum'=>$left,'usedcouponnum'=>$usedcouponnum),array('recid'=>$recid))){
					$coupon = pdo_fetch('SELECT * FROM ' . tablename('weliam_indiana_coupon'). " WHERE couponid=:couponid and uniacid=:uniacid",array(':uniacid'=>$_W['uniacid'],':couponid'=>$record['couponid']));
					$num=$coupon['discount'];//充值夺宝币个数
					m('credit')->updateCredit2($record['firstopenid'],$_W['uniacid'],$num);
					//添加核销数据
					$data = array(
						'name' => $couponmess['title'],
						'discount' => $couponmess['discount'],
						'hexiaoperson' => $openid,
						'usedperson' => $record['secondopenid'],
						'createtime' => time(),
						'uniacid' => $_W['uniacid'],
					);
					pdo_insert("weliam_indiana_hexiao",$data);
				}
			}else{
				message("优惠卷已使用完了!");
			}
		
		}
include $this->template('check');
?>
