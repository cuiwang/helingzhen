<?php
	global $_W,$_GPC;
	$id = $_GPC['id'];
	$uid = $_W['member']['uid'];
	$yes = FALSE;
	$re = 'fail';
	/*查询当前积分*/
	$credits = pdo_fetch("select credit1 from".tablename('mc_members')."where uid=:uid and uniacid=:uniacid",array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
	/*优惠卷*/
	$activity_coupon = pdo_fetch("select * from".tablename('activity_coupon')."where couponid=:couponid and uniacid=:uniacid",array(':couponid'=>$id,':uniacid'=>$_W['uniacid'])); 
	$gocredit=$activity_coupon['credit'];
	$nowcredits = $credits['credit1']-$gocredit;
	if($nowcredits>=0){
		/*判断是否超过上限*/
		if(intval($activity_coupon['dosage']) >= intval($activity_coupon['limit'])){
			$re = 'limit';
		}else{
			pdo_update("mc_members",array('credit1'=>$nowcredits),array('uid'=>$uid,'uniacid'=>$_W['uniacid']));
			pdo_update("activity_coupon",array('dosage'=>$activity_coupon['dosage']+1),array('couponid'=>$id,'uniacid'=>$_W['uniacid']));
			$yes = TRUE;
			$re = 'success';
			if($yes){
				$data=array(
					'uniacid'=>$_W['uniacid'],
					'uid'=>$uid,
					'grantmodule'=>'wz_tuan',
					'granttime'=>TIMESTAMP,
					'status'=>1,
					'remark'=>'用户使用'.$gocredit.'积分兑换了新微团购优惠卷.',
					'couponid'=>$id
				);
				pdo_insert("activity_coupon_record",$data);
			}
		}
	}else{
		$re = 'empty';
	}
	
	$result=array('result'=>$re);
	die(json_encode($result));
?>