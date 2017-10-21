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
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->vote_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
}elseif($op=='post'){
	$vote_id = intval($_GPC['vote_id']);
	if(!empty($vote_id)){
		$vote = pdo_fetch("SELECT * FROM ".tablename($this->vote_table)." WHERE weid = :weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$vote_id));
		
		$vote_xms = pdo_fetchall("SELECT * FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND fid=:fid ORDER BY displayid ASC",array(':rid'=>$rid,':fid'=>$vote['id']));
	}else{
			$vote['show_style'] = 1;
			$vote['vote_more'] = 1;
			$vote['vote_nums'] = 2;
			$vote['vote_show_result'] = 1;
			$vote['status'] = 1;
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
	$data['status'] = intval($_GPC['status']);//vote nums
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	
	$vote_xms = array();
	if(empty($vote_id)){
		pdo_insert($this->vote_table,$data);
		$insert_id = pdo_insertid();
		if($insert_id){
			if (is_array($_GPC['vote_name']) && !empty($_GPC['vote_name'])) {
				foreach ($_GPC['vote_name'] as $key => $value ) {
					if($data['show_style']==1){
						pdo_insert($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$value,'fid'=>$insert_id,'rid'=>$rid,'weid'=>$weid,'show_style'=>$data['show_style'],'nums'=>intval($_GPC['vote_xms_nums'][$key])));
					}else{
						pdo_insert($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$value,'img'=>$_GPC['vote_img'][$key],'fid'=>$insert_id,'rid'=>$rid,'weid'=>$weid,'show_style'=>$data['show_style'],'nums'=>intval($_GPC['vote_xms_nums'][$key])));
					}
				}
			}
		}
		message('保存成功',referer(),"success");
	}else{
		pdo_update($this->vote_table,$data,array('id'=>$vote_id,'weid'=>$weid));
			if (is_array($_GPC['vote_name']) && !empty($_GPC['vote_name'])) {
				foreach ($_GPC['vote_name'] as $key => $value ) {
					$check = pdo_fetch("SELECT `id` FROM ".tablename($this->vote_xms_table)." WHERE id=:id AND rid=:rid",array(":id"=>$_GPC['vote_xms_id'][$key],":rid"=>$rid));
					if($data['show_style']==1){
						if(!empty($check)){
							pdo_update($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$value,'nums'=>intval($_GPC['vote_xms_nums'][$key])),array('id'=>$_GPC['vote_xms_id'][$key],'rid'=>$rid));
						}else{
							pdo_insert($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$value,'fid'=>$vote_id,'rid'=>$rid,'weid'=>$weid,'show_style'=>$data['show_style'],'nums'=>intval($_GPC['vote_xms_nums'][$key])));
						}
					}else{
						if(!empty($check)){
							pdo_update($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$value,'nums'=>intval($_GPC['vote_xms_nums'][$key]),'img'=>$_GPC['vote_img'][$key]),array('id'=>$_GPC['vote_xms_id'][$key],'rid'=>$rid));
						}else{
							pdo_insert($this->vote_xms_table,array('displayid'=>intval($_GPC['vote_displayid'][$key]),'name'=>$value,'img'=>$_GPC['vote_img'][$key],'fid'=>$vote_id,'rid'=>$rid,'weid'=>$weid,'show_style'=>$data['show_style'],'nums'=>intval($_GPC['vote_xms_nums'][$key])));
						}
					}
				}
			}
		
		message('更新成功',referer(),"success");
	}
	
}
}elseif($op=='del'){
	$vote_id = intval($_GPC['vote_id']);
	if(!empty($vote_id)){
		$vote = pdo_fetch("SELECT * FROM ".tablename($this->vote_table)." WHERE weid = :weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$vote_id));
		$vote_xms = pdo_fetchall("SELECT * FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND fid=:fid ORDER BY displayid ASC",array(':rid'=>$rid,':fid'=>$vote['id']));
		if(!empty($vote_xms) && is_array($vote_xms)){
			foreach($vote_xms as $row){
				pdo_delete($this->vote_record,array('rid'=>$rid,'vote_xm_id'=>$row['id']));
				pdo_delete($this->vote_xms_table,array('rid'=>$id,'id'=>$row['id']));
			}
		}
		pdo_delete($this->vote_table,array("rid"=>$rid,'id'=>$vote_id));
	}else{
		message('轮数id错误');
	}
	message('删除成功',referer(),'success');
	
}elseif($op=='ajax'){
	$vote_xm_id = intval($_GPC['vote_xm_id']);
	if(!empty($vote_xm_id)){
		pdo_delete($this->vote_record,array('rid'=>$rid,'vote_xm_id'=>$vote_xm_id));
		pdo_delete($this->vote_xms_table,array('rid'=>$id,'id'=>$vote_xm_id));
		die(json_encode(array('res'=>0)));
	}else{
		die(json_encode(array('res'=>-1)));
	}
}

include $this->template('vote_manage');
 
      
