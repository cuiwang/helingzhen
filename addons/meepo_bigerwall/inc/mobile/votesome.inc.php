<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$openid = $_W['openid'];
$voteid = $_GPC['id'];
if($_W['isajax']){
$data = array();
if(empty($voteid)){
	$data = error(-1,'请选择投票项目！');
	die(json_encode($data));
}
$sql = "SELECT * FROM ".tablename('weixin_flag')." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
$param = array(':openid' =>$openid, ':rid' => $rid,':weid' =>$weid);
$member =  pdo_fetch($sql,$param);
if(empty($member)){
				$data = error(-1,'错误你的信息不存在或是已经被删除！');
				die(json_encode($data));
}
if($member['isblacklist']==1){
	$data = error(-1,'你已经被拉入黑名单、投票失败！');
	die(json_encode($data));
}
if($member['vote']!=0){
	$data = error(-1,'你已经投过票了！');
	die(json_encode($data));
}

pdo_update('weixin_flag',array('vote'=>$voteid),array('id'=>$member['id'],'weid'=>$weid,'rid'=>$rid));
pdo_query("UPDATE ".tablename('weixin_vote')." SET res = res + 1 WHERE id = :id AND weid = :weid AND rid = :rid",array(':id'=>$voteid,':weid'=>$weid,':rid'=>$rid));
$data = error(0,'恭喜、投票成功！');
die(json_encode($data));
}
