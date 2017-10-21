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
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->shake_rotate_table)." WHERE weid = :weid AND rid=:rid ORDER BY id ASC",array(':weid'=>$weid,':rid'=>$rid));
}elseif($op=='post'){
	$shake_id = intval($_GPC['shake_id']);
	if(!empty($shake_id)){
		$shake = pdo_fetch("SELECT * FROM ".tablename($this->shake_rotate_table)." WHERE weid = :weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$shake_id));
	}else{
		$shake['status'] = 1;
		$shake['pnum'] = 10;
	}
	if(checksubmit('submit')){
		$data = array();
		$data['status'] = intval($_GPC['status']);//shake 
		$data['pnum'] = intval($_GPC['pnum']);//shake nums
		$data['weid'] = $weid;
		$data['rid'] = $rid;
		if(empty($shake_id)){
			$data['createtime'] = time();
			pdo_insert($this->shake_rotate_table,$data);
			message('保存成功',referer(),"success");
		}else{
			pdo_update($this->shake_rotate_table,$data,array('id'=>$shake_id,'weid'=>$weid));
			message('更新成功',referer(),"success");
		}
		
	}
}elseif($op=='del'){
	$shake_id = intval($_GPC['shake_id']);
	if(!empty($shake_id)){
		pdo_delete($this->shake_rotate_table,array("rid"=>$rid,'id'=>$shake_id));
		pdo_delete($this->shake_user_table,array("rid"=>$rid,'rotate_id'=>$shake_id));
	}else{
		message('轮数id错误');
	}
	message('删除成功',referer(),'success');
	
}
include $this->template('shake_manage');
 
      
