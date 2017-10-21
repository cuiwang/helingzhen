<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$openid = $_W['openid'];
$data = array();
$insert = array();
$sql = "SELECT * FROM ".tablename('weixin_flag')." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
$param = array(':openid' =>$openid, ':rid' =>$rid,':weid' =>$weid);
$member =  pdo_fetch($sql,$param);
if(empty($member)){
	$data = error(-1,'错误你的信息不存在或是已经被删除！');
	die(json_encode($data));
}
if($member['isblacklist']==1){
	$data = error(-1,'你已经被拉入黑名单、不能参与拔河活动！');
	die(json_encode($data));
}
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));	
if(empty($ridwall)){
	$data = error(-1,'活动不存在或是已经被删除！');
	die(json_encode($data));
}
if($_W['isajax']){
	$type = intval($_GPC['type']);
	if(empty($type)){
		$data = error(-1,'错误、请先选择队伍！');
		die(json_encode($data));
	}
	$team_arr = array(1,2);
	if(!in_array($type, $team_arr, TRUE)){
		$data = error(-1,'错误、此队伍不存在！');
		die(json_encode($data));
	}
	$check_bahe = pdo_fetch("SELECT `id`,`team` FROM ".tablename('weixin_bahe_team')." WHERE openid=:openid AND rid=:rid AND weid=:weid",array(':openid'=>$openid,':rid'=>$rid,':weid'=>$weid));
	$insert = array();
	$insert['avatar'] = $member['avatar'];
	$insert['nickname'] = emotion($member['nickname']);
	if(empty($check_bahe)){
		$insert['openid'] = $openid;
		$insert['point'] = 0;
		$insert['weid'] =$weid;
		$insert['rid'] =  $rid;
		$insert['old_team'] = $type;
		$insert['createtime'] = time();
		pdo_insert('weixin_bahe_team',$insert);
	}else{
		if($type!=$check_bahe['team']){// 1 1 2 1 
			$insert['team'] = $type;
			$insert['point'] = 0;
			$insert['old_team'] = intval($check_bahe['team']);
		}
		pdo_update('weixin_bahe_team',$insert,array('rid'=>$rid,'weid'=>$weid,'openid'=>$openid));
	}
	
	$back = $this->createMobileUrl('bahe_team',array('rid'=>$rid,'team'=>$type,'time'=>time()));
	$data = error(0,$back);
	die(json_encode($data));
}

