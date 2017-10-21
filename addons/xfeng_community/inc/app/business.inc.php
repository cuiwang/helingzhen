<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 独立商家
 */

defined('IN_IA') or exit('Access Denied');

	global $_W,$_GPC;
	$title = '小区商家';
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'app';

	if($op == 'login'){
		//商家管理后台独立页面	
		if (checksubmit('submit')) {
			$mobile = trim($_GPC['mobile']);
			$password = md5(trim($_GPC['password']));
			$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_business')."WHERE weid=:weid AND mobile=:mobile",array(':mobile' => $mobile,':weid' => $_W['uniacid']));
			if (empty($member['status'])) {
				message('您还没有开通小区商家，请联系小区管理员开通',referer(),'error');
				exit();
			}
			if ($member['password'] == $password) {
				$url = $this->createMobileUrl('business',array('op' => 'home','mobile'=>$mobile));
				header("Location:$url");exit();
			}else{
				message('密码错误',referer(),'error');
			}
		}

		include $this->template('business_login');
	}elseif($op == 'loginup'){
		//独立商家注册页面
		// load()->classs('wesession');
		// WeSession::$expire = 600;	
		// WeSession::start();	
		if ($_W['ispost']) {
			if ($_GPC['verifycode']) {
				if ($_GPC['verifycode'] == $_SESSION['code']) {
					$result = 1;
					include $this->template('business_loginup');
					exit();
				}else{
					message('验证码不对,请重新输入');
				} 
			}	
			$data = array(
				'weid'       => $_W['uniacid'],
				'mobile'     => $_GPC['mobile'],
				'username'   => $_GPC['username'],
				'password'   => md5(trim($_GPC['password'])),
				'createtime' => TIMESTAMP,
			);
			pdo_insert('xcommunity_business',$data);
			message("注册成功,联系管理员开通",$this->createMobileUrl('business',array('op' => 'login')));		
		}
		include $this->template('business_loginup');
	}elseif($op == 'home'){
		//独立商家登录后页面
		$mobile = $_GPC['mobile'];
		$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_business')."WHERE mobile=:mobile",array(':mobile' => $mobile));
		include $this->template('business_home');
	}elseif($op == 'setting'){
		$id = intval($_GPC['uid']);
		$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_business')."WHERE id=:id",array(':id' => $id));
		$data = array(
				'mobile' => $_GPC['mobile'],
				'username' => $_GPC['username'],
				'qq' => $_GPC['qq'],
			);
		if ($_W['ispost']) {
			pdo_update("xcommunity_business",$data,array('id' => $id));
			message('修改成功',referer(),'success');
		}
		include $this->template('business_setting');
	}elseif($op == 'pwd'){
		$id = intval($_GPC['id']);
		$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_business')."WHERE id=:id",array(':id' => $id));
		$oldpwd = md5($_GPC['oldpwd']);
		if ($_W['ispost']) {
			if ($oldpwd == $member['password']) {
				if ($_GPC['newpwd1'] == $_GPC['newpwd2']) {
					$password = md5($_GPC['newpwd1']);
					pdo_query("UPDATE ".tablename('xcommunity_business')."SET password='{$password}' WHERE id=:id",array(':id' => $_GPC['id']));
					message('修改成功',referer(),'success');
				}else{
					message('两次密码不相同');
				}
			}else{
				message('原密码不正确,请重新输入',$this->createMobileUrl('business',array('op' => 'login')),'success');
			}
		}
		include $this->template('business_pwd');
	}elseif($op == 'dpadd'){
		$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_business')."WHERE id=:uid",array(':uid' => $_GPC['uid']));
		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['uniacid']}'");
		$uid = intval($_GPC['uid']);
		if ($_GPC['id']) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_sjdp')."WHERE id=:id",array(':id' => $_GPC['id']));
			$regs = unserialize($item['regionid']);
		}
		$data = array(
				'weid'          => $_W['uniacid'],
				'uid'           => $uid,
				'regionid'      => serialize($_GPC['regionid']),
				'sjname'        => $_GPC['sjname'],
				'picurl'        => $_GPC['picurl'],
				'contactname'   => $_GPC['contactname'],
				'mobile'        => $_GPC['mobile'],
				'phone'			=> $_GPC['phone'],
				'qq'            => $_GPC['qq'],
				'province'      => $_GPC['birth']['province'],
				'city'       	=> $_GPC['birth']['city'],
				'dist'       	=> $_GPC['birth']['district'],
				'address'		=> $_GPC['address'],
				'shopdesc'      => htmlspecialchars_decode($_GPC['shopdesc']),
				'businnesstime' => $_GPC['businnesstime'],
				'businessurl'   => $_GPC['businessurl'],
				'createtime'    => TIMESTAMP,
			);
		if ($_W['ispost']) {
			if ($_GPC['id']) {
				pdo_update("xcommunity_sjdp",$data,array('id' => $_GPC['id']));
				message('修改成功',$this->createMobileUrl('business',array('op' =>'dplist' ,'uid' => $uid)),'success');
			}else{
				pdo_insert("xcommunity_sjdp",$data);
				message('增加成功',referer(),'success');
			}
		}

		include $this->template('business_dp_add');
	}elseif($op == 'dplist'){
		$uid = intval($_GPC['uid']);
		$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_business')."WHERE id=:uid AND weid='{$_W['uniacid']}'",array(':uid' => $uid));
		$sjdps = pdo_fetchall("SELECT * FROM".tablename('xcommunity_sjdp')."WHERE uid=:uid AND weid='{$_W['uniacid']}'",array(':uid' => $uid));
		include $this->template('business_dp_list');
	}elseif($op == 'delete'){
		pdo_delete("xcommunity_sjdp",array('id' => $_GPC['id']));
		message("删除成功",referer(),'success');

	}elseif($op == 'app' || $op == 'more'){
		//微信端商家展示
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		$member = $this->changemember();
		$rows   = pdo_fetchall("select * from".tablename('xcommunity_sjdp')."where weid = '{$_W['uniacid']}' LIMIT ".($pindex - 1) * $psize.','.$psize);
		$list = array();
		foreach ($rows as $key => $value) {
			$regions = unserialize($value['regionid']);
			if (@in_array($member['regionid'], $regions)) {
				$list[$key]['sjname'] = $value['sjname'];
				$list[$key]['address'] = $value['address'];
				$list[$key]['mobile'] = $value['mobile'];
				$list[$key]['id'] = $value['id'];
				$list[$key]['picurl'] = $value['picurl'];
				$list[$key]['businessurl'] = $value['businessurl'];
			}
		}

		if ($op == 'more') {
			include $this->template('business_app_more');
			exit();
		}	

		include $this->template('business_app');
	}elseif ($op == 'detail') {
		//微信端商家内容页
		$id = intval($_GPC['id']);
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_sjdp')."WHERE id=:id",array(':id' => $id));
		}
		include $this->template('business_app_detail');
	}















