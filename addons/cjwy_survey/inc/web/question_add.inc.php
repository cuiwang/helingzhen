<?php
/**
 * cjwy_survey 金表数据
 * ============================================================================
*/
global $_GPC,  $_W;
$uniacid = $_W["uniacid"];
$sid = intval( $_GPC['sid'] );
$sqid = intval( $_GPC['sqid'] );

//表单基本信息
$sinfo = pdo_fetch('SELECT * FROM '.tablename('cjwy_survey')." WHERE sid = :sid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':sid'=>$sid) );
if( empty($sinfo) ){
	message('参数错误!', '', 'error');
}

if (checksubmit('submit')) {
	if( empty($_GPC['title']) ){
		message('请输入问题名称');
	}
	$extra = trim($_GPC['extra'], "\n");
	
	if( !in_array($_GPC['type'], array('text', 'textarea')) && empty($extra) ){
		message('单选、多选、下拉列表类型必须输入参数');
	}
	$insert_data = array(
		'title' =>	$_GPC['title'],
		'description'	=>	$_GPC['description'],
		'type'	=>	$_GPC['type'],
		'extra'	=>	$extra,
		'defvalue'	=>	$_GPC['defvalue'],
		'rule'	=>	$_GPC['rule'],
		'is_must'	=>	$_GPC['is_must'] ? 1 : 0,
		'sort'	=>	intval( $_GPC['sort'] ),
		'created'	=>	TIMESTAMP,
	);
	if( $sqid ){
		pdo_update('cjwy_survey_question', $insert_data, array('uniacid' => $uniacid, 'sqid'=>$sqid) );
	} else {
		$insert_data['sid'] = $sid;
		$insert_data['rid'] = $sinfo['rid'];
		$insert_data['uniacid'] = $sinfo['uniacid'];
		pdo_insert('cjwy_survey_question', $insert_data);
	}
	message('操作成功', $this->createWebUrl('question', array('id'=>$sinfo['rid'])));
} else {
	$item = array(
		'type'	=>	'radio',
		'is_must'	=>	1,
	);

	if( $sqid ){
		$qinfo = pdo_fetch('SELECT * FROM '.tablename('cjwy_survey_question')." WHERE sqid = :sqid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':sqid'=>$sqid) );
		if( $qinfo ){
			//$extra = unserialize( $qinfo['extra'] );
			//$qinfo['extra'] = '';
			//foreach ($extra as $key => $value) {
			//	$qinfo['extra'] .= "{$key}:{$value}\n";
			//}
			$item = $qinfo;
		}
	}
	
	include $this->template('question_add');
}