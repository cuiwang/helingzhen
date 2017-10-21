<?php
/**
 * 钻石投票-奖品
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
is_weixin();
$rid=intval($_GPC['rid']);
$reply = pdo_fetch("SELECT  rid,title,prizemsg,sharetitle,shareimg,sharedesc,config,addata,endtime,apstarttime,apendtime,status  FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(':rid' => $rid));
$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
if(empty($reply['status'])){message("活动已禁用");}
$addata=unserialize($reply['addata']);
if($reply['apstarttime']> time()){
	$aptime=1;//未开始报名
}elseif($reply['apendtime']<time()){
	$aptime=2;//报名已结束
}
$voteuser = pdo_fetch("SELECT id FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid AND  openid = :openid ORDER BY `id` DESC", array(':rid' => $rid,':openid' => $this->oauthuser['openid']));
if(!empty($voteuser)){
	$myvoteid=$voteuser['id'];
}


$_share['title'] =!empty($reply['sharetitle'])?$reply['sharetitle']:$reply['title'];
$_share['imgUrl'] =!empty($reply['shareimg'])?tomedia($reply['shareimg']):tomedia($reply['thumb']);
$_share['desc'] =!empty($reply['sharedesc'])?$reply['sharedesc']:$reply['description'];
$_W['page']['sitename']="活动奖品";
include $this->template('prize');

