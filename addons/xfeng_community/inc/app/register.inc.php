<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端注册页面
 */
load()->classs('wesession');
defined('IN_IA') or exit('Access Denied');
	global $_GPC,$_W;
	//个人信息
	$title = '用户注册';
	WeSession::start($_W['uniacid'],$_W['fans']['from_user'],60);
	//判断验证码是否正确
	if($this->module['config']['verifycode']){
		if($_W['ispost'] && empty($_SESSION['isstatus'])){
				$verifycode = $_GPC['verifycode'];
				if($verifycode == $_SESSION['code']){
					$_SESSION['isstatus']=1;
					$forward = $this->createMobileUrl('register');
					header("Location:{$forward}");exit();
					
				}else{
					message('验证码失效,请重新获取！',referer(),'success');exit();
				}
		}
	}
	//查询已添加的小区
	$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
	if(checksubmit('submit')){
		//status=1  显示注册成功， status=0 解绑
		if ($this->module['config']['verify']) {
			$status = 0;
		}else{
			$status = 1;
		}
		$data =array(
			'weid'          =>$_W['weid'],
			'realname'      =>$_GPC['realname'],
			'regionid'      =>$_GPC['regionid'],
			'openid'        =>$_W['fans']['from_user'],
			'mobile'        =>$_GPC['mobile'],
			'regionname'    =>$item['title'],
			'address'       =>$_GPC['address'],
			'remark'        =>$_GPC['remark'],
			'manage_status' =>0,
			'status'		=>$status,
			'createtime'    =>$_W['timestamp'],
		);
		if ($_GPC['id']) {
			pdo_update('xcommunity_member',$data,array('id' => $_GPC['id']));
			message('修改成功！',$this->createMobileUrl('member'),'success');
		}else{
			pdo_insert('xcommunity_member',$data);
			message('账户绑定成功,您可以正常操作提供的物业服务！',$this->createMobileUrl('home'),'success');
		}
		
	}
	include $this->template('register');