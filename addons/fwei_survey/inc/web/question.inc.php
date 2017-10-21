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
$rid = intval( $_GPC['id'] );
//调研基本信息
$sinfo = pdo_fetch('SELECT * FROM '.tablename('fwei_survey')." WHERE rid = :rid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':rid'=>$rid) );
if( empty($sinfo) ){
	message('参数错误!', '',  'error');
}
//取出调研问题列表
$question_list = pdo_fetchall('SELECT * FROM '.tablename('fwei_survey_question')." WHERE sid = :sid AND uniacid = :uniacid ORDER BY sort ASC, sqid ASC", array(':uniacid' => $uniacid, ':sid'=>$sinfo['sid']) );
$types = array(
	'radio'	=>	'单选',
	'checkbox'	=>	'多选',
	'select'	=>	'下拉选框',
	'text'	=>	'单行文本',
	'textarea'	=>	'多行文本',
);
foreach ($question_list as $key => &$val) {
	$val['extra_txt'] = nl2br($val['extra']);
	$val['type_txt'] = $types[$val['type']];
}
include $this->template('question');