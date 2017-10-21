<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$sql = "SELECT * FROM ". tablename('modules')."WHERE issystem=0 and type='business' order by price desc";
$business = pdo_fetchall($sql);
$sql = "SELECT * FROM ". tablename('modules')."WHERE issystem=0 and type='biz' order by price desc";
$biz = pdo_fetchall($sql);
$sql = "SELECT * FROM ". tablename('modules')."WHERE issystem=0 and type='activity' order by price desc";
$activity = pdo_fetchall($sql);
$sql = "SELECT * FROM ". tablename('modules')."WHERE issystem=0 and type='wdlgame' order by price desc";
$wdlgame = pdo_fetchall($sql);
$sql = "SELECT * FROM ". tablename('modules')."WHERE issystem=0 and type='wdlshow' order by price desc";
$wdlshow = pdo_fetchall($sql);
$sql = "SELECT * FROM ". tablename('modules')."WHERE issystem=0 and type='customer' order by price desc";
$customer = pdo_fetchall($sql);
$sql = "SELECT * FROM ". tablename('modules')."WHERE issystem=0 and type='other' order by price desc";
$other = pdo_fetchall($sql);
$pars=array();
$taocan = pdo_fetchall("SELECT * FROM ".tablename('users_group'),$pars,"id");
$jichu=getgroupmodules($_W['setting']['taocan']['jichuid']);
$shangye=getgroupmodules($_W['setting']['taocan']['shangyeid']);
$hangye=getgroupmodules($_W['setting']['taocan']['hangyeid']);
$zhizun=getgroupmodules($_W['setting']['taocan']['zhizunid']);
if (empty($jichu)){
	$jichu=array();
}
if (empty($shangye)){
	$shangye=array();
}
if (empty($hangye)){
	$hangye=array();
}
if (empty($zhizun)){
	$zhizun=array();
}
template('website/taocan/baojia');
