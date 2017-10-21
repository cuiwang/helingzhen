<?php
global $_W,$_GPC;
$ops = array('display', 'setting'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
$table_setting = 'meepo_lottery_setting';
if($op == 'display'){
	$sql = 'SELECT * FROM'.tablename($table_setting);
	$setting_lists = pdo_fetchall($sql);
 	foreach($setting_lists as $key => $val){
 		if($val['uniacid']==0){
 			$data['uniacid'] = $_W['uniacid'];
 			$row = pdo_update($table_setting,$data,array('setting_id'=>$val['setting_id']));
 		}	
 	}
	$sql = 'SELECT * FROM '.tablename($table_setting).'WHERE uniacid =:uniacid';
	$params = array(':uniacid'=>$_W['uniacid']);
	$result = pdo_fetch($sql,$params);
}
if($op == 'setting'){
	$sql = 'SELECT * FROM '.tablename($table_setting).'WHERE uniacid =:uniacid';
	$params = array(':uniacid'=>$_W['uniacid']);
	$result = pdo_fetch($sql,$params);
	if(checksubmit('submit')){
		if(!empty($_GPC['content'])){
			$sql = 'SELECT count(*) FROM '.tablename($table_setting).'WHERE uniacid =:uniacid';
			$params =array(':uniacid'=>$_W['uniacid']);
			$rows = pdo_fetchcolumn($sql,$params);
			if($rows){
				$data['share_title'] = $_GPC['share_title'];
				$data['share_desc'] = $_GPC['share_desc'];
				$data['share_thumbnail'] = $_GPC['share_thumbnail'];
				$data['lottey_info'] = $_GPC['content'];
				$data['sign_count'] = $_GPC['sign_count'];
				$data['lottery_count'] = $_GPC['lottery_count'];
				$data['uniacid'] = $_W['uniacid'];
				$row = pdo_update($table_setting,$data);
				if($row){
					message('设置成功！',$this->createWebUrl('lottery_setting'),array('op'=>'display'),'success');
				}else{
					message('设置失败！',$this->createWebUrl('lottery_setting'),array('op'=>'setting'),'error');
				}
			}else{
				$data['share_title'] = $_GPC['share_title'];
				$data['share_desc'] = $_GPC['share_desc'];
				$data['share_thumbnail'] = $_GPC['share_thumbnail'];
				$data['lottey_info'] = $_GPC['content'];
				$data['sign_count'] = $_GPC['sign_count'];
				$data['lottery_count'] = $_GPC['lottery_count'];
				$data['uniacid'] = $_W['uniacid'];
				$row = pdo_insert($table_setting,$data);
				if($row){
					message('设置成功！',$this->createWebUrl('lottery_setting'),array('op'=>'display'),'success');
				}else{
					message('设置失败！',$this->createWebUrl('lottery_setting'),array('op'=>'setting'),'error');
				}
			}
		}
	}
}
include $this->template('web/lottery_setting');