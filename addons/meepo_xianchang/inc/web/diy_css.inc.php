<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
$basic_config = pdo_fetch("SELECT * FROM ".tablename($this->basic_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(checksubmit('submit')){
	$data = array();
	$data['diy_css'] = $_GPC['diy_css'];
	$basic_config_id = intval($_GPC['basic_config_id']);
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	if(empty($basic_config_id)){
		pdo_insert($this->basic_config_table,$data);
		message('保存成功',$this->createWebUrl('diy_css',array('id'=>$id)),"success");
	}else{
		pdo_update($this->basic_config_table,$data,array('id'=>$basic_config_id,'weid'=>$weid));
		message('更新成功',$this->createWebUrl('diy_css',array('id'=>$id)),"success");
	}
	
}
include $this->template('diy_css');