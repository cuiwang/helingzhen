<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
$wall_config = pdo_fetch("SELECT * FROM ".tablename($this->wall_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($wall_config)){
	$wall_config['status'] = 1;
	$wall_config['show_time'] = 0;
	$wall_config['show_style'] = 0;
	$wall_config['show_type'] = 0;
	$wall_config['show_big'] = 0;
	$wall_config['show_big_time'] = 4;
	$wall_config['re_time'] = 3;
	$wall_config['maxlength'] = 0;
	$wall_config['input_count'] = 0;
	$wall_config['chistory'] = 50;
	$wall_config['forbidden_words'] = '我操#操你妹#你妹的';
	
}
if(checksubmit('submit')){
	$data = array();
	$data['status'] = intval($_GPC['status']);
	$data['title'] = $_GPC['title'];
	$data['show_style'] = intval($_GPC['show_style']);
	$data['show_type'] = intval($_GPC['show_type']);
	$data['re_time'] = intval($_GPC['re_time']);
	$data['chistory'] = intval($_GPC['chistory']);
	$data['show_time'] = intval($_GPC['show_time']);
	$data['show_big'] = intval($_GPC['show_big']);
	$data['show_big_time'] = intval($_GPC['show_big_time']);
	$data['maxlength'] = intval($_GPC['maxlength']);
	$data['input_count'] = intval($_GPC['input_count']);
	$data['forbidden_words'] = $_GPC['forbidden_words'];
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	$wall_config_id = intval($_GPC['wall_config_id']);
	if(empty($wall_config_id)){
		pdo_insert($this->wall_config_table,$data);
	}else{
		pdo_update($this->wall_config_table,$data,array('id'=>$wall_config_id,'weid'=>$weid));
	}
	message('保存成功',$this->createWebUrl('wall_config',array('id'=>$id)),"success");
}
include $this->template('wall_config');