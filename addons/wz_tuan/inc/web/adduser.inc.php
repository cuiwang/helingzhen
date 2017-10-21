<?php
		global $_W,$_GPC;
		load()->func('tpl');
		$uniacid=$_W['uniacid'];
		$op = $_GPC['op']?$_GPC['op']:'display';
		if ($op == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$goodses = pdo_fetchall("SELECT * FROM ".tablename('wz_tuan_user')." WHERE uniacid = '{$_W['uniacid']}' ORDER BY uid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_user') . " WHERE uniacid = '{$_W['uniacid']}'");
			$pager = pagination($total, $pindex, $psize);
			include $this->template('web/user');
		}elseif ($op == 'add') {
			$uid = intval($_GPC['uid']);
			if(!empty($uid)){
				$sql = 'SELECT * FROM '.tablename('wz_tuan_user').' WHERE uid=:uid AND uniacid=:uniacid LIMIT 1';
				$params = array(':uid'=>$uid, ':uniacid'=>$_W['uniacid']);
				$goods = pdo_fetch($sql, $params);
				if(empty($goods)){
					message('未找到指定的商户.', $this->createWebUrl('web/user'));
				}
			}
			if (checksubmit()) {
				$data = $_GPC['goods']; // 获取打包值
				empty($data['title']) && message('请填写名称');
				empty($data['username']) && message('请填写登陆账号');
				empty($data['password']) && message('请填写登陆密码');
				if(empty($goods)){
					//添加管理人员
					load()->model('user');
					$user = array();
					$user['username'] = trim($data['username']);
					if(!preg_match(REGULAR_USERNAME, $user['username'])) {
						message('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
					}

					if(user_check(array('username' => $user['username']))) {
						message('非常抱歉，此用户名已经被注册，你需要更换注册名称！');
					}

					$user['password'] = $data['password'];
					if(istrlen($user['password']) < 8) {
						message('必须输入密码，且密码长度不得低于8位。');
					}

					$user['remark'] = '';
					$user['groupid'] = 1;
					$uid = user_register($user);
					//分配权限
					$per_data['uniacid'] = $_W['uniacid'];
					$per_data['uid'] = $uid;
					$per_data['type'] = 'sendoprate';
					$per_data['permission'] = 'all';
					$ret = pdo_insert('users_permission', $per_data);
					
					$per_data2['uniacid'] = $_W['uniacid'];
					$per_data2['uid'] = $uid;
					$per_data2['type'] = 'system';
					$per_data2['permission'] = 'profile_module';
					$ret = pdo_insert('users_permission', $per_data2);
					
					//分配所属公众号
					$acc_data['uniacid'] = $_W['uniacid'];
					$acc_data['uid'] = $uid;
					$acc_data['role'] = 'operator';
					$ret = pdo_insert('uni_account_users', $acc_data);
					//保存商户信息
					$bus_data['uniacid'] = $_W['uniacid'];
					$bus_data['title'] = $data['title'];
					$bus_data['uid'] = $uid;
					$ret = pdo_insert('wz_tuan_user', $bus_data);
				} else {
					$bus_data['title'] = $data['title'];
					$bus_data['bond'] = $data['bond'];
					$bus_data['status'] = $data['status'];
					$ret = pdo_update('wz_tuan_user', $bus_data, array('uid'=>$uid));
				}

				if (!empty($ret)) {
					message('商户信息保存成功', $this->createWebUrl('web/user', array('op'=>'add', 'uid'=>$uid)), 'success');
				} else {
					message('商户信息保存失败');
				}
			}
			include $this->template('web/useradd');
		}elseif ($op == 'delete') {
			$uid = intval($_GPC['uid']);
			if(empty($uid)){
				message('未找到指定商户');
			}

			$result = pdo_delete('users', array('uid'=>$uid));
			$result = pdo_delete('users_permission', array('uid'=>$uid));
			$result = pdo_delete('uni_account_users', array('uid'=>$uid));
			$result = pdo_delete('wz_tuan_user', array('uid'=>$uid, 'uniacid'=>$_W['uniacid']));
			if(intval($result) == 1){
				message('删除商户成功.', $this->createWebUrl('web/user'), 'success');
			} else {
				message('删除商户失败.');
			}
		}
	
?>