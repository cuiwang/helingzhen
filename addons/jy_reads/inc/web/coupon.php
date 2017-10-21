<?php
$oparr = array('display','edit','close');
$op =  in_array( $_GPC ['op'], $oparr) ? $_GPC ['op'] : 'display';

// 列表展示
if ($op == 'display') {
	$pindex = max ( 1, intval ( $_GPC ['page'] ) );
	$psize = 10;
	$list = pdo_fetchall ( 'SELECT * FROM ' . tablename ( $this->table_coupon ) . ' WHERE uniacid=:uniacid ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array (
			':uniacid' => $_W ['uniacid'] 
	) );
	$total = pdo_fetchcolumn ( 'SELECT COUNT(1) FROM ' . tablename ( $this->table_coupon ) . ' WHERE uniacid=:uniacid', array (
			':uniacid' => $_W ['uniacid'] 
	) );
	$activities = pdo_fetchAll('SELECT * FROM '.tablename($this->table_reply).' WHERE status<>0 AND uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));
	foreach($list as $key => $value){
		if($value['limit']==null||empty($value['limit'])){
			$list[$key]['lista'] = '没有活动';
		}else{
			$temp= json_decode('['.$value['limit'].']',true);
			foreach($temp as $v){
				$tempa = pdo_fetch('SELECT * FROM '.tablename($this->table_reply).' WHERE status<>0 AND uniacid=:uniacid AND id=:replyid',array(':uniacid'=>$_W['uniacid'],':replyid'=>$v));
				if($tempa){
					$list[$key]['lista'] .= $tempa['name'] . ',';
				}
			}
		}
	}
	$page = pagination ( $total, $pindex, $psize );
	include $this->template ( 'web/coupon' );
}

if ($op == 'edit') {
	load()->func('tpl');
	$couponid = $_GPC ['couponid'];
	$coupon = pdo_fetch('SELECT * FROM '.tablename($this->table_coupon).' WHERE id=:id AND uniacid=:uniacid',array(':id'=>$couponid,':uniacid'=>$_W['uniacid']));
	
	if(checksubmit()){
		$data = array();
		$data['couponname'] = $_GPC['couponname'];
		$data['couponthumb'] = $_GPC['couponthumb'];
		$data['couponcount'] = $_GPC['couponcount'];
		$data['couponneed'] = $_GPC['couponneed'];
		$data['displayorder'] = $_GPC['displayorder'];
		$data['couponcode'] = $_GPC['couponcode'];

		$data['status'] = $_GPC['status'];
		$data['location'] = $_GPC['location'];
		$data['area'] = $_GPC['area'];
		$data['uniacid'] = $_W['uniacid'];
		$data['islimit'] = $_GPC['islimit'];
		
		$data['couponmsg'] = $_GPC['couponmsg'];
		$data['share'] = $_GPC['share'];

		$data['info'] = empty($_GPC ['property'])?'':json_encode($_GPC ['property']);
		// 添加活动可用红包，不为全部通用
		if(!empty($data['islimit'])){
			$data['limit'] = json_encode($_GPC['limit']);
			$data['limit'] = str_replace('[','',$data['limit']);
			$data['limit'] = str_replace(']','',$data['limit']);
		}

		if(!$coupon){
			$data['couponrest'] = $_GPC['couponcount'];
			pdo_insert($this->table_coupon,$data);
			$couponid = pdo_insertid();
			message('添加成功',$this->createWebUrl('coupon',array('couponid'=>$couponid,'op'=>'edit')));
		}else{
			if($data['couponcount'] < $coupon['couponcount']){
				// 减少的时候
				if(($coupon['couponcount'] - $data['couponcount']) < $coupon['couponrest']){
					$data['couponrest'] = $coupon['couponrest'] + $data['couponcount'] - $coupon['couponcount'];
				}else{
					$data['couponrest'] = 0;
				}
			}else{
				// 增加的时候
				$data['couponrest'] = $_GPC['couponcount'] - $coupon['couponcount'] + $coupon['couponrest'];
			}
			// 修正
			if($data['couponcount'] < $data['couponrest']){
				$data['couponrest'] = $data['couponcount'];
			}
			pdo_update($this->table_coupon,$data,array('id'=>$couponid));
			message('修改成功',$this->createWebUrl('coupon',array('couponid'=>$couponid,'op'=>'edit')));
		}
	}

	if(!$coupon){
		$coupon = array();
		$coupon['couponname'] = '卡券';
		$coupon['couponthumb'] = '';
		$coupon['couponcount'] = 1;
		$coupon['couponneed'] = 1;
		$coupon['couponrest'] = 1;
		$coupon['couponname'] = '这是卡券页面信息';
		$coupon['displayorder'] = 0;
		$coupon['couponcode'] = '';
		$coupon['status'] = 1;
		$coupon['location'] = 0;
		$coupon['area'] = '';
		$coupon['info'] = '';
		$coupon['islimit'] = 0;
		$coupon['limit'] = '';
		$coupon['limitarr'] = array();
	}

	// 获取activityid
	if(empty($coupon['limit'])){
		$coupon['limitarr'] = array();
	}else{
		$coupon['limitarr'] = json_decode('['.$coupon['limit'].']',true);
	}
	$coupon['info'] = json_decode($coupon['info'],true);
	$activities = pdo_fetchAll('SELECT * FROM '.tablename($this->table_reply).' WHERE status<>0 AND uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));
	$properties = pdo_fetchAll('SELECT * FROM '.tablename($this->table_property));
	include $this->template ( 'web/coupon' );
}

if($op == 'close'){
	$couponid = $_GPC ['couponid'];
	$re = pdo_update($this->table_coupon,array('status'=>0),array('id'=>$couponid));
	if($re){
		message('修改成功',$this->createWebUrl('coupon',array('couponid'=>$couponid)));
	}else{
		message('修改失败',$this->createWebUrl('coupon',array('couponid'=>$couponid,'op'=>'edit')));
	}
}
?>