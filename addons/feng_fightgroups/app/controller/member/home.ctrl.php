<?php
/**
 * [weliam] Copyright (c) 2016/3/23
 * index.ctrl
 * 个人中心控制器
 */
defined('IN_IA') or exit('Access Denied');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$pagetitle = !empty($config['tginfo']['sname']) ? '个人中心 - '.$config['tginfo']['sname'] : '个人中心';
$time = time();
$scratch = pdo_fetch("select * from".tablename('tg_scratch')."where status=1 and uniacid={$_W['uniacid']} and starttime<'{$time}' and endtime>'{$time}'");
if($op =='display'){
	$member = getMember($openid);
	if(!$member['credit1']){
		$member['credit1'] = '0.00';
	}
	if(!$member['credit2']){
		$member['credit2'] = '0.00';
	}
	$time = time();
	$tatal1 = pdo_fetchall("select openid from".tablename('tg_coupon')."where openid='{$_W['openid']}' and uniacid={$_W['uniacid']} and use_time=0 AND `start_time` < '{$time}' AND `end_time` > '{$time}'");
	$tatal = count($tatal1);
	include wl_template('member/home');
}

if($op == 'activity'){
	$data  =   pdo_fetchall("select * from".tablename('tg_scratch_record')."where uniacid={$_W['uniacid']} and openid='{$_W['openid']}'");
	$i = 0;
	foreach($data as$key=>&$value){
		$data1 =   pdo_fetch("select * from".tablename('tg_scratch')."where uniacid={$_W['uniacid']} and id={$value['activity_id']}");
		$value['name']  = $data1['name'];
		$value['createtime']  = date('Y-m-d H:i:s',$value['createtime']);
	}
//	wl_debug($data);
	include wl_template('member/activity_list');
}
if($op == 'app'){
	include wl_template('member/app_login');
}