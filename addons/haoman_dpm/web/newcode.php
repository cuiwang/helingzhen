<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$turntable = $_GPC['turntable'];
if(empty($turntable)){
	message('请从左侧菜单选择具体的活动类型，再添加奖品信息', '', 'error');
}
if($turntable == 1){
	$awardtitle = '现场抽奖';
}
if($turntable == 2){
	$awardtitle = '抢红包';
}
load()->model('reply');
load()->func('tpl');
//$sql = "uniacid = :uniacid and `module` = :module";
//$params = array();
//$params[':uniacid'] = $_W['uniacid'];
//$params[':module'] = 'haoman_dpm';

//$rowlist = reply_search($sql, $params);

// message($rid);

if($operation == 'updataad'){

	$id = $_GPC['listid'];

	// message($_GPC['cardnum']);
//	$keywords = reply_single($_GPC['rulename']);

	$updata = array(
		'rid' => $rid,
		'uniacid' => $_W['uniacid'],
		'turntable' => $_GPC['turntable'],
		'sort' => $_GPC['sort'],
		'prizename' => $_GPC['prizename'],
		'couponid' => $_GPC['couponid'],
		'awardspro' => $_GPC['awardspro'],
		'awardstotal' => $_GPC['awardstotal'],
		'awardsimg' => $_GPC['awardsimg'],
		'ptype' => intval($_GPC['ptype']),
		'credit' => $_GPC['credit']*100,
		'credit2' => $_GPC['credit2']*100,
	);


	$temp =  pdo_update('haoman_dpm_prize',$updata,array('id'=>$id));

	message("修改奖品成功",$this->createWebUrl('codeshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");


}elseif($operation == 'addad'){

	// message($_GPC['cardname']);

//	$keywords = reply_single($_GPC['rulename']);

	$updata = array(
		'rid' => $rid,
		'uniacid' => $_W['uniacid'],
		'turntable' => $_GPC['turntable'],
		'sort' => $_GPC['sort'],
		'prizename' => $_GPC['prizename'],
		'couponid' => $_GPC['couponid'],
		'awardspro' => $_GPC['awardspro'],
		'awardstotal' => $_GPC['awardstotal'],
		'awardsimg' => $_GPC['awardsimg'],
		'ptype' => intval($_GPC['ptype']),
		'credit' => $_GPC['credit']*100,
		'credit2' => $_GPC['credit2']*100,
	);


	// message($keywords['name']);

	$temp = pdo_insert('haoman_dpm_prize', $updata);

	message("添加奖品成功",$this->createWebUrl('codeshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");

}elseif($operation == 'up'){
	$uid = intval($_GPC['uid']);
	if(empty($uid)){
		message('获取奖品ID出错，请刷新后重试', '', 'error');
	}
	$item = pdo_fetch("select * from " . tablename('haoman_dpm_prize') . "  where id=:uid ", array(':uid' => $uid));
	$keywords = reply_single($item['rid']);
	include $this->template('updatacode');

}elseif($operation == 'del'){
	$uid = intval($_GPC['uid']);
	if(empty($uid)){
		message('获取奖品ID出错，请刷新后重试', '', 'error');
	}
	pdo_delete('haoman_dpm_prize', array('id' => $uid));
	message("删除奖品成功",$this->createWebUrl('codeshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");

}else{

	// $now = time();
	// $addcard1 = array(
	// 	"getstarttime" => $now,
	// 	"getendtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
	// );

	include $this->template('newcode');

}