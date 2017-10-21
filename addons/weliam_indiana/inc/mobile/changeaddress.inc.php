<?php 
	global $_W,$_GPC;
	$result = 'false';
	$openid = m('user') -> getOpenid();
	$type = $_GPC['type'];
	$id = $_GPC['id'];
	$isdefault  = $_GPC['isdefault'];
	
	$data = array(
	'uniacid' =>$_W['uniacid'],
	'openid' => $openid,
	'username' => $_GPC['username'],
	'mobile' => $_GPC['mobile'],
	'zipcode' => $_GPC['zipcode'],
	'province' =>$_GPC['provance'],
	'city' => $_GPC['city'],
	'district' => $_GPC['district'],
	'address' => $_GPC['address'],
	'isdefault'  => $_GPC['isdefault']
	);
	
	//判定是修改地址
	if($type == 'update'){
		//判定是否是新增地址
		if($id == '-1'){
			//判定是否是默认地址
			if($isdefault == '1'){
				pdo_update("weliam_indiana_address",array('isdefault' => '0'),array('uniacid' => $_W['uniacid'],'openid' => $openid));	
			}
			pdo_insert("weliam_indiana_address",$data);
			$aid = pdo_insertid();
			if($_GPC['pid']){
				die(json_encode(array("result" => 1, "data" => $aid)));
			}else{
				die(json_encode(array("result" => 1, "data" => '')));
			}
		}else{
			if($isdefault == '1'){
				pdo_update("weliam_indiana_address",array('isdefault' => '0'),array('openid' => $openid,'uniacid' => $_W['uniacid']));	
			}
			pdo_update("weliam_indiana_address",$data,array('id' => $id));
			die(json_encode(array("result" => 1, "data" => '')));
		}
	}

	//判定是删除地址
	if($type == "delete"){
		pdo_delete("weliam_indiana_address",array('id' => $id));
		echo "true";
	}
	 
	?>