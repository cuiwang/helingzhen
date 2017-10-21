<?php
/**
 * cjwy_survey 金表数据
 * ============================================================================
*/
global $_GPC,  $_W;
$uniacid = $_W["uniacid"];
$rid = intval( $_GPC['id'] );
//表单基本信息
$sinfo = pdo_fetch('SELECT * FROM '.tablename('cjwy_survey')." WHERE rid = :rid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':rid'=>$rid) );
if( empty($sinfo) ){
	message('参数错误!', '',  'error');
}
//取出表单选项列表
$question_list = pdo_fetchall('SELECT * FROM '.tablename('cjwy_survey_question')." WHERE sid = :sid AND uniacid = :uniacid ORDER BY sort ASC, sqid ASC", array(':uniacid' => $uniacid, ':sid'=>$sinfo['sid']) );
$types = array(
	'radio'	=>	'单选',
	'checkbox'	=>	'多选',
	'select'	=>	'下拉选框',
	'text'	=>	'当行输入',
	'textarea'	=>	'多行文本',
);
foreach ($question_list as $key => &$val) {
	$val['extra_txt'] = nl2br($val['extra']);
	$val['type_txt'] = $types[$val['type']];
}
include $this->template('question');