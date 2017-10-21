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
$ridwall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));	
if(empty($ridwall)){
	$data = error(-1,'活动不存在或是已经被删除！');
	die(json_encode($data));
}
if($_W['isajax']){
	$news = array();
	$pindex = intval($_GPC['page']);
	$psize = 6;
	$news = pdo_fetchall("SELECT * FROM ".tablename('weixin_wall')." WHERE openid=:openid AND weid=:weid  ORDER BY createtime DESC LIMIT " . $pindex . ",{$psize}",array(':openid'=>$openid,':weid'=>$weid));
	if(is_array($news) && !empty($news)){
		 foreach($news as &$row){
			 $row['content'] = emotion(emo($row['content']));
		 }
		 unset($row);
	 }else{
		$news = array();
	 }	
	$data = error(0,$news);
	die(json_encode($data));
}

