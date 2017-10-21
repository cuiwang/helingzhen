<?php
load()->model('mc');
global $_GPC, $_W;
mc_oauth_userinfo();
// if($_W['fans']['openid'] == ''){
// 	message('请在微信打开','','success');
// }
//checkauth();
$sid = intval( $_GPC['sid'] );
$uniacid = $_W['uniacid'];

//表单基本信息
$sinfo = pdo_fetch('SELECT * FROM '.tablename('cjwy_survey')." WHERE sid = :sid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':sid'=>$sid) );
if( empty($sinfo) ){
	message('参数错误!', '',  'error');
}
//问题列表
$question_list = pdo_fetchall('SELECT * FROM '.tablename('cjwy_survey_question')." WHERE sid = :sid AND uniacid = :uniacid ORDER BY sort ASC, sqid ASC", array(':uniacid' => $uniacid, ':sid'=>$sinfo['sid']) );

if (checksubmit('submit')) {
	$submit_data = $_GPC['submit_data'];
	$username = $_GPC['username'];
	$mobile = $_GPC['mobile'];

	
	//--自动注册环节,检查是否开启强制注册--//
	// $setting = uni_setting($_W['uniacid'], array('passport'));
	// if (empty($setting['passport']['focusreg'])) {
	// 	//没有开启强制注册，则系统自动注册
	// 	checkauth();
	// }
	$uid = $_W['member']['uid'];//uid email mobile
	if(empty($uid)){
		//开启了强制注册，自定义注册
		$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
		$data = array(
			'uniacid' => $_W['uniacid'],
			'email' => md5($_W['openid']).'@012wz.com',
			'salt' => random(8),
			'groupid' => $default_groupid, 
			'createtime' => TIMESTAMP,
			'realname'	=>	$username,
			'mobile'	=>	$mobile,
		);
		$data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);
		pdo_insert('mc_members', $data);
		$user['uid'] = pdo_insertid();
		
		if(_mc_login($user)){
			//注册并登陆成功
		}else{
			//注册或登陆失败,提示用户
			global $engine;
			$engine->deal_checkauth();
		}
	}
	//--检测自动注册成功--//
	foreach ( $submit_data as $sqid => $arr ) {
		$insert_data = array(
			'rid'	=>	$sinfo['rid'],
			'openid' => $_W['fans']['openid'],
			'sid'	=>	$sinfo['sid'],
			'uniacid'	=>	$uniacid,
			'sqid'	=>	$sqid,
			'uid'	=>	$_W['member']['uid'],
			'created'	=>	TIMESTAMP,
		);
		if( is_array($arr) ){
			foreach ( $arr as $val ) {
				$insert_data['content'] = trim($val);
				pdo_insert('cjwy_survey_answer', $insert_data);
			}
		} else {
			$insert_data['content'] = trim($arr);
			pdo_insert('cjwy_survey_answer', $insert_data);
		}
	}
	pdo_update('cjwy_survey', 'num=num+1', array('sid'=>$sinfo['sid']));
	// die(json_encode( array('s'=>200, 'msg' => $sinfo['success_info'])) );
	//exit:

	 message('信息提交成功',$this->createMobileUrl('ok'),'success');
}

foreach ($question_list as $key => &$val) {
	$val['extra'] = explode("\r\n",$val['extra']);
}
$types = array(
	'radio'	=>	'单选',
	'checkbox'	=>	'多选',
	'select'	=>	'下拉选框',
	'text'	=>	'当行输入',
	'textarea'	=>	'多行文本',
);
//如果用户注册了，调出他的用户名和手机
$members = array();
$survey_status = '';

if( $sinfo['max_num']>0 && $sinfo['num'] >= $sinfo['max_num']){
	$survey_status = '此次活动已完成，谢谢您参与！';
}

if( $_W['member']['uid'] ){
	$members = mc_fetch($_W['member']['uid'], array('realname','mobile', 'uid') );
	$arr = pdo_fetch('SELECT * FROM '.tablename('cjwy_survey_answer').' WHERE sid=:sid AND uid=:uid LIMIT 1', array(':sid'=>$sinfo['sid'], ':uid'=>$_W['member']['uid']));
	if( $arr ){
		$survey_status = '您已参与过了！';
	}
}

if( TIMESTAMP < $sinfo['stime'] ){
	$survey_status = '此表单未开始，开始时间为：'.date('Y-m-d H:i:s', $sinfo['stime']);
}
if( TIMESTAMP > $sinfo['etime'] ){
	$survey_status = '此表单已结束，结束时间为：'.date('Y-m-d H:i:s', $sinfo['etime']);
}

if($arr){
	include $this->template('ok');
}else
include $this->template('reply');