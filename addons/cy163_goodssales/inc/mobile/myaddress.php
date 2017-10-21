<?php
global $_W, $_GPC;
$member = $this->_checkMember($_W);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($operation == 'display'){
	$addresslist = pdo_fetchall("SELECT * FROM ".tablename(BEST_ADDRESS)." WHERE weid = {$_W['uniacid']} AND openid = '{$member['openid']}' ORDER BY isauto DESC");
	include $this->template('address_list');
}elseif($operation == 'post'){
	$id = intval($_GPC['id']);
	if ($_GPC['isdo'] == 1) {
		$realname = trim($_GPC['realname']);
		if(empty($realname)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请填写收货人姓名！';
			echo json_encode($resArr);
			exit();
		}
		$telphone = trim($_GPC['telphone']);
		if(!$this->isMobile($telphone)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请填写正确的手机号码！';
			echo json_encode($resArr);
			exit();
		}
		$province = trim($_GPC['province']);
		$city = trim($_GPC['city']);
		$district = trim($_GPC['district']);
		if(empty($province) || empty($city) || empty($district)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请选择地区！';
			echo json_encode($resArr);
			exit();
		}
		$address = trim($_GPC['address']);
		if(empty($address)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请填写详细地址！';
			echo json_encode($resArr);
			exit();
		}
		$isauto = intval($_GPC['isauto']);
		$data = array(
			'weid'=>$_W['uniacid'],
			'openid'=>$member['openid'],
			'realname'=>$realname,
			'telphone'=>$telphone,
			'province'=>$province,
			'city'=>$city,
			'district'=>$district,
			'address'=>$address,
			'isauto'=>$isauto,
		);
		if($isauto == 1){
			pdo_update(BEST_ADDRESS,array('isauto'=>0),array('openid'=>$member['openid']));
		}
		if(!empty($id)){
			pdo_update(BEST_ADDRESS,$data,array('id'=>$id));
		}else{
			pdo_insert(BEST_ADDRESS,$data);
		}
		$resArr['error'] = 0;
		$resArr['msg'] = '操作成功！';
		echo json_encode($resArr);
		exit();
	}
	$address = pdo_fetch("SELECT * FROM ".tablename(BEST_ADDRESS)." WHERE id = {$id}");
	include $this->template('address_edit');
}elseif($operation == 'delete'){
	$id = intval($_GPC['id']);
	$address = pdo_fetch("SELECT * FROM ".tablename(BEST_ADDRESS)." WHERE id = {$id} AND weid = {$_W['uniacid']} AND openid = '{$member['openid']}'");
	if(empty($address)){
		$resArr['error'] = 1;
		$resArr['msg'] = '抱歉，不存在该地址！';
		echo json_encode($resArr);
		exit();
	}
	pdo_delete(BEST_ADDRESS,array('id'=>$id));
	$resArr['error'] = 0;
	$resArr['msg'] = '地址删除成功！';
	echo json_encode($resArr);
	exit();
}
?>