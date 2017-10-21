<?php
	global $_W, $_GPC;
	$op = $_GPC['op'];
	$uid = $_W['member']['uid'];
	if(empty($op)){
		$op='main';
	}
	if($op=='exchange'){
		$cards = array();
		$cards = pdo_fetchall('SELECT a.id,a.couponid,b.type,b.title,b.discount,b.thumb,b.credit,b.condition,b.starttime,b.endtime FROM ' 
		. tablename('activity_coupon_modules') . ' AS a LEFT JOIN ' . tablename('activity_coupon') . ' AS b ON a.couponid = b.couponid 
		WHERE a.uniacid = :uniacid AND a.module = :modu AND b.starttime <= :time AND b.endtime >= :time  AND b.dosage < b.amount
		ORDER BY a.id DESC', array(':uniacid' => $_W['uniacid'], ':modu' => 'wz_tuan', ':time' => TIMESTAMP,), 'couponid');
	}
	if($op=='main'){
		if(empty($_GPC['type'])){
			$type='djj';
		}else{
			$type = $_GPC['type'];
		}
//		if($type=='credit'){
//			$credits = pdo_fetch("select credit1 from".tablename('mc_members')."where uid=:uid and uniacid=:uniacid",array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
//		}else
		if($type=='djj'){
			$djj_num=0;
			$djjs = pdo_fetchall('SELECT b.couponid,b.discount,b.condition,b.endtime,b.type FROM '. tablename('activity_coupon_modules') . ' AS a LEFT JOIN ' . 
			tablename('activity_coupon') . ' AS b ON a.couponid = b.couponid WHERE a.uniacid = :uniacid AND 
			a.module = :modu AND b.starttime <= :time AND b.endtime >= :time AND b.type in(1,2) ORDER BY a.id 
			DESC', array(':uniacid' => $_W['uniacid'], ':modu' => 'wz_tuan', ':time' => TIMESTAMP), 'couponid');
			
			foreach($djjs as$key=>$value){
				$djj_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record') . 
				" WHERE uniacid=:uniacid and uid=:uid and grantmodule=:grantmodule and couponid=:couponid and status = 1",
				array(':uniacid'=>$_W['uniacid'],':uid'=>$uid,':grantmodule'=>'wz_tuan',':couponid'=>$value['couponid']));
				if ($djj_num) {
					$djjs[$key]['num'] = $djj_num;
				}else{
					unset($djjs[$key]);
				}
			}
			 
		}elseif($type=='zkj'){
			$zkj_num=0;
			//优惠券已使用
			$zkjs = pdo_fetchall('SELECT b.couponid,b.discount,b.condition,b.endtime,b.type FROM '. tablename('activity_coupon_modules') . ' AS a LEFT JOIN ' . 
			tablename('activity_coupon') . ' AS b ON a.couponid = b.couponid WHERE a.uniacid = :uniacid AND 
			a.module = :modu AND b.starttime <= :time AND b.type in(1,2) ORDER BY a.id 
			DESC', array(':uniacid' => $_W['uniacid'], ':modu' => 'wz_tuan', ':time' => TIMESTAMP), 'couponid');
			
			foreach($zkjs as$key=>$value){
				$zkj_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record') . 
				" WHERE uniacid=:uniacid and uid=:uid and grantmodule=:grantmodule and couponid=:couponid and status = 2",
				array(':uniacid'=>$_W['uniacid'],':uid'=>$uid,':grantmodule'=>'wz_tuan',':couponid'=>$value['couponid']));
				if ($zkj_num) {
					$zkjs[$key]['num'] = $zkj_num;
					$zkjs[$key]['status'] = 2;
				}else{
					unset($zkjs[$key]);
				}
			}
			//优惠券已过期
			$zkjss = pdo_fetchall('SELECT b.couponid,b.discount,b.condition,b.endtime,b.type FROM '. tablename('activity_coupon_modules') . ' AS a LEFT JOIN ' . 
			tablename('activity_coupon') . ' AS b ON a.couponid = b.couponid WHERE a.uniacid = :uniacid AND 
			a.module = :modu AND b.endtime <= :time AND b.type in(1,2) ORDER BY a.id 
			DESC', array(':uniacid' => $_W['uniacid'], ':modu' => 'wz_tuan', ':time' => TIMESTAMP), 'couponid');
			
			foreach($zkjss as$key=>$value){
				$zkj_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record') . 
				" WHERE uniacid=:uniacid and uid=:uid and grantmodule=:grantmodule and couponid=:couponid and status = 1",
				array(':uniacid'=>$_W['uniacid'],':uid'=>$uid,':grantmodule'=>'wz_tuan',':couponid'=>$value['couponid']));
				if ($zkj_num) {
					$zkjss[$key]['num'] = $zkj_num;
					$zkjss[$key]['status'] = 1;
				}else{
					unset($zkjss[$key]);
				}
			}
			$zkjs = array_merge($zkjs, $zkjss);  
		}
	}
	include $this->template('credit');
	
?>
