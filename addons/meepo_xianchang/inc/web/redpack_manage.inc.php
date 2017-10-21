<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
load()->func('tpl');
$op = empty($_GPC['op'])? 'list':$_GPC['op'];
if($op=='list'){
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->redpack_rotate_table)." WHERE weid = :weid AND rid=:rid ORDER BY id ASC",array(':weid'=>$weid,':rid'=>$rid));
}elseif($op=='post'){
	$redpack_id = intval($_GPC['redpack_id']);
	if(!empty($redpack_id)){
		$redpack = pdo_fetch("SELECT * FROM ".tablename($this->redpack_rotate_table)." WHERE weid = :weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$redpack_id));
	}else{
		$redpack['status'] = 1;
		$redpack['type'] = 1;
		$redpack['money'] = 6.6;
		$redpack['all_money'] = 100;
		$redpack['min'] = 1;
		$redpack['max'] = 8.8;
		$redpack['redpack_num'] = 10;
		$redpack['get_num'] = 1;
		$redpack['gailv'] = 50;
		$redpack['countdown'] = 30;
		$redpack['zzs'] = $_W['account']['name'];

	}
	if(checksubmit('submit')){
		$data = array();
		$data['status'] = intval($_GPC['status']);//shake 
		$data['type'] = intval($_GPC['type']);//shake nums
		$data['money'] = $_GPC['money'];//shake nums
		$data['all_money'] = $_GPC['all_money'];//shake nums
		$data['min'] =  $_GPC['min'];//shake nums
		$data['max'] =  $_GPC['max'];//shake nums
		$data['redpack_num'] = intval($_GPC['redpack_num']);//shake nums
		$data['get_num'] = intval($_GPC['get_num']);//shake nums
		$data['gailv'] = intval($_GPC['gailv']);//shake nums
		$data['countdown'] = intval($_GPC['countdown']);//shake nums
		$data['zzs'] = $_GPC['zzs'];//shake nums
		$data['weid'] = $weid;
		$data['rid'] = $rid;
		$redpack_id = intval($_GPC['redpack_id']);
		if(empty($redpack_id)){
			$data['createtime'] = time();
			pdo_insert($this->redpack_rotate_table,$data);
			message('保存成功',referer(),"success");
		}else{
			pdo_update($this->redpack_rotate_table,$data,array('id'=>$redpack_id,'weid'=>$weid));
			message('更新成功',referer(),"success");
		}
		
	}
}elseif($op=='del'){
	$redpack_id = intval($_GPC['redpack_id']);
	if(!empty($redpack_id)){
		pdo_delete($this->redpack_rotate_table,array("rid"=>$rid,'id'=>$redpack_id));
		pdo_delete($this->redpack_user_table,array("rid"=>$rid,'rotate_id'=>$redpack_id));
	}else{
		message('轮数id错误');
	}
	message('删除成功',referer(),'success');
	
}elseif($op=='reset'){
		pdo_delete($this->redpack_rotate_table,array("rid"=>$rid));
		pdo_delete($this->redpack_user_table,array("rid"=>$rid));
		message('清空成功',referer(),"success");
}
include $this->template('redpack_manage');
 
      
