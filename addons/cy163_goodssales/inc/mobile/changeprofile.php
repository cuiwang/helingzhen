<?php
global $_W,$_GPC;
if(empty($_W['fans']['from_user'])){
	$resArr['error'] = 1;
	$resArr['message'] = '请在微信浏览器端操作！';
	echo json_encode($resArr);
	exit();
}
$member = pdo_fetch("SELECT * FROM ".tablename(BEST_MEMBER)." WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}'");
$changetype = trim($_GPC['changetype']);
if($changetype == 'avatar'){
	$data['avatar'] = trim($_GPC['avatar']);
	if($data['avatar'] == ''){
		$resArr['error'] = 1;
		$resArr['message'] = '请上传头像！';
		echo json_encode($resArr);
		exit();
	}else{
		pdo_update(BEST_MEMBER,$data,array('openid'=>$_W['fans']['from_user']));
		$resArr['error'] = 0;
		$resArr['message'] = '修改头像成功！';
		echo json_encode($resArr);
		exit();
	}
}
if($changetype == 'nickname'){
	$data['nickname'] = trim($_GPC['nickname']);
	$nicknamelen = strlen($data['nickname']);
	if($nicknamelen < 6){
		$resArr['error'] = 1;
		$resArr['message'] = '昵称最少6个字符！';
		echo json_encode($resArr);
		exit();
	}else{
		$hasnickname = pdo_fetch("SELECT id FROM ".tablename(BEST_MEMBER)." WHERE weid = {$_W['uniacid']} AND nickname = '{$data['nickname']}'");
		if(!empty($hasnickname)){
			$resArr['error'] = 1;
			$resArr['message'] = '该昵称已被占用！';
			echo json_encode($resArr);
			exit();
		}
		pdo_update(BEST_MEMBER,$data,array('openid'=>$_W['fans']['from_user']));
		$resArr['error'] = 0;
		$resArr['message'] = '修改昵称成功！';
		echo json_encode($resArr);
		exit();
	}
}
if($changetype == 'realname'){
	if($member['hasrealname'] == 1){
		$resArr['error'] = 1;
		$resArr['message'] = '抱歉，真实姓名只能有1次修改机会！';
		echo json_encode($resArr);
		exit();
	}
	$data['realname'] = trim($_GPC['realname']);
	$data['hasrealname'] = 1;
	$realnamelen = strlen($data['realname']);
	if($realnamelen < 6){
		$resArr['error'] = 1;
		$resArr['message'] = '请输入正确的真实姓名！';
		echo json_encode($resArr);
		exit();
	}else{
		pdo_update(BEST_MEMBER,$data,array('openid'=>$_W['fans']['from_user']));
		$resArr['error'] = 0;
		$resArr['message'] = '修改真实姓名成功！';
		echo json_encode($resArr);
		exit();
	}
}
if($changetype == 'telphone'){
	$data['telphone'] = trim($_GPC['telphone']);
	if(!$this->isMobile($data['telphone'])){
		$resArr['error'] = 1;
		$resArr['message'] = '请填写正确的手机号码！';
		echo json_encode($resArr);
		exit();
	}else{
		pdo_update(BEST_MEMBER,$data,array('openid'=>$_W['fans']['from_user']));
		$resArr['error'] = 0;
		$resArr['message'] = '修改手机号码成功！';
		echo json_encode($resArr);
		exit();
	}
}
if($changetype == 'gender'){
	$data['gender'] = trim($_GPC['gender']);
	if($data['gender'] != '男' && $data['gender'] != '女'){
		$resArr['error'] = 1;
		$resArr['message'] = '请填写正确的性别！';
		echo json_encode($resArr);
		exit();
	}else{
		pdo_update(BEST_MEMBER,$data,array('openid'=>$_W['fans']['from_user']));
		$resArr['error'] = 0;
		$resArr['message'] = '修改性别成功！';
		echo json_encode($resArr);
		exit();
	}
}
?>