<?php 
	// 
	//  login.inc.php
	//  <project>
	//  登录操作
	//  Created by Administrator on 2016-09-02.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	
	global $_W,$_GPC;
	
	$openid = m('user') -> getOpenid();
	
	if(CLIENT_IP){
		$ip = CLIENT_IP;
	}else{
		die(json_encode(array('status'=>1,'data'=>'','msg'=>'ip参数错误')));
	}
	
	$ops = array('login','register','check_member' , 'bingding' , 'forget_password' , 'change_password' , 'login_out' , 'get_message' , 'send_code' , 'accreditLogin');
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'ill';
	
	if($op == 'ill'){
		//非法访问
		die(json_encode(array('status'=>1,'data'=>'','msg'=>'参数错误')));
	}
	
	if($op == 'login'){
		if($_W['ispost']){
			//登录
			$account = $_GPC['account'];
			$password = $_GPC['passwords'];
			if(empty($account) || empty($password)){
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'账号或者密码不能为空')));
			}
			
			$checkmember = pdo_fetch("select * from".tablename('weliam_indiana_member')." where uniacid=:uniacid and account=:account",array(':uniacid'=>$_W['uniacid'],':account'=>$account));
			if(!empty($checkmember)){
				//存在用户
				$re = pdo_fetch("select * from".tablename("weliam_indiana_login_session")." where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$account));			//检测是否存在登录信息
				
				if(!empty($re) && time() < $re['locktime']){
					//用户被锁定
					$left_time = ceil(($re['locktime'] - time())/60);
					die(json_encode(array('status'=>1,'data'=>'','msg'=>'账号被锁定，'.$left_time."分钟之后请重新登录")));
				}
				
				if($checkmember['password'] == md5($password.''.$checkmember['salt'])){
					if(!empty($re)){
						pdo_delete("weliam_indiana_login_session",array('uniacid'=>$_W['uniacid'],'openid'=>$account));
					}
					$cookie = array();
					$cookie['account'] = $account;
					$cookie['password'] = md5($password.''.$checkmember['salt']);
					$session = base64_encode(json_encode($cookie));
					if(WL_USER_AGENT == 'yunapp'){
						isetcookie('__login_session', $session, 604800, true);
					}else{
						isetcookie('__login_session', $session, 3600, true);
					}
					die(json_encode(array('status'=>2,'data'=>'','msg'=>'登录成功')));
				}else{
					if(empty($re)){
						$data['openid'] = $account;
						$data['ip'] = $ip;
						$data['updatetime'] = time();
						$data['locktime'] = time()-500;
						$data['status'] = -1;
						$data['uniacid'] = $_W['uniacid'];
						$data['count'] = 1;
						pdo_insert("weliam_indiana_login_session",$data);
					}else{
						$change['count'] = $re['count'] + 1;
						if($change['count'] > 5){
							$change['locktime'] = time()  + ($change['count']-5)*60;
						}
						pdo_update("weliam_indiana_login_session",$change,array('uniacid'=>$_W['uniacid'],'openid'=>$account));
					}
					die(json_encode(array('status'=>1,'data'=>'','msg'=>'密码不正确')));
				}
			}else{
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'该账户不存在')));
			}
		}
		include $this->template('member/login');
	}
	
	if($op == 'accreditLogin'){
		if(!empty($_POST)){
			$token=json_decode($_POST["token"],1);
		    if(!empty($token)){
		    	$checkmember = pdo_fetch("select * from".tablename('weliam_indiana_member')." where uniacid=:uniacid and unionid=:unionid",array(':uniacid'=>$_W['uniacid'],':unionid'=>$token['unionid']));
		    	if(empty($checkmember)){
		    		$checkfans = pdo_fetch("select * from".tablename('mc_mapping_fans')." where uniacid=:uniacid and unionid=:unionid",array(':uniacid'=>$_W['uniacid'],':unionid'=>$token['unionid']));
		    		
		    		$data['unionid'] = $token['unionid'];
		    		$data['openid'] = $token['openid'];
					$data['appopenid'] = $token['openid'];
					$data['uniacid'] = $_W['uniacid'];
					$data['createtime'] = time();
					$data['nickname'] = $token['nickname'];
					$data['gender'] = $token['sex'];
					$data['avatar'] = $token['headimgurl'];
					$data['status'] = 1;
					$data['type'] = 1;
		    		if(empty($checkfans)){
						$datam['uniacid'] = $data['uniacid'];
						$datam['createtime'] = $data['createtime'];
						$datam['nickname'] = $data['nickname'];
						$datam['avatar'] = $data['avatar'];
						if(pdo_insert('mc_members',$datam)){
							$dataf['acid'] = $data['uniacid'];
							$dataf['uniacid'] = $data['uniacid'];
							$dataf['uid'] =	pdo_insertid();
							$dataf['openid'] = $data['openid'];
							$dataf['unionid'] = $data['unionid'];
							$dataf['nickname'] = $data['nickname'];
							$dataf['follow'] = 1;
							$dataf['unfollowtime'] = time();
							$dataf['tag'] = '';
							$dataf['updatetime'] = time();
							pdo_insert('mc_mapping_fans',$dataf);
							pdo_insert('weliam_indiana_member',$data);
						}
		    		}else{
		    			if(pdo_fetch("select * from".tablename('weliam_indiana_member')." where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$checkfans['openid']))){
		    				pdo_update('weliam_indiana_member',array('unionid'=>$token['unionid'],'appopenid'=>$token['openid']),array('uniacid'=>$_W['uniacid'],'openid'=>$checkfans['openid']));
		    			}else{
		    				$data['openid'] = $checkfans['openid'];
		    				pdo_insert('weliam_indiana_member',$data);
		    			}
		    		}
		    	}
		    }
		}else{
		   	header("Location: ".app_url('index/go'));
		}
	}

	if($op == 'register'){
		if($_W['ispost']){
			//注册操作
			$account = $_GPC['account'];
			$password = $_GPC['passwords'];
			
			$recode = $_GPC['recode'];
			$code = $_GPC['code'];
			if(md5($account.$code) != $recode){
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'验证码错误')));
			}
			
			if(preg_match("/^1[345678]{1}\d{9}$/",$account)){
				$member = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid=:uniacid and account=:account",array(':uniacid'=>$_W['uniacid'],':account'=>$account));
				if(empty($member)){
					$salt = '';
				  	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
				   	$max = strlen($strPol)-1;
				   	for($i=0;$i<4;$i++){
				    	$salt.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
				   	}
				   	$data['openid'] = md5($account);
					$data['salt'] = $salt;
					$data['account'] = $account;
					$data['password'] = md5($password.''.$salt);
					$data['uniacid'] = $_W['uniacid'];
					$data['createtime'] = time();
					$data['nickname'] = substr($account, 0 , 3).'****'.substr($account, 7 , 4);
					$data['mobile'] = $account;
					$data['avatar'] = $_W['siteroot'].'addons/weliam_indiana/static/image/default.png';
					$data['status'] = 1;
					$data['type'] = 1;
					
					if(pdo_insert('weliam_indiana_member',$data)){
						$datam['uniacid'] = $data['uniacid'];
						$datam['createtime'] = $data['createtime'];
						$datam['nickname'] = $data['nickname'];
						$datam['avatar'] = $data['avatar'];
						if(pdo_insert('mc_members',$datam)){
							$dataf['acid'] = $data['uniacid'];
							$dataf['uniacid'] = $data['uniacid'];
							$dataf['uid'] =	pdo_insertid();
							$dataf['openid'] = $data['openid'];
							$dataf['unionid'] = $data['openid'];
							$dataf['nickname'] = $data['nickname'];
							$dataf['salt'] = $data['salt'];
							$dataf['follow'] = 1;
							$dataf['unfollowtime'] = time();
							$dataf['tag'] = '';
							$dataf['updatetime'] = time();
							if(pdo_insert('mc_mapping_fans',$dataf)){
								die(json_encode(array('status'=>2,'data'=>'','msg'=>'注册成功')));
							}
						}
					}else{
						die(json_encode(array('status'=>1,'data'=>'','msg'=>'注册失败')));
					}
				}else{
					die(json_encode(array('status'=>1,'data'=>'','msg'=>'该账户已存在')));
				}
			}else{
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'账号不正确')));
			}
		}
		include $this->template('member/register');
	}
	
	if($op == 'bingding'){
		//微信绑定手机账号
		if($_W['ispost']){
			load()->func('communication');
			$account = $_GPC['account'];
			$password = $_GPC['passwords'];
			
			$recode = $_GPC['recode'];
			$code = $_GPC['code'];
			if(md5($account.$code) != $recode){
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'验证码错误')));
			}
			
			if(preg_match("/^1[345678]{1}\d{9}$/",$account)){
				$member = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid=:uniacid and account=:account",array(':uniacid'=>$_W['uniacid'],':account'=>$account));
				if(empty($member) || $member['openid'] == md5($account)){
					$salt = '';
				  	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
				   	$max = strlen($strPol)-1;
				   	for($i=0;$i<4;$i++){
				    	$salt.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
				   	}
				   	
					if(empty($openid)){
						die(json_encode(array('status'=>1,'data'=>'','msg'=>'参数获取不正确')));
					}
					$data['salt'] = $salt;
					$data['account'] = $account;
					$data['mobile'] = $account;
					$data['password'] = md5($password.''.$salt);
					if(pdo_update('weliam_indiana_member',$data,array('uniacid'=>$_W['uniacid'],'openid'=>$openid))){
						if($member['openid'] == md5($account)){
							$re = ihttp_request($_W["siteroot"].'addons/weliam_indiana/core/api/dataCombine.api.php', array('mainid' => $openid,'combineid'=>md5($account),'uniacid'=>$_W['uniacid']),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
							m('log')->WL_log('datacombine','数据检查',$re,$_W['uniacid']);
						}
						die(json_encode(array('status'=>2,'data'=>'','msg'=>'绑定账号成功')));
					}else{
						die(json_encode(array('status'=>1,'data'=>'','msg'=>'绑定失败')));
					}
				}else{
					die(json_encode(array('status'=>1,'data'=>'','msg'=>'该账户已被绑定')));
				}
			}else{
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'账号不正确')));
			}
		}
		include $this->template('member/bingding');
	}
	
	if($op == 'forget_password'){
		//忘记密码
		if($_W['ispost']){
			$account = $_GPC['account'];
			$password = $_GPC['passwords'];
			
			$recode = $_GPC['recode'];
			$code = $_GPC['code'];
			if(md5($account.$code) != $recode){
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'验证码错误')));
			}
			
			if(preg_match("/^1[345678]{1}\d{9}$/",$account)){
				$member = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid=:uniacid and account=:account",array(':uniacid'=>$_W['uniacid'],':account'=>$account));
				if(!empty($member)){
					$data['password'] = md5($password.''.$member['salt']);
					
					if(pdo_update('weliam_indiana_member',$data,array('uniacid'=>$_W['uniacid'],'openid'=>$member['openid']))){
						die(json_encode(array('status'=>2,'data'=>'','msg'=>'密码修改成功')));
					}else{
						die(json_encode(array('status'=>1,'data'=>'','msg'=>'密码修改失败')));
					}
				}else{
					die(json_encode(array('status'=>1,'data'=>'','msg'=>'该账户不存在')));
				}
			}else{
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'账号不正确')));
			}
		}
		include $this->template('member/forget_password');
	}
	
	if($op == 'check_member'){
		//检测用户头像和用户信息
		$account = $_GPC['account'];
		if(preg_match("/^1[345678]{1}\d{9}$/",$account)){
			$member = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid=:uniacid and account=:account",array(':uniacid'=>$_W['uniacid'],':account'=>$account));
			if(empty($member)){
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'该账户不存在')));
			}else{
				$member['avatar'] = tomedia($member['avatar']);
				die(json_encode(array('status'=>2,'data'=>$member,'msg'=>'查询成功')));
			}
		}else{
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'账号不正确')));
		}
	}
	
	if($op == 'get_message'){
		//获取当前用户的信息
		$member = pdo_fetch("select avatar,mid from".tablename('weliam_indiana_member')."where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		$member['avatar'] = tomedia($member['avatar']);
		die(json_encode(array('status'=>2,'data'=>$member,'msg'=>'查询成功')));
	}

	if($op == 'change_password'){
		//修改密码
		$account = $_GPC['account'];
		$password = $_GPC['password'];
		
		$recode = $_GPC['recode'];
		$code = $_GPC['code'];
		if(md5($account.$code) != $recode){
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'验证码错误')));
		}
		
		if(preg_match("/^1[345678]{1}\d{9}$/",$account)){
			$member = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid=:uniacid and account=:account",array(':uniacid'=>$_W['uniacid'],':account'=>$account));
			if(empty($member)){
				$data['account'] = $account;
				$data['password'] = md5($password.''.$member['salt']);
				
				if(pdo_update('weliam_indiana_member',$data,array('uniacid'=>$_W['uniacid'],'openid'=>$member['openid']))){
					die(json_encode(array('status'=>2,'data'=>'','msg'=>'绑定账号成功')));
				}else{
					die(json_encode(array('status'=>1,'data'=>'','msg'=>'绑定失败')));
				}
			}else{
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'该账户已存在')));
			}
		}else{
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'账号不正确')));
		}
	}

	if($op == 'login_out'){
		//账户登出
		isetcookie('__login_session', '',0, true);
		die(json_encode(array('status'=>2,'data'=>'','msg'=>'登出成功')));
	}
	
	if($op == 'send_code'){
		//发送验证码
		$account = $_GPC['mobile'];
		if(preg_match("/^1[345678]{1}\d{9}$/",$account)){
			$code = rand(1000,9999);
			$res = m('sms')->smsSF($code,$account);
			if($res['result']['success'] == 1){
				die(json_encode(array('status'=>2,'code'=>md5($account.$code),'msg'=>'发送成功')));
			}else{
				die(json_encode(array('status'=>1,'code'=>'','msg'=>$res)));
			}
		}else{
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'手机号码格式不正确')));
		}
	}
