<?php
/**
 * 金表数据模块定义
 */
defined('IN_IA') or exit('Access Denied');

class cjwy_surveyModule extends WeModule {

	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		if (!empty($rid)) {
			$item = pdo_fetch("SELECT * FROM ".tablename( 'cjwy_survey' )." WHERE rid = :rid", array(':rid' => $rid));
			$item['stime'] = date('Y-m-d H:i:s', $item['stime']);
			$item['etime'] = date('Y-m-d H:i:s', $item['etime']);
		} else {
			$item = array(
				'max_num'	=>	0,
				'stime'	=>	date('Y-m-d H:i:s', TIMESTAMP),
				'etime'	=>	date('Y-m-d H:i:s', strtotime('+30 day', TIMESTAMP) ),
				'success_info'	=>	'填写完成，谢谢参与！',
			);
		}
		// 调用模板页面
		load()->func('tpl');
		include $this->template('rule');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		global $_GPC;
		if ( empty($_GPC['title']) ) {
			return '请填写标题！';
		}
		if( empty($_GPC['thumb']) ){
			return '设置一张封面图片吧！';
		}
		if( $_GPC['stime'] >= $_GPC['etime'] ){
			return '表单时间设置有问题！';
		}
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];

		$insert_data = array(
			'title'	=>	$_GPC['title'],
			'thumb'	=>	$_GPC['thumb'],
			'description'	=>	$_GPC['description'],
			'content'	=>	htmlspecialchars_decode($_GPC['content']),
			'stime'	=>	strtotime($_GPC['stime']),
			'etime'	=>	strtotime($_GPC['etime']),
			'success_info'	=>	$_GPC['success_info'],
			'max_num'	=>	$_GPC['max_num'],
			'credit'	=>	$_GPC['credit'],
			'coupon'	=>	$_GPC['coupon'],
			'sharetitle'=>  $_GPC['sharetitle'],
			'sharepic'  =>  $_GPC['sharepic'],
			'sharedesc' =>  $_GPC['sharedesc'],
			'hdp'       =>  $_GPC['hdp'],
			'bgpic'     =>  $_GPC['bgpic'],
			'copyright' =>  $_GPC['copyright'],
			'copyrighturl'=>$_GPC['copyrighturl'],
		);

		$sinfo = pdo_fetch( 'SELECT * FROM '.tablename('cjwy_survey').' WHERE uniacid = :uniacid AND rid = :rid' , array(':uniacid' => $uniacid,':rid'=>$rid));
		if( $sinfo ){
			pdo_update('cjwy_survey', $insert_data, array('rid'=>$rid));
		} else {
			$insert_data['rid']	=	$rid;
			$insert_data['uniacid']	=	$uniacid;
			$insert_data['created']	=	TIMESTAMP;

			pdo_insert('cjwy_survey', $insert_data);
		}

		message('数据信息保存成功！正转向数据内容管理！', $this->createWebUrl('question', array('id' => $rid)));
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete('cjwy_survey', array('rid'=>$rid));
		pdo_delete('cjwy_survey_question', array('rid'=>$rid));
		pdo_delete('cjwy_survey_answer', array('rid'=>$rid));
	}


}