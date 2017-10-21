<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];


$qd_config = pdo_fetch("SELECT * FROM ".tablename($this->qd_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($qd_config)){
	$qd_config['status'] = 1;
	$qd_config['title'] = '签到';
}
if(checksubmit('submit')){
	$data = array();
	$data['status'] = intval($_GPC['status']);
	$data['title'] = $_GPC['title'];
	$data['app_bg'] = $_GPC['app_bg'];
	$data['button_name'] = $_GPC['button_name'];
	$data['button_url'] = $_GPC['button_url'];
	$data['button_color'] = $_GPC['button_color'];
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	$qd_config_id = intval($_GPC['qd_config_id']);
	if(empty($qd_config_id)){
		pdo_insert($this->qd_config_table,$data);
	}else{
		pdo_update($this->qd_config_table,$data,array('id'=>$qd_config_id,'weid'=>$weid));
	}
	message('保存成功',$this->createWebUrl('qd_config',array('id'=>$id)),"success");
}
include $this->template('qd_config');