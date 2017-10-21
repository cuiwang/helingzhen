<?php
global $_W,$_GPC;
$panel_heading = '首次关注';
$row = pdo_fetch("SELECT ba.* FROM ".tablename('meepo_fen_basic')." AS ba LEFT JOIN ".tablename('meepo_fen_reply')." AS re ON re.new_basic_id = ba.id WHERE re.uniacid='{$_W['uniacid']}'");

if($_W['ispost']){
	$date = array(
			'content'=>$_GPC['content'],
			'uniacid'=>$_W['uniacid']
	);
	$reply = pdo_fetch("SELECT * FROM ".tablename('meepo_fen_reply')." WHERE uniacid = '{$_W['uniacid']}'");

	if(empty($reply)){
		//没有设置过
		pdo_insert('meepo_fen_basic',$date);
		$basic_id = pdo_insertid();
		pdo_insert('meepo_fen_reply',array('new_basic_id'=>$basic_id,'uniacid'=>$_W['uniacid']));
	}else{
		if(!empty($reply['new_basic_id'])){
			pdo_update('meepo_fen_basic',$date,array('id'=>$reply['new_basic_id']));
		}else{
			pdo_insert('meepo_fen_basic',$date);
			$basic_id = pdo_insertid();
			pdo_update('meepo_fen_reply',array('new_basic_id'=>$basic_id,'uniacid'=>$_W['uniacid']),array('id'=>$reply['id']));
		}
	}
	message('提交成功',referer(),'success');
}
if(empty($row['content'])){
	$row['content'] = '很开心在#follow_time#认识您，欢迎来到#pan_name#平台，目前#pan_name#已有#follow_num#位粉丝，活动期间首次关注赠送#title#面值#discount#,使用方法#description#,有效期至#endtime#';
}
include $this->template('auto_wel_text');