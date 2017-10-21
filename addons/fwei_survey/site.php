<?php
/**
 * 微调研模块微站定义
 *
 * 悟。空 源 码 网
 * @url http://www.5kym.com/
 */
defined('IN_IA') or exit('Access Denied');

class Fwei_surveyModuleSite extends WeModuleSite {

	public function doWebAdd(){
		header('Location:'.url('platform/reply/post',array('m'=>'fwei_survey')));
	}
	public function doWebQuestion_delete(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$sqid = intval( $_GPC['sqid'] );
		$sinfo = pdo_fetch('SELECT * FROM '.tablename('fwei_survey_question').' WHERE sqid=:sqid', array(':sqid'=>$sqid));
		if( !$sinfo || $sinfo['uniacid'] != $uniacid){
			message('您无权进行此操作');
		}
		pdo_delete('fwei_survey_question', array('uniacid'=>$uniacid, 'sqid'=>$sqid));
		message('问题删除成功', $this->createWebUrl('question', array('id'=>$sinfo['rid'])));
	}
	public function doWebAnswer_delete(){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$uid = intval( $_GPC['uid'] );
		$sid = intval( $_GPC['sid'] );
		$sinfo = pdo_fetch('SELECT * FROM '.tablename('fwei_survey').' WHERE sid=:sid', array(':sid'=>$sid));
		if( !$sinfo || $sinfo['uniacid'] != $uniacid){
			message('您无权进行此操作');
		}
		$rs = pdo_delete('fwei_survey_answer', array('uniacid'=>$uniacid, 'uid'=>$uid));
		if( $rs ){
			pdo_update('fwei_survey', 'num = num - 1', array('uniacid'=>$uniacid, 'sid'=>$sid));
		}
		message('删除成功', $this->createWebUrl('answer', array('id'=>$sinfo['rid'], 'act'=>'detail_list')));
	}
}