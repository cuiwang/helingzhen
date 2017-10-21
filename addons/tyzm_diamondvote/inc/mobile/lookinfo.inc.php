<?php
/**
 * 钻石投票-查看信息
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
is_weixin();
load()->func('communication');
$uniacid = intval($_W['uniacid']);
$rid=intval($_GPC['rid']);
$id=intval($_GPC['id']);
$oauth_openid=$this->oauthuser['oauth_openid'];


if(empty($uniacid) || empty($rid) || empty($id) || empty($oauth_openid)){

	exit(json_encode(array('status' => '2', 'msg' => "参数错误1")));

}

$vipuser = pdo_fetch("SELECT packname FROM " . tablename($this->tableviporder) . " WHERE oauth_openid = :oauth_openid AND packtime>".TIMESTAMP." ORDER BY `packnum` DESC", array(':oauth_openid' => $oauth_openid));

$packnum=pdo_fetchcolumn("SELECT sum(packnum) FROM ".tablename($this->tableviporder)." WHERE oauth_openid = :oauth_openid AND packtime>".TIMESTAMP." ORDER BY `packnum` DESC", array(':oauth_openid' => $oauth_openid));

$looknumt=pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($this->tablelooklist)." WHERE oauth_openid = :oauth_openid ", array(':oauth_openid' => $oauth_openid));
$packnum=$packnum-$looknumt;

if($packnum<1){
	exit(json_encode(array('status' => '2', 'msg' => "没有查看数量了(2)")));
}

$lookinfo = pdo_fetch("SELECT id FROM " . tablename($this->tablelooklist) . " WHERE oauth_openid = :oauth_openid AND rid = :rid AND  tid = :tid", array(':oauth_openid' => $oauth_openid,':rid' =>$rid,':tid' =>$id));


if(empty($lookinfo)){
	$vipin=array(
		'uniacid'=>$uniacid ,
		'rid'=>$rid ,
		'tid'=>$id,
		'oauth_openid'=>$oauth_openid,
		'user_ip'=>$_W['clientip'],
		'createtime'=>time(),
	);
	if(pdo_insert($this->tablelooklist, $vipin)){
		exit(json_encode(array('status' => '1', 'msg' => "查看成功")));
	}else{
		exit(json_encode(array('status' => '2', 'msg' => "参数错误3")));
	}
	//来自efwww-com
}else{
	exit(json_encode(array('status' => '1', 'msg' => "已查看过")));
	
}