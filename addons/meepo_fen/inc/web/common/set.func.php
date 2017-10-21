<?php
//sysset
function get_default(){
	global $_W;
	$data = array();
	$data['uniacid'] = $_W['uniacid'];
	$data['module'] = 'meepo_fen';
	$data['time'] = time();
	$set = array();
	$set['pan_name'] = $_W['account']['name'];
	$set['fans_num'] = 0;
	$set['credit1'] = 0;
	$set['credit2'] = 0;
	$set['cash'] = 0;
	$data['set'] = serialize($set);
	return pdo_insert('meepo_common_setting',$data);
}

function get_tpl($project){
	global $_W;
	$data = array();
	$data[] = array('type'=>'tpl_text','label'=>'平台名字','display'=>true,'name'=>'pan_name','value'=>$project['pan_name']);
	$data[] = array('type'=>'tpl_text','label'=>'初始化粉丝数目','display'=>true,'name'=>'fans_num','value'=>$project['fans_num']);
	$data[] = array('type'=>'tpl_text','label'=>'赠送积分','display'=>true,'name'=>'credit1','value'=>$project['credit1']);
	$data[] = array('type'=>'tpl_text','label'=>'赠送余额','display'=>true,'name'=>'credit2','value'=>$project['credit2']);
	$data[] = array('type'=>'tpl_text','label'=>'赠送现金红包','display'=>true,'name'=>'cash','value'=>$project['cash']);
	
	
	return $data;
}