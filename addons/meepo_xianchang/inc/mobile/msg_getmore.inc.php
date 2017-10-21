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
$sql = "SELECT * FROM ".tablename($this->user_table)." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
$param = array(':openid' =>$openid, ':rid' =>$rid,':weid' =>$weid);
$user =  pdo_fetch($sql,$param);
if(empty($user)){
	$data = error(-1,'错误你的信息不存在或是已经被删除！');
	die(json_encode($data));
}
$xianchang = pdo_fetch("SELECT * FROM ".tablename($this->xc_table)." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));	
if(empty($xianchang)){
	$data = error(-1,'活动不存在或是已经被删除！');
	die(json_encode($data));
}
if($_W['isajax']){
	$news = array();
	$pindex = intval($_GPC['page']);
	$psize = 6;
	$news = pdo_fetchall("SELECT * FROM ".tablename($this->wall_table)." WHERE openid=:openid AND weid=:weid AND rid=:rid ORDER BY createtime DESC LIMIT " . $pindex . ",{$psize}",array(':openid'=>$openid,':weid'=>$weid,':rid'=>$rid));
	if(is_array($news) && !empty($news)){
		 foreach($news as &$row){
			 $row['content'] = emotion($this->emo($row['content']));
		 }
		 unset($row);
	 }else{
		$news = array();
	 }	
	$data = error(0,$news);
	die(json_encode($data));
}

