<?php
/**
 * fwei_survey 微调研
 * ============================================================================
 * * 版权所有 2005-2012 fwei.net，并保留所有权利。
 *   网站地址: http://www.fwei.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: fwei.net $
 *
*/
global $_GPC,  $_W;
$uniacid = $_W["uniacid"];
$sid = intval( $_GPC['sid'] );
$sqid = intval( $_GPC['sqid'] );

//调研基本信息
$sinfo = pdo_fetch('SELECT * FROM '.tablename('fwei_survey')." WHERE sid = :sid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':sid'=>$sid) );
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
		pdo_update('fwei_survey_question', $insert_data, array('uniacid' => $uniacid, 'sqid'=>$sqid) );
	} else {
		$insert_data['sid'] = $sid;
		$insert_data['rid'] = $sinfo['rid'];
		$insert_data['uniacid'] = $sinfo['uniacid'];
		pdo_insert('fwei_survey_question', $insert_data);
	}
	message('操作成功', $this->createWebUrl('question', array('id'=>$sinfo['rid'])));
} else {
	$item = array(
		'type'	=>	'radio',
		'is_must'	=>	1,
	);

	if( $sqid ){
		$qinfo = pdo_fetch('SELECT * FROM '.tablename('fwei_survey_question')." WHERE sqid = :sqid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':sqid'=>$sqid) );
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