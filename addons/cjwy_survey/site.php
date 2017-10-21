<?php
/**
 * 金表数据模块定义
 */
defined('IN_IA') or exit('Access Denied');

class Cjwy_surveyModuleSite extends WeModuleSite {

	public function doWebAdd(){
		header('Location:'.url('platform/reply/post',array('m'=>'cjwy_survey')));
	}
	public function doWebQuestion_delete(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$sqid = intval( $_GPC['sqid'] );
		$sinfo = pdo_fetch('SELECT * FROM '.tablename('cjwy_survey_question').' WHERE sqid=:sqid', array(':sqid'=>$sqid));
		if( !$sinfo || $sinfo['uniacid'] != $uniacid){
			message('您无权进行此操作');
		}
		pdo_delete('cjwy_survey_question', array('uniacid'=>$uniacid, 'sqid'=>$sqid));
		message('问题删除成功', $this->createWebUrl('question', array('id'=>$sinfo['rid'])));
	}
	public function doWebAnswer_delete(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$uid = intval( $_GPC['uid'] );
		$sid = intval( $_GPC['sid'] );
		$sinfo = pdo_fetch('SELECT * FROM '.tablename('cjwy_survey').' WHERE sid=:sid', array(':sid'=>$sid));
		if( !$sinfo || $sinfo['uniacid'] != $uniacid){
			message('您无权进行此操作');
		}
		$rs = pdo_delete('cjwy_survey_answer', array('uniacid'=>$uniacid, 'uid'=>$uid));
		if( $rs ){
			pdo_update('cjwy_survey', 'num = num - 1', array('uniacid'=>$uniacid, 'sid'=>$sid));
		}
		message('删除成功', $this->createWebUrl('answer', array('id'=>$sinfo['rid'], 'act'=>'detail_list')));
	}
}