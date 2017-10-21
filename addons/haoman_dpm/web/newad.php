<?php
global $_GPC, $_W;
// $rid = intval($_GPC['rid']);
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
		'rid' => $_GPC['rulename'],
		'uniacid' => $_W['uniacid'],
		'rulename' => $keywords['name'],
		'adlogo' => $_GPC['adlogo'],
		'adtitle' => $_GPC['adtitle'],
		'addetails' => $_GPC['addetails'],
		'adlink' => $_GPC['adlink'],
		'createtime' =>time(),
	);


	$temp =  pdo_update('haoman_dpm_addad',$updata,array('id'=>$id));

	message("修改广告成功",$this->createWebUrl('admanage'),"success");


}elseif($operation == 'addad'){

	// message($_GPC['cardname']);

	$keywords = reply_single($_GPC['rulename']);

	$updata = array(
		'rid' => $_GPC['rulename'],
		'uniacid' => $_W['uniacid'],
		'rulename' => $keywords['name'],
		'adlogo' => $_GPC['adlogo'],
		'adtitle' => $_GPC['adtitle'],
		'addetails' => $_GPC['addetails'],
		'adlink' => $_GPC['adlink'],
		'createtime' =>time(),
	);


	// message($keywords['name']);

	$temp = pdo_insert('haoman_dpm_addad', $updata);

	message("添加广告成功",$this->createWebUrl('admanage'),"success");

}elseif($operation == 'up'){

	$uid = intval($_GPC['uid']);
	$list = pdo_fetch("select * from " . tablename('haoman_dpm_addad') . "  where id=:uid ", array(':uid' => $uid));

	include $this->template('updataad');

}else{

	$now = time();
	$addcard1 = array(
		"getstarttime" => $now,
		"getendtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
	);

	include $this->template('newad');

}