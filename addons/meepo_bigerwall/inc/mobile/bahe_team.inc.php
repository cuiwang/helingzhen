<?php
include MODULE_ROOT.'/inc/mobile/__init.php';
$team = intval($_GPC['team']);
$openid = $_W['openid'];
if($ridwall['baheshow']!='1'){
	message('本次活动未开启拔河活动！');
}
if(empty($member)){
	message('错误你的信息不存在或是已经被删除！');
}
if(empty($team)){
 message('错误、队伍不存在！');		
}
$team_name = 'bahe_team'.$team.'_name';
$team_image = 'bahe_team'.$team.'_image';
$team_arr = array(1,2);
if(!in_array($team, $team_arr, TRUE)){
	message('错误、此队伍不存在！');
}
	$data = array();
	$insert = array();
	$insert['team'] = $team;
	$check_bahe = pdo_fetch("SELECT `id`,`team` FROM ".tablename('weixin_bahe_team')." WHERE openid=:openid AND rid=:rid AND weid=:weid",array(':openid'=>$openid,':rid'=>$rid,':weid'=>$weid));
	$insert['avatar'] = $member['avatar'];
	$insert['nickname'] = emotion($member['nickname']);
	if(empty($check_bahe)){
		$insert['openid'] = $openid;
		$insert['point'] = 0;
		$insert['weid'] =$weid;
		$insert['rid'] =  $rid;
		$insert['old_team'] = $team;
		$insert['createtime'] = time();
		
		pdo_insert('weixin_bahe_team',$insert);
	}else{
		if($team!=$check_bahe['team']){// 1 1 2 1 
			$insert['team'] = $team;
			$insert['point'] = 0;
			$insert['old_team'] = intval($check_bahe['team']);
		}
		
		pdo_update('weixin_bahe_team',$insert,array('rid'=>$rid,'weid'=>$weid,'openid'=>$openid));
	}
include $this->template('bahe_team');
