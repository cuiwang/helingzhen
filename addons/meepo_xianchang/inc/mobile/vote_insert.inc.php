<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$openid = $_W['openid'];
$data = array();
$insert = array();
if($_W['isajax']){
	$vote_id = intval($_GPC['vote_id']);
	$vote_xms = $_GPC['data'];
	if(!is_array($vote_xms) || empty($vote_xms)){
		$data = error(-1,'请选择投票项');
	    die(json_encode($data));
	}
	
	$sql = "SELECT * FROM ".tablename($this->user_table)." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
	$param = array(':openid' =>$openid, ':rid' =>$rid,':weid' =>$weid);
	$user =  pdo_fetch($sql,$param);
	if($user['status']!=1){
		$data = error(-1,'您的信息未被审核通过、投票失败！');
		die(json_encode($data));
	}
	if(empty($user)){
		$data = error(-1,'错误你的信息不存在或是已经被删除！');
		die(json_encode($data));
	}

	if($user['isblacklist']==2){
		$data = error(-1,'你已经被拉入黑名单、上墙失败！');
		die(json_encode($data));
	}
	$vote = pdo_fetch("SELECT * FROM ".tablename($this->vote_table)." WHERE rid=:rid AND weid=:weid AND id=:id ",array(':rid'=>$rid,':weid'=>$weid,':id'=>$vote_id));
	if($vote['status']==1){
		$data = error(-1,'本轮投票还未开始！');
		die(json_encode($data));
	}
	if($vote['status']==3){
		$data = error(-1,'本轮投票已经结束啦！');
		die(json_encode($data));
	}
	$check_voted = pdo_fetchcolumn("SELECT `id` FROM ".tablename($this->vote_record)." WHERE weid=:weid AND rid=:rid AND openid=:openid AND fid=:fid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid,':fid'=>$vote_id));
	if($check_voted){
		$data = error(-1,'本轮您已经投票过啦');
		die(json_encode($data));
	}
	$vote_xms = $_GPC['data'];
	foreach($vote_xms as $row){
		$insert = array(
			'openid'=>$openid,
			'nick_name'=>$user['nick_name'],
			'avatar'=>$user['avatar'],
			'rid'=>$rid,
			'weid'=>$weid,
			'fid'=>$vote_id,
			'vote_xm_id'=>$row,
			'createtime'=>time(),
			);
		pdo_insert($this->vote_record,$insert);
		pdo_query("UPDATE ".tablename($this->vote_xms_table)." SET nums = nums + 1 WHERE weid = 
			:weid AND id=:id",array(':weid'=>$weid,':id'=>$row));
	}
	die(json_encode(error(0,'success')));
	
}