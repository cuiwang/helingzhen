<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->model('reply');
load()->func('tpl');
$sql = "uniacid = :uniacid and `module` = :module";
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$params[':module'] = 'haoman_dpm';

$rowlist = reply_search($sql, $params);

// message($rid);

if($operation == 'updataad'){

	$id = $_GPC['listid'];

	// message($_GPC['cardnum']);
	$keywords = reply_single($_GPC['rulename']);

	$updata = array(
		'rid' => $rid,
		'uniacid' => $_W['uniacid'],
		'pid' => $_GPC['pid'],
		'name' => $_GPC['name'],
		'description' => $_GPC['description'],
		'avatar' => $_GPC['avatar'],
		'img' => $_GPC['img'],
		'status' => $_GPC['status'],
	);


	$temp =  pdo_update('haoman_dpm_jiabing',$updata,array('id'=>$id));

	message("修改嘉宾成功",$this->createWebUrl('jiabinshow',array('rid'=>$rid)),"success");


}elseif($operation == 'addad'){

	// message($_GPC['cardname']);

	$keywords = reply_single($_GPC['rulename']);

	$updata = array(
		'rid' => $rid,
		'uniacid' => $_W['uniacid'],
		'pid' => $_GPC['pid'],
		'name' => $_GPC['name'],
		'description' => $_GPC['description'],
		'avatar' => $_GPC['avatar'],
		'img' => $_GPC['img'],
		'status' => $_GPC['status'],
	);


	// message($keywords['name']);

	$temp = pdo_insert('haoman_dpm_jiabing', $updata);

	message("添加嘉宾成功",$this->createWebUrl('jiabinshow',array('rid'=>$rid)),"success");

}elseif($operation == 'up'){
	$uid = intval($_GPC['uid']);
	if(empty($uid)){
		message('获取嘉宾ID出错，请刷新后重试', '', 'error');
	}
	$item = pdo_fetch("select * from " . tablename('haoman_dpm_jiabing') . "  where id=:uid ", array(':uid' => $uid));
	$keywords = reply_single($item['rid']);
	include $this->template('updatajiabin');

}elseif($operation == 'del'){
	$uid = intval($_GPC['uid']);
	if(empty($uid)){
		message('获取奖品ID出错，请刷新后重试', '', 'error');
	}
	pdo_delete('haoman_dpm_jiabing', array('id' => $uid));
	message("删除嘉宾成功",$this->createWebUrl('jiabinshow',array('rid'=>$rid)),"success");

}else{


	include $this->template('newjiabin');

}