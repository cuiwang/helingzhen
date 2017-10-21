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
$ty=$_GPC['ty'];
$reply = pdo_fetch("SELECT   rid,title,sharetitle,shareimg,sharedesc,config,addata,endtime,apstarttime,apendtime,status  FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(':rid' => $rid));
if(empty($reply['status'])){message("活动已禁用");}
$addata=unserialize($reply['addata']);
$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
if($reply['apstarttime']> time()){
	$aptime=1;//未开始报名
}elseif($reply['apendtime']<time()){
	$aptime=2;//报名已结束
}
$voteuser = pdo_fetch("SELECT id FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid AND  openid = :openid ORDER BY `id` DESC", array(':rid' => $rid,':openid' =>$this->oauthuser['openid']));
if(!empty($voteuser)){
	$myvoteid=$voteuser['id'];
}
$reply['giftscale']=$reply['giftscale']?$reply['giftscale']:1;
$reply['giftunit']=$reply['giftunit']?$reply['giftunit']:"点";
if($_W['ispost']){
	
	$condition="";
	$condition.=" AND status=1 ";
	if(empty($ty)){
		$condition .= " ORDER BY `votenum` DESC ,`giftcount` DESC ";
	}else{
		$condition .= " ORDER BY `giftcount` DESC,`votenum` DESC ";
	}
	$nowpage=$_GPC['limit'];
	$pindex = max(1, intval($nowpage));
	$psize = !empty($reply['rankingnum'])?$reply['rankingnum']:20;	
	$list = pdo_fetchall("SELECT id,noid,avatar,name,giftcount,votenum,introduction FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid  ".$condition." LIMIT ".($pindex-1) * $psize.','.$psize,array(':rid' => $rid));
	if (!empty($list)){
		foreach ($list as $key => $value){
			//$list[$key]['img1']=tomedia($list[$key]['img1']);
			$list[$key]['url']=$this->createMobileUrl('view', array('rid' => $rid,'id' => $list[$key]['id']));  
			if($key<3){
				$list[$key]['hg']="hg1.gif";
				$list[$key]['item3color']='style="color:#FC573D;"';
			}else{
				$list[$key]['hg']="hg2.gif";
			}
			$list[$key]['avatar']=tomedia($list[$key]['avatar']);
			if($value['attestation']){
			$list[$key]['name']='<img class="jiavicon" src="'.MODULE_URL.'/template/static/images/jiavicon.png" height="16" width="16"/>'.$list[$key]['name'];
			}
			$list[$key]['giftcount']=$list[$key]['giftcount']*$reply['giftscale'];
		}
		$sta =200;
	}else{
		$sta =-103;
	}
	exit(json_encode(array('status' => $sta, 'content' => $list)));
}


$_share['title'] =!empty($reply['sharetitle'])?$reply['sharetitle']:$reply['title'];
$_share['imgUrl'] =!empty($reply['shareimg'])?tomedia($reply['shareimg']):tomedia($reply['thumb']);
$_share['desc'] =!empty($reply['sharedesc'])?$reply['sharedesc']:$reply['description'];
$_W['page']['sitename']="排名";
include $this->template('ranking');
