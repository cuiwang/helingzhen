<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/__init.php';
if(!in_array('shake',$xianchang['controls'])){
	message('本次活动未开启摇一摇活动！');
}
if(empty($user)){
	message('错误你的信息不存在或是已经被删除！');
}
$shake_info = $shake_config = pdo_fetch("SELECT * FROM ".tablename($this->shake_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if($shake_config['award_again']==2){
	$check_award = pdo_fetch("SELECT `id` FROM ".tablename($this->shake_user_table)." WHERE rid=:rid AND weid=:weid AND award=:award AND openid=:openid LIMIT 1",array(':rid'=>$rid,':weid'=>$weid,':award'=>'1',':openid'=>$openid));
	if(!empty($check_award)){
			message('您已经中过奖啦、机会留给别的小伙伴吧！');
	}
}
if(empty($shake_config)){
	message('请先配置现场摇一摇');
}
$shake_info['slogan_list'] = explode('#',$shake_info['slogan']);
$rotate_id = pdo_fetchcolumn("SELECT `id` FROM ".tablename($this->shake_rotate_table)." WHERE weid=:weid AND rid=:rid AND status!=:status ORDER BY id ASC  LIMIT 1",array(':weid'=>$weid,':rid'=>$rid,':status'=>'3'));
if(empty($rotate_id)){
	message('摇一摇活动已经全部结束啦！');
}
$check_status = pdo_fetchcolumn("SELECT `status` FROM ".tablename($this->shake_rotate_table)." WHERE weid=:weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$rotate_id));
if($check_status==2){
	message('游戏已经开始啦、请等待下一轮');
}

$shake_user = pdo_fetchcolumn("SELECT `id` FROM ".tablename($this->shake_user_table)." WHERE openid=:openid AND rid=:rid AND weid=:weid AND rotate_id=:rotate_id",array(':openid'=>$openid,':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id));
if(empty($shake_user)){
	$join_man = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->shake_user_table)." WHERE weid = :weid AND rid=:rid AND rotate_id =:rotate_id",array(':weid'=>$weid,':rid'=>$rid,':rotate_id'=>$rotate_id));
	if($join_man >= $shake_config['max_man']){
		message('本轮准许参与抢红包活动仅限'.$shake_config['max_man'].'人参与、请等待下一轮开始');
	}else{
		pdo_insert($this->shake_user_table,array('weid'=>$weid,'rid'=>$rid,'rotate_id'=>$rotate_id,'openid'=>$openid,'count'=>0,'nick_name'=>$user['nick_name'],'avatar'=>$user['avatar']));
	}
}
$socket_url = $this->module['config']['socket_url'];
if(empty($socket_url)){
	message('请先设置参数设置里的SOCKET地址');
}
include $this->template('app_shake');