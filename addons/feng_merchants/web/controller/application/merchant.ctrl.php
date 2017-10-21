<?php
	wl_load()->model('merchant');
	$ops = array('display','edit', 'delete', 'account_display','account','record','data','permissions','search');
	$op_names = array('商家列表','编辑/添加商家', '删除', '商家中心管理','商家结算中心','结算记录','订单统计','权限管理','查找粉丝');
	foreach($ops as$key=>$value){
		permissions('do', 'ac', 'op', 'application', 'merchant', $ops[$key], '应用与营销', '商家管理', $op_names[$key]);
	}
	$op = in_array($op, $ops) ? $op : 'display';
	//商家列表显示
	if($op == 'display'){
		$uniacid=$_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$merchants = pdo_fetchall("SELECT * FROM ".tablename('tg_merchant')." WHERE uniacid = {$uniacid} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_merchant') . " WHERE uniacid = '{$uniacid}'");
		$pager = pagination($total, $pindex, $psize);
		include wl_template('application/merchant/merchant_list');
	}
	if($op == 'search'){
		$con     = "uniacid='{$_W['uniacid']}' ";
		$keyword = $_GPC['keyword'];
		$type = $_GPC['t'];
		if ($keyword != '') {
			$con .= " and nickname LIKE '%{$keyword}%'";
		}  
		$ds = pdo_fetchall("select * from" . tablename('tg_member') . "where $con");
		include wl_template('application/merchant/select_merchanter');
		exit;
	}
	//商家编辑
	if ($op == 'edit') {
		$id = intval($_GPC['id']);
		if(!empty($id)){
			$sql = 'SELECT * FROM '.tablename('tg_merchant').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
			$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
			$merchant = pdo_fetch($sql, $params);
			$saler = pdo_fetch("select * from" . tablename('tg_member') . "where uniacid={$_W['uniacid']} and openid='{$merchant['openid']}'");
			$messagesaler = pdo_fetch("select * from" . tablename('tg_member') . "where uniacid={$_W['uniacid']} and openid='{$merchant['messageopenid']}'");
			if(empty($merchant)){
				message('商家信息不存在', web_url('application/merchant', array('op'=>'display')), 'success');
			}
		}
		
		if (checksubmit()) {
			$data = $_GPC['merchant']; // 获取打包值
			$data['detail'] = htmlspecialchars_decode($data['detail']);
			$data['openid'] = $_GPC['openid']; 
			$data['messageopenid'] = $_GPC['messageopenid'];
			if(empty($merchant)){
				$data['uniacid'] = $_W['uniacid'];
				$data['createtime'] = TIMESTAMP;
				  
				if($data['open']==1){
					load()->model('user');
					if(!preg_match(REGULAR_USERNAME, $data['uname'])) {
						message('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
					}
					if(user_check(array('username' => $data['uname']))) {
						message('非常抱歉，此用户名已经被注册，你需要更换注册名称！');
					}else{
						$tpwd = trim($_GPC['tpwd']);
						$data['password'] = trim($data['password']);
						if(empty($data['password']) || empty($tpwd)){
							message('密码不能为空！');exit;
						}
						if($data['password']!=$tpwd){
								message('两次密码输入不一致！');exit;
						}
						if(istrlen($data['password']) < 8) {
							message('必须输入密码，且密码长度不得低于8位。');exit;
						}
						/*生成用户*/
						$user = array();
						$user['salt'] = random(8);
						$user['username'] = $data['uname'];
						$user['password'] = user_hash($data['password'], $user['salt']);
						$user['groupid'] = 1;
						$user['joinip'] = CLIENT_IP;
						$user['joindate'] = TIMESTAMP;
						$user['lastip'] = CLIENT_IP;
						$user['lastvisit'] = TIMESTAMP;
						if (empty($user['status'])) {
							$user['status'] = 2;
						}
						$result = pdo_insert('users', $user);
						$uid = pdo_insertid();
						$data['uid'] = $uid;
						/*分配模块*/
						$m = array();
						$m['uniacid'] = $_W['uniacid'];
						$m['uid'] = $uid;
						$m['type'] = 'feng_merchants';
						$m['permission'] = 'all';
						$result = pdo_insert('users_permission', $m);
						/*添加操作员*/
						 pdo_insert('uni_account_users', array('uniacid'=>$_W['uniacid'],'uid'=>$uid,'role'=>'operator'));
					}
				}
				$ret = pdo_insert('tg_merchant', $data);
			} else {
				$ret = pdo_update('tg_merchant', $data, array('id'=>$id));
				$user = pdo_fetch("select * from".tablename("users")."where uid=:uid",array(':uid'=>$merchant['uid']));
				$opwd = trim($_GPC['opwd']);
				$npwd = trim($_GPC['npwd']);
				$tpwd = trim($_GPC['tpwd']);
				if($data['open']==2){
					$ret = pdo_update('users', array('status'=>1), array('uid'=>$merchant['uid']));
				}else{
					if(empty($opwd) || empty($npwd)|| empty($tpwd)){
						
					}else{
						if($opwd!=$merchant['password']){
							message('原密码错误！');exit;
						}else{
							if($npwd!=$tpwd){
								message('两次密码输入不一致！');exit;
							}
						}
						if(istrlen($npwd) < 8) {
							message('必须输入密码，且密码长度不得低于8位。');exit;
						}
						$p = user_hash($npwd, $user['salt']);
						$ret = pdo_update('users', array('password'=> $p,'status'=>2), array('uid'=>$merchant['uid']));
					}
					
				}
			}
			
			if (!empty($ret)) {
				message('商家信息保存成功', web_url('application/merchant', array('op'=>'display', 'id'=>$id)), 'success');
			} else {
				message('商家信息保存失败');
			}
		}
		
		include wl_template('application/merchant/merchant_list');
	}
	if($op == 'delete') {
		$id = intval($_GPC['id']);
		$sql = 'SELECT * FROM '.tablename('tg_merchant').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
		$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
		$merchant = pdo_fetch($sql, $params);
		if(empty($id)){
			message('未找到指定商家分类');
		}
		$result = pdo_delete('tg_merchant', array('id'=>$id, 'uniacid'=>$_W['uniacid']));
		if(intval($result) == 1){
			pdo_delete('users', array('uid'=>$merchant['uid']));
			message('删除商家成功.', web_url('application/merchant'), 'success');
		} else {
			message('删除商家失败.');
		}
	}
	if($op == 'account_display'){
		$uniacid=$_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$merchants = pdo_fetchall("SELECT * FROM ".tablename('tg_merchant')." WHERE uniacid = {$uniacid} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		foreach($merchants as$key=>$value){
			$account =  pdo_fetch("SELECT amount,no_money FROM ".tablename('tg_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid={$value['id']}");
			$merchants[$key]['amount'] = $account['amount'];
			$merchants[$key]['no_money'] = $account['no_money'];
		}
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_merchant') . " WHERE uniacid = '{$uniacid}'");
		$pager = pagination($total, $pindex, $psize);
		include wl_template('application/merchant/account');
	}
	if ($op == 'account') {
		$id = $_GPC['id'];
		$merchant = pdo_fetch("SELECT * FROM ".tablename('tg_merchant')." WHERE uniacid = {$_W['uniacid']} and id={$id}");
		$account =  pdo_fetch("SELECT amount,no_money FROM ".tablename('tg_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid={$id}");
		if (checksubmit('submit')) {
			$money = $_GPC['money'];
			$mm = $money;
			if(!empty($merchant['percent'])){
				$money = (1-$merchant['percent']*0.01)*$money;
			}else{
				$money = $money;
			}
			$no_money = merchant_get_no_money($id);
			if(is_numeric($money)){
				if($money<1){
					message('到账金额需要大于1元！', referer(), 'error');
					exit;
				}
			if($no_money<$money){
				message('您没有足够的可结算金额！', referer(), 'error');exit;
			}
			$result = finance($merchant['openid'], 1, $money * 100, '', '商家提现');
			if($result){
					$res=merchant_update_no_money(0-$mm, $id);
					if($res){
						pdo_insert("tg_merchant_record",array('merchantid'=>$id,'money'=>$mm,'uid'=>$_W['uid'],'createtime'=>TIMESTAMP,'uniacid'=>$_W['uniacid']));
					}
				}
				if (is_error($result)) {
						message('微信钱包提现失败: ' . $result['message'], '', 'error');exit;
				}
			}else{
				message('结算金额输入错误！', referer(), 'error');
				return false;
			}
			message('结算成功！', referer(), 'success');
		}
		include wl_template('application/merchant/account');
	}
	if($op == 'record') {
		$id = $_GPC['id'];
		$merchant = pdo_fetch("SELECT thumb,name,openid FROM ".tablename('tg_merchant')." WHERE uniacid = {$_W['uniacid']} and id={$id}");
		$list = pdo_fetchall("select * from".tablename('tg_merchant_record')."where merchantid='{$id}' and uniacid={$_W['uniacid']} ");
		include wl_template('application/merchant/account');
	}
	if($op == 'data'){
		$id = $_GPC['id'];
		$merchant = pdo_fetch("SELECT thumb,name,openid FROM ".tablename('tg_merchant')." WHERE uniacid = {$_W['uniacid']} and id={$id}");
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = "  uniacid = :uniacid";
		$paras = array(':uniacid' => $_W['uniacid']);
	
		$status = $_GPC['status'];
		$transid = $_GPC['transid'];
		$pay_type = $_GPC['pay_type'];
		$keyword = $_GPC['keyword'];
		$member = $_GPC['member'];
		$time = $_GPC['time'];
	
		if (empty($starttime) || empty($endtime)) {
			$starttime = strtotime('-1 month');
			$endtime = time();
		}
		if (!empty($_GPC['time'])) {
			$starttime = strtotime($_GPC['time']['start']);
			$endtime = strtotime($_GPC['time']['end']) ;
			$condition .= " AND  createtime >= :starttime AND  createtime <= :endtime ";
			$paras[':starttime'] = $starttime;
			$paras[':endtime'] = $endtime;
		}
		if(trim($_GPC['goodsid'])!=''){
			$condition .= " and g_id like '%{$_GPC['goodsid']}%' ";
		}
		if(trim($_GPC['goodsid2'])!=''){
			$condition .= " and g_id like '%{$_GPC['goodsid2']}%' ";
		}
		if (!empty($_GPC['merchantid'])) {
			$condition .= " AND  merchantid={$_GPC['merchantid']} ";
		}
		if (!empty($_GPC['transid'])) {
			$condition .= " AND  transid =  '{$_GPC['transid']}'";
		}
		if (!empty($_GPC['pay_type'])) {
			$condition .= " AND  pay_type = '{$_GPC['pay_type']}'";
		} elseif ($_GPC['pay_type'] === '0') {
			$condition .= " AND  pay_type = '{$_GPC['pay_type']}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND  orderno LIKE '%{$_GPC['keyword']}%'";
		}
		if (!empty($_GPC['member'])) {
			$condition .= " AND (addname LIKE '%{$_GPC['member']}%' or mobile LIKE '%{$_GPC['member']}%')";
		}
		if ($status != '') {
			$condition .= " AND  status = '" . intval($status) . "'";
			if($status==4){
				$allnogettime = pdo_fetchall("select * from".tablename('tg_order')."where gettime='' and uniacid='{$_W['uniacid']}' and status=3");
				if(empty($gettime)){
					$gettime = 5;
				}
				$now = time();
				foreach($allnogettime as $key =>$value){
					$shouldgettime = $value['sendtime']+$gettime*24*3600;
					if($shouldgettime<$now){
						pdo_update('tg_order',array('gettime'=>$shouldgettime,'status'=>4),array('id'=>$value['id']));
					}
				}
				
			}
		}
		$sql = "select  * from " . tablename('tg_order') . " where $condition and mobile<>'虚拟' and is_hexiao=0 and merchantid={$id} ORDER BY createtime DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql, $paras);
		$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'info', 'name' => '余额支付'), '2' => array('css' => 'success', 'name' => '在线支付'), '3' => array('css' => 'warning', 'name' => '货到付款'));
		$orderstatus = array('0' => array('css' => 'default', 'name' => '待付款'), '1' => array('css' => 'info', 'name' => '已付款'), '2' => array('css' => 'warning', 'name' => '待发货'), '3' => array('css' => 'success', 'name' => '已发货'), '4' => array('css' => 'success', 'name' => '已签收'), '5' => array('css' => 'default', 'name' => '已取消'), '6' => array('css' => 'danger', 'name' => '待退款'), '7' => array('css' => 'default', 'name' => '已退款'));
		foreach ($list as $key => $value) {
			$options  = pdo_fetch("select title,productprice,marketprice from " . tablename("tg_goods_option") . " where id=:id limit 1", array(":id" => $value['optionid']));
			$list[$key]['optionname'] = $options['title'];
			$s = $value['status'];
			$list[$key]['statuscss'] = $orderstatus[$value['status']]['css'];
			$list[$key]['status'] = $orderstatus[$value['status']]['name'];
			$list[$key]['css'] = $paytype[$value['pay_type']]['css'];
			if ($value['pay_type'] == 2) {
				if (empty($value['transid'])) {
					$list[$key]['paytype'] = '微信支付';
				} else {
					$list[$key]['paytype'] = '微信支付';
				}
			} else {
				$list[$key]['paytype'] = $paytype[$value['pay_type']]['name'];
			}
			$goodsss = pdo_fetch("select id,gname,gimg,merchantid,unit from" . tablename('tg_goods') . "where id = '{$value['g_id']}'");
			$list[$key]['unit'] = $goodsss['unit'];
			$list[$key]['gid'] = $goodsss['id'];
			$list[$key]['gname'] = $goodsss['gname'];
			$list[$key]['gimg'] = $goodsss['gimg'];
			$list[$key]['merchant'] = pdo_fetch("select name from" . tablename('tg_merchant') . "where id = '{$value['merchantid']}' and uniacid={$_W['uniacid']}");
		}
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE $condition and mobile<>'虚拟' and is_hexiao=0", $paras);
		$pager = pagination($total, $pindex, $psize);
		
		$all = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and mobile<>'虚拟' and is_hexiao=0 ");
		$status0 = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and merchantid={$id}  and status=0 and mobile<>'虚拟' and is_hexiao=0");
		$status1 = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and merchantid={$id}  and status=1 and mobile<>'虚拟' and is_hexiao=0");
		$status2 = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and merchantid={$id}  and status=2 and mobile<>'虚拟' and is_hexiao=0");
		$status3 = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and merchantid={$id}  and status=3 and mobile<>'虚拟' and is_hexiao=0");
		$status4 = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and merchantid={$id}  and status=4 and mobile<>'虚拟' and is_hexiao=0");
		$status5 = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and merchantid={$id}  and status=5 and mobile<>'虚拟' and is_hexiao=0");
		$status6 = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and merchantid={$id}  and status=6 and mobile<>'虚拟' and is_hexiao=0");
		$status7 = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and merchantid={$id}  and status=7 and mobile<>'虚拟' and is_hexiao=0");
		include wl_template('application/merchant/account');
	}
	if($op == 'permissions'){
		$id = intval($_GPC['id']);
		$nodes = pdo_fetchall("select * from".tablename('tg_user_node')." where uniacid={$_W['uniacid']} and status=2 and do_id=0");
		foreach($nodes as $key=>&$value){
			$value['children'] = pdo_fetchall("select * from".tablename('tg_user_node')." where uniacid={$_W['uniacid']} and status=2 and do_id={$value['id']} and ac_id=0");
			foreach($value['children'] as $k=>&$v){
				$v['children'] = pdo_fetchall("select * from".tablename('tg_user_node')." where uniacid={$_W['uniacid']} and status=2 and do_id={$value['id']} and ac_id={$v['id']}");
			}
		}
		
		$role = pdo_fetch("select * from".tablename('tg_user_role')."where uniacid={$_W['uniacid']} and merchantid={$id}");
		if (checksubmit('submit')) {
			$nodes = $_GPC['node_ids'];
			$nodes=serialize($nodes);
			$data = array(
				'merchantid'=>$id,
				'nodes'=>$nodes,
				'uniacid'=>$_W['uniacid']
			);
			if($role){
				pdo_update('tg_user_role',$data,array('merchantid'=>$id));
			}else{
				pdo_insert('tg_user_role',$data);
			}
			message('保存成功！', referer(), 'success');
		}
		$role = pdo_fetch("select * from".tablename('tg_user_role')."where uniacid={$_W['uniacid']} and merchantid={$id}");
		if($role){
			$role['node_ids'] = unserialize($role['nodes']);
		}
		include wl_template('application/merchant/permissions');
	}
?>