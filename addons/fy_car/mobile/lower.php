<?php
   /**
	* 工作人员管理
	*/

   global $_GPC, $_W;
   $this->checklogin();
   $weid = $_W['uniacid'];

   $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $psize = 15;
   $pindex = max(1, intval($_GPC['page']));
   
   if($op=='display'){
	   $mystore = pdo_fetchall("SELECT * FROM " .tablename($this->table_store). " WHERE accountid='{$_SESSION['accountid']}'");

	   $condition = " weid='{$weid}' AND accountid='{$_SESSION['accountid']}'";
	   if(!empty($_GPC['name'])){
		   $condition .= " AND worker_name LIKE '%{$_GPC[name]}%'";
	   }
	   if(!empty($_GPC['car'])){
		   $condition .= " AND car_number LIKE '%{$_GPC[car]}%'";
	   }
	   if(!empty($_GPC['storeid'])){
		   $condition .= " AND storeid = '{$_GPC[storeid]}'";
	   }

	   $lowerlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_car_worker). " WHERE {$condition} order by id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		
	   $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_car_worker). " WHERE {$condition}");
	   $pager = $this->pagination($total, $pindex, $psize);

   }elseif($op=='addlower'){
	    $workerid = intval($_GPC['workerid']); //工作人员编号
		if(!empty($workerid)){
			$item = pdo_fetch("SELECT * FROM " .tablename($this->table_car_worker). " WHERE id='{$workerid}' AND accountid='{$_SESSION['accountid']}'");
			if(empty($item)){
				message('该工作人员不存在！', '', 'error');
			}
		}

		$storelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_store) . " WHERE weid =:weid AND accountid=:accountid", array(':weid' => $weid, 'accountid'=>$_SESSION['accountid']));

		if ($_POST) {
			$data = array();
			$data['weid']          = intval($weid);
			$data['worker_name']   = trim($_GPC['worker_name']);
			$data['username']      = trim($_GPC['username']);
			$data['worker_mobile'] = trim($_GPC['worker_mobile']);
			$data['worker_desc']   = trim($_GPC['worker_desc']);
			$data['car_number']    = trim($_GPC['car_number']);
			$data['isshow']        = intval($_GPC['isshow']);
			$data['accountid']     = $_SESSION['accountid'];
			$data['storeid']       = intval($_GPC['storeid']);
			$data['add_time']      = time();

			if (empty($data['worker_name'])) {
				message('请输入工作人员名称！', '', 'error');
			}
			if (empty($data['username'])) {
				message('请输入电子邮箱/手机号码！', '', 'error');
			}
			if (empty($data['storeid'])) {
				message('请选择服务点！', '', 'error');
			}
			if (empty($data['worker_mobile'])) {
				message('请输入联系电话！', '', 'error');
			}

			$store = pdo_fetch("SELECT * FROM " .tablename($this->table_store). " WHERE id='{$data['storeid']}'");
			if(empty($store)){
				message('该服务点不存在！', '', 'error');
			}
			$data['store_name'] = $store['title'];

			$user  = pdo_fetch("SELECT * FROM " .tablename('mc_members'). " WHERE uniacid='{$weid}' AND mobile='{$_GPC['username']}' OR email='{$_GPC['username']}'");
			if(empty($user)){
				message('该用户不存在，请检查是否绑定电子邮箱/手机号码！', '', 'error');
			}
			$fans = pdo_fetch("SELECT * FROM " .tablename('mc_mapping_fans'). " WHERE uid='{$user[uid]}'");
			if(empty($fans)){
				message('该粉丝不存在，获取粉丝openid失败！', '', 'error');
			}
			$data['openid']   = $fans['openid'];
			$data['nickname'] = $fans['nickname'];

			$webWorker = array(
				'weid'       => $data['weid'],
				'name'       => $data['worker_name'],
				'mobile'     => $data['worker_mobile'],
				'openid'     => $fans['openid'],
				'wx_name'    => $fans['nickname'],
				'car_num'    => $data['car_number'],
				'storeid'    => $data['storeid'],
				'store_name' => $data['store_name'],
				'isshow'     => $data['isshow'],
				'sort'       => 0,
				'type'       => 2,
				'detail'     => $data['lower_desc'],
			);

			if (!empty($workerid)) {
				unset($data['add_time']);
				//加盟商工作人员表
				pdo_update($this->table_car_worker, $data, array('id' => $workerid, 'weid' => $weid, 'accountid'=>$_SESSION['accountid']));

				//后台工作人员模版消息表
				pdo_update($this->table_worker, $webWorker, array('weid' => $weid, 'workerid'=>$item['id']));
				message('编辑成功!', $this->createMobileUrl('lower'), 'success');
			} else {
				//加盟商工作人员表
				pdo_insert($this->table_car_worker, $data);
				$webWorker['workerid'] = pdo_insertid();
				
				//后台工作人员模版消息表
				pdo_insert($this->table_worker, $webWorker);
				message('添加成功！', $this->createMobileUrl('lower'), 'success');
			}
			
		}

   }elseif($op=='dellower'){
	    $workerid = intval($_GPC['workerid']); //工作人员编号
		$lower = pdo_fetch('SELECT * FROM ' .tablename($this->table_car_worker). " WHERE weid='{$weid}' AND id='{$workerid}' AND accountid='{$_SESSION['accountid']}'");
		if(empty($lower)){
			message('该工作人员不存在！', '', 'error');
		}

		pdo_delete($this->table_worker, array('workerid'=>$workerid));
		$result = pdo_delete($this->table_car_worker, array('id'=>$workerid));
		if($result){
			message('删除成功！', $this->createMobileUrl('lower'), 'success');
		}else{
			message('删除失败！', $this->createMobileUrl('lower'), 'error');
		}
   }

   include $this->template('lower');

