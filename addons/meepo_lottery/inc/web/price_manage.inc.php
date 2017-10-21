<?php
global $_W,$_GPC;
$tablename = 'meepo_lottery_detail';
$table_user = 'meepo_lottery_members';
$ops = array('display', 'delete'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
load()->func('db');
load()->func('pdo');
load()->func('tpl');
if($op == 'display'){
	$pageindex = max(intval($_GPC['page']), 1); // 当前页码
	$pagesize = 10; // 设置分页大小
	$sql = 'SELECT * FROM'.tablename($tablename);
	$price_lists = pdo_fetchall($sql);
 	foreach($price_lists as $key => $val){
 		if($val['uniacid']==0){
 			$data['uniacid'] = $_W['uniacid'];
 			$row = pdo_update($tablename,$data,array('detail_id'=>$val['detail_id']));
 		}	
 	}
 	$sql = 'SELECT * FROM'.tablename($table_user);
	$member_lists = pdo_fetchall($sql);
 	foreach($member_lists as $key => $val){
 		if($val['uniacid']==0){
 			$data['uniacid'] = $_W['uniacid'];
 			$row = pdo_update($table_user,$data,array('members_id'=>$val['members_id']));
 		}	
 	}
	$sql = 'SELECT * FROM '.tablename($tablename).'WHERE uniacid = :uniacid LIMIT '.(($pageindex -1) * $pagesize).','.$pagesize;
	$params =array(':uniacid'=>$_W['uniacid']);
	$results = pdo_fetchall($sql,$params);
	$sql = 'SELECT COUNT(*) FROM '.tablename($tablename).' WHERE uniacid = :uniacid ';
	$total = pdo_fetchcolumn($sql,$params);
 	$pager = pagination($total, $pageindex, $pagesize);
	foreach($results as $key => $val){
		$sql = 'SELECT * FROM '.tablename($table_user).'WHERE members_openid =:openid AND uniacid =:uniacid';
		$params = array(
			':openid' =>$val['detail_openid'],
			':uniacid'=>$_W['uniacid']
		);
		$user = pdo_fetch($sql,$params);
		$results[$key]['detail_username'] = $user['members_username'];
		$results[$key]['detail_thumbnail'] = $user['members_thumbnail'];
	}	
}
if($op=='delete'){
	$id = intval($_GPC['id']);
	if(!empty($id)){
		$row = pdo_delete($tablename,array('detail_id'=>$id,'uniacid'=>$_W['uniacid']));
		if($row){
			message('删除成功！',$this->createWebUrl('price_manage'),array('op'=>'display'),'success');
		}else{
			message('删除失败！',$this->createWebUrl('price_manage'),array('op'=>'display'),'error');
		}
	}
}
include $this->template('web/price_manage');