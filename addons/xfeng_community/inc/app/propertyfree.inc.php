<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端 物业费查询
 */
load()->classs('wesession');
defined('IN_IA') or exit('Access Denied');

	global $_W,$_GPC;
	$title = '物业费查询';
	$op = !empty($_GPC['op'])?$_GPC['op']:'display';
	WeSession::start($_W['uniacid'],$_W['fans']['from_user'],60);
	$member = $this->changemember();
	$region = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE id='{$member['regionid']}'");  
	if ($op == 'display') {
		//查当前公众号下面的费用时间
		$list = pdo_fetchAll("SELECT * FROM".tablename('xcommunity_protime')."WHERE weid='{$_W['weid']}'");
		include $this->template('propertyfree');
	}
	
	if ($op == 'ajax') {
		$protime = $_GPC['ym'];
		$pro = pdo_fetch("SELECT * FROM".tablename('xcommunity_protime')."WHERE protime=:protime AND weid='{$_W['weid']}'",array(':protime' => $protime));

		$profrees = pdo_fetchAll("SELECT * FROM".tablename('xcommunity_propertyfree')."WHERE protimeid='{$pro['id']}' AND mobile='{$member['mobile']}'");
		
		$result = array(
				'data'   => $profrees,
				'info'   => "成功",
				'status' => 1,
			);
		print_r(json_encode($result));
	}elseif($op == 'pay'){
		$pid = intval($_GPC['pid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_propertyfree') . " WHERE id = :id", array(':id' => $pid));
		if ($order['status'] != '0') {
			message('抱歉，您的物业费已缴纳！', referer(), 'success');
		}
		
		$params['tid'] = $pid;
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['profree']+$order['tcf']+$order['gtsf']+$order['gtdf'];
		$params['title'] = '物业总费用';
		$_SESSION['type'] = 'profree';



		include $this->template('cost_pay');
	}