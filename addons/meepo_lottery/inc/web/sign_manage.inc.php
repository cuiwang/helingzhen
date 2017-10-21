<?php 
global $_W,$_GPC;
$table_sign = 'meepo_lottery_sign';
$table_member = 'meepo_lottery_members';
$ops = array('display', 'delete'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
load()->func('db');
load()->func('pdo');
load()->func('tpl');
if($op == 'display'){
	$pageindex = max(intval($_GPC['page']), 1); // 当前页码
	$pagesize = 10; // 设置分页大小
	$sql = 'SELECT * FROM'.tablename($table_sign);
	$sign_lists = pdo_fetchall($sql);
 	foreach($sign_lists as $key => $val){
 		if($val['uniacid']==0){
 			$data['uniacid'] = $_W['uniacid'];
 			$row = pdo_update($table_sign,$data,array('sign_id'=>$val['sign_id']));
 		}	
 	}
	$sql = 'SELECT * FROM '.tablename($table_sign).'WHERE uniacid = :uniacid LIMIT '.(($pageindex -1) * $pagesize).','. $pagesize;
	$params =  array(':uniacid'=>$_W['uniacid']);
	$sign_result = pdo_fetchall($sql,$params);
	$sql = 'SELECT COUNT(*) FROM '.tablename($table_sign).'WHERE uniacid = :uniacid';
	$total = pdo_fetchcolumn($sql,$params);
 	$pager = pagination($total, $pageindex, $pagesize);
	foreach($sign_result as $key=>$val){
		$sql = 'SELECT * FROM '.tablename($table_member).'WHERE members_openid= :openid AND uniacid = :uniacid';
		$params = array(':openid' => $val['sign_openid'],':uniacid'=>$_W['uniacid']);
		$row = pdo_fetch($sql,$params);
		$sign_result[$key]['sign_username'] = $row['members_username'];
		$sign_result[$key]['sign_thumbnail'] = $row['members_thumbnail'];
	}
}
if($op == 'delete'){
	$id = intval($_GPC['id']);
	if(!empty($id)){
		$row = pdo_delete($table_sign,array('sign_id'=>$id));
		if($row){
		message('删除成功！',$this->createWebUrl('sign_manage'),array('op'=>'display'),'success');

	}else{
		message('删除失败！',$this->createWebUrl('sign_manage'),array('op'=>'delete'),'error');

		}
	}
}

include $this->template('web/sign_manage');