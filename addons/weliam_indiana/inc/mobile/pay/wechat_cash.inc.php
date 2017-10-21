<?php 
// 
//  wechat_cash.inc.php
//  <project>
//  重写微信支付
//  Created by Administrator on 2016-07-12.
//  Copyright 2016 Administrator. All rights reserved.
// 


	defined('IN_IA') or exit('Access Denied');
	$moduels = uni_modules();
	
	$params = @json_decode(base64_decode($_GPC['params']), true);
	if(empty($params) || !array_key_exists($params['module'], $moduels)) {
		message('访问错误.');
	}
	$setting = uni_setting($_W['uniacid'], 'payment');
	$dos = array();
	
	if(!empty($setting['payment']['wechat']['switch'])) {
		$dos[] = 'wechat_cash';
	}
	
	$do = $_GET['do'];
	$type = in_array($do, $dos) ? $do : '';
	if(empty($type)) {
		message('支付方式错误,请联系商家', '', 'error');
	}
	
	if(!empty($type)){
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
		$pars  = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
		$log = pdo_fetch($sql, $pars);
		if(!empty($log) && ($type != 'credit' && !empty($_GPC['notify'])) && $log['status'] != '0') {
			message('这个订单已经支付成功, 不需要重复支付.');
		}
		$moduleid = pdo_fetchcolumn("SELECT mid FROM ".tablename('modules')." WHERE name = :name", array(':name' => $params['module']));
		$moduleid = empty($moduleid) ? '000000' : sprintf("%06d", $moduleid);
		$fee = $params['fee'];
		
		$record = array();
		$record['type'] = $type;
		$record['uniontid'] = date('YmdHis').$moduleid.random(8,1);
		
		if (empty($log)) {
			if(pdo_insert('core_paylog', $record)) {
				$plid = pdo_insertid();
				$record['plid'] = $plid;
				$log = $record;
			} else {
				message('系统错误, 请稍后重试.');
			}
		} else {
			pdo_update('core_paylog', $record, array('plid' => $log['plid']));
		}
		
		$ps = array();
		$ps['tid'] = $log['plid'];
		$ps['uniontid'] = $log['uniontid'];
		$ps['user'] = $_W['fans']['from_user'];
		$ps['fee'] = $log['card_fee'];
		$ps['title'] = $params['title'];
		
		if($type == 'wechat_cash') {
			if(!empty($plid)) {
				$tag = array();
				$tag['acid'] = $_W['acid'];
				$tag['uid'] = $_W['member']['uid'];
				pdo_update('core_paylog', array('openid' => $_W['openid'], 'tag' => iserializer($tag)), array('plid' => $plid));
			}
			load()->func('communication');
			$sl = base64_encode(json_encode($ps));
			$auth = sha1($sl . $_W['uniacid'] . $_W['config']['setting']['authkey']);
			header("location: ".$this->createMobileUrl('wechat_pay')."&i={$_W['uniacid']}&auth={$auth}&ps={$sl}");
			exit();
		}
	}else{
		message('没有有效的支付方式', '', 'error');
	}
