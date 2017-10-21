<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
$ddp_config = pdo_fetch("SELECT * FROM ".tablename($this->ddp_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($ddp_config)){
	$ddp_config['ddp_type']  = 1;
}
if(checksubmit('submit')){
	$data = array();
	$data['ddp_type'] = intval($_GPC['ddp_type']);
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	$ddp_config_id = intval($_GPC['ddp_config_id']);
	if(empty($ddp_config_id)){
		pdo_insert($this->ddp_config_table,$data);
		message('保存成功',$this->createWebUrl('ddp_config',array('id'=>$id)),"success");
	}else{
		pdo_update($this->ddp_config_table,$data,array('id'=>$ddp_config_id,'weid'=>$weid));
		message('更新成功',$this->createWebUrl('ddp_config',array('id'=>$id)),"success");
	}
	
}
include $this->template('ddp_config');
 
      
