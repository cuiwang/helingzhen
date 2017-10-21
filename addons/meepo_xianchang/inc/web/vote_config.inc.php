<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
$vote_config = pdo_fetch("SELECT * FROM ".tablename($this->vote_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($vote_config)){
	$vote_config['show_style'] = 1;
	$vote_config['vote_more'] = 1;
	$vote_config['vote_nums'] = 2;
	$vote_config['vote_show_result'] = 1;
}
if(checksubmit('submit')){
	$data = array();
	$data['show_style'] = intval($_GPC['show_style']);
	$data['title'] = $_GPC['title'];
	$data['vote_zhuti'] = $_GPC['vote_zhuti'];
	$data['vote_zhuti_img'] = $_GPC['vote_zhuti_img'];
	$data['vote_zhuti_des'] = $_GPC['vote_zhuti_des'];
	$data['vote_more'] = intval($_GPC['vote_more']);//one double
	$data['vote_nums'] = intval($_GPC['vote_nums']);//vote nums
	$data['vote_start_time'] = strtotime($_GPC['ac_times']['start']);
	$data['vote_end_time'] = strtotime($_GPC['ac_times']['end']);
	$data['vote_show_result'] = intval($_GPC['vote_show_result']);//vote nums
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	$vote_id = intval($_GPC['vote_id']);
	$vote_xms = array();
	if(empty($vote_id)){
		pdo_insert($this->vote_config_table,$data);
		$insert_id = pdo_insertid();
		if($insert_id){
			if (is_array($_GPC['vote_xms_id']) && !empty($_GPC['vote_xms_id'])) {
				foreach ($_GPC['vote_xms_id'] as $key => $value ) {
					if($data['show_style']==1){
						pdo_insert($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$_GPC['vote_name'][$key],'fid'=>$insert_id,'rid'=>$rid,'weid'=>$weid,'show_style'=>$data['show_style']));
					}else{
						pdo_insert($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$_GPC['vote_name'][$key],'img'=>$_GPC['vote_img'][$key],'fid'=>$insert_id,'rid'=>$rid,'weid'=>$weid,'show_style'=>$data['show_style']));
					}
				}
			}
		}
		message('保存成功',$this->createWebUrl('vote_config',array('id'=>$id)),"success");
	}else{
		pdo_update($this->vote_config_table,$data,array('id'=>$vote_id,'weid'=>$weid));
		
			if (is_array($_GPC['vote_xms_id']) && !empty($_GPC['vote_xms_id'])) {
				foreach ($_GPC['vote_xms_id'] as $key => $value ) {
					$check = pdo_fetch("SELECT `id` FROM ".tablename($this->vote_xms_table)." WHERE id=:id AND rid=:rid",array(":id"=>$value,":rid"=>$rid));
					if($data['show_style']==1){
						if(empty($check)){
							pdo_update($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$_GPC['vote_name'][$key]),array('id'=>$value,'rid'=>$rid));
						}else{
							pdo_insert($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$_GPC['vote_name'][$key],'fid'=>$vote_id,'rid'=>$rid,'weid'=>$weid,'show_style'=>$data['show_style']));
						}
					}else{
						if(empty($check)){
							pdo_update($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$_GPC['vote_name'][$key]),array('id'=>$value,'rid'=>$rid));
						}else{
							pdo_insert($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$_GPC['vote_name'][$key],'img'=>$_GPC['vote_img'][$key],'fid'=>$vote_id,'rid'=>$rid,'weid'=>$weid,'show_style'=>$data['show_style']));
						}
					}
				}
			}
		
		message('更新成功',$this->createWebUrl('vote_config',array('id'=>$id)),"success");
	}
	
}
include $this->template('vote_config');
 
      
