<?php
	global $_GPC,$_W;
	$op = $_GPC['op'];
	$openid = m('user') -> getOpenid();
	if($_GPC['op']=='toshare'){
		$num = $_GPC['num'];
		$recordid = $_GPC['recordid'];
		$record= pdo_fetch('SELECT * FROM ' . tablename('weliam_indiana_coupon_record'). " WHERE recid=:recid and status = 1 ",array(':uniacid'=>$_W['uniacid'],':recid'=>$recordid));
		$coupon_number = $_GPC['coupon_number'];
		$myrecord = pdo_fetch('SELECT * FROM ' . tablename('weliam_indiana_coupon_record'). " WHERE secondopenid='{$openid}' and coupon_number='{$coupon_number}'");
		if($myrecord){
			message("不能多次领取该优惠卷!");exit;
		}else{
			$coupons = pdo_fetch("select * from".tablename("weliam_indiana_coupon")."where couponid = '{$record['couponid']}' and uniacid={$_W['uniacid']}");
			$left = $record['couponnum']-$num;
			if($left>=0){
				$endtime = intval($coupons['daylimit'])*24*3600;
				$insert = array(
					'couponid' => $record['couponid'],
					'uniacid' => $_W['uniacid'],
					'firstopenid' => $record['firstopenid'],
					'secondopenid' => $openid,
					'gettime' => TIMESTAMP,
					'endtime' =>TIMESTAMP+$endtime,
					'status' => 1,
					'couponnum'=>$num,
					'merchantid'=>$coupons['merchantid'],
					'coupon_number'=>$record['coupon_number']
				);
				pdo_insert('weliam_indiana_coupon_record',$insert);
				$recid = pdo_insertid();
				pdo_update("weliam_indiana_coupon_record",array('couponnum'=>$left),array('recid'=>$recordid));
				$tourl = urlencode($_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=weliam_indiana&do=check&openid='.$openid.'&recid='.$recid);
			}else{
				message("优惠卷被人捷足先登啦！");exit;
			}
		}
		
		$tourl = urlencode($_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=weliam_indiana&do=check&openid='.$openid.'&recid='.$recordid);
	}elseif($_GPC['op']=='sendto'){
		$sendnum = $_GPC['sendnum'];
		$recordid = $_GPC['recordid'];
		$r = pdo_fetch("select * from".tablename("weliam_indiana_coupon_record")."where uniacid={$_W['uniacid']} and recid={$recordid}");
	}else{
		$recordid = $_GPC['recordid'];
		//检测优惠券信息
		$r = pdo_fetch("select * from".tablename("weliam_indiana_coupon_record")."where uniacid={$_W['uniacid']} and recid={$recordid}");
		$d = pdo_fetch("select * from".tablename('weliam_indiana_coupon')."where couponid={$r['couponid']} and uniacid={$_W['uniacid']}");
		//分配二维码地址
		$tourl = urlencode($_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=weliam_indiana&do=check&openid='.$openid.'&recid='.$recordid);
	}
	
	include $this->template('sharecredit');
?>