<?php
	global $_GPC, $_W;
	$weid = $_W['uniacid'];

	$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_car_setting) . " WHERE weid={$weid} LIMIT 1");
	if(empty($setting)){
		message("请先配置相关数据！", $this->createWebUrl('setting'), "error");
	}

	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	$psize = 15;
	$pindex = max(1, intval($_GPC['page']));
   
	if($op=='display'){
		$condition = " weid = '{$weid}' ";
		if (!empty($_GPC['time'])) {
			$starttime = strtotime($_GPC['time']['start']);
			$endtime   = strtotime($_GPC['time']['end']);
		}else{
			$starttime = strtotime('-1 month');
			$endtime   = time();
		}
		if ($_GPC['searchtime'] == '1') {
			$condition .= " AND add_time >= '{$starttime}' AND add_time <= '{$endtime}' ";
		}
		
		if(!empty($_GPC['account'])){
		   $condition .= " AND account LIKE '%{$_GPC['account']}%' ";
		}
		if(!empty($_GPC['status'])){
		   $condition .= " AND status = '{$_GPC['status']}' ";
		}
		if(!empty($_GPC['contact'])){
		   $condition .= " AND contact LIKE '%{$_GPC['contact']}%' ";
		}
		
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_car_account) . " WHERE {$condition} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_car_account) . " WHERE {$condition}");
		$pager = pagination($total, $pindex, $psize);
		
	}elseif($op=='post'){
		$uid = $_GPC['uid'];
		if(!empty($uid)){
			$item = pdo_fetch("SELECT * FROM " . tablename($this->table_car_account) . " WHERE weid = '{$weid}' AND id = '{$uid}'");
		}

		if(checksubmit('submit')){
			$data = array(
				'weid'     => $weid,
				'account'  => trim($_GPC['account']),
				'password' => md5($_GPC['password']),
				'contact'  => trim($_GPC['contact']),
				'mobile'   => trim($_GPC['mobile']),
				'alipay'   => trim($_GPC['alipay']),
				'realname' => trim($_GPC['realname']),
				'status'   => intval($_GPC['status']),
				'add_time' => time(),
			);
			if(empty($data['account'])){
				message("请输入登录帐号！");
			}elseif(strlen($data['account'])<3 || strlen($data['account'])>12){
				message("登录帐号长度为3~12位！");
			}
			if(empty($data['contact'])){
				message("请输入联系人！");
			}
			if(empty($data['mobile'])){
				message("请输入联系电话！");
			}
			if(empty($data['status'])){
				message("请选择帐号状态！");
			}

			if(empty($uid)){//新增
				$checkAccount = pdo_fetch("SELECT * FROM " . tablename($this->table_car_account) . " WHERE weid = '{$weid}' AND account = '{$data['account']}'");
				if(!empty($checkAccount)){
					message("登录帐号已存在！");
					}
				if(empty($_GPC['password'])){
					message("请输入登录密码！");
				}elseif(strlen($_GPC['password'])<6 || strlen($_GPC['password'])>20){
					message("登录密码长度为6~20位！");
				}elseif($_GPC['password'] != $_GPC['confirm_password']){
					message("两次密码不一致！");
				}

				$result = pdo_insert("fy_car_account", $data);
				if($result){
					message("创建帐号成功！", $this->createWebUrl('account'), "success");
				}else{
					message("创建帐号失败！", referer, "error");
				}
			}else{//更新
				if(empty($_GPC['password'])){
					unset($data['password']);
					unset($data['confirm_password']);
				}else{
					if(strlen($_GPC['password'])<6 || strlen($_GPC['password'])>20){
						message("登录密码长度为6~20位！");
					}elseif($_GPC['password'] != $_GPC['confirm_password']){
						message("两次密码不一致！");
					}
				}
				unset($data['account']);
				$result = pdo_update("fy_car_account", $data, array('id'=>$uid));
				if($result){
					message("更新帐号成功！", $this->createWebUrl('account'), "success");
				}else{
					message("更新帐号失败！", referer, "error");
				}
			}
		}
	}elseif($op=='delete'){
	   $uid = $_GPC['uid'];
	   $item = pdo_fetch("SELECT * FROM " . tablename($this->table_car_account) . " WHERE weid = '{$weid}' AND id = '{$uid}'");
	   if(empty($item)){
		   message("该帐号不存在！");
	   }

	   $result = pdo_delete("fy_car_account", array('id'=>$uid));
	   if($result){
		   message("删除帐号成功！", $this->createWebUrl('account'), "success");
	   }else{
		   message("删除帐号失败！", referer, "error");
	   }
   }
   

   include $this->template('web/account');

