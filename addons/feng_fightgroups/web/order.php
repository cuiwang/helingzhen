<?
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	if ($operation == 'display') {
		
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$status = $_GPC['status'];
		$is_tuan = $_GPC['is_tuan'];
		$condition = " o.uniacid = :weid";
		$paras = array(':weid' => $_W['uniacid']);
		if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
			$endtime = time();
		}
		if (!empty($_GPC['time'])) {
			$starttime = strtotime($_GPC['time']['start']);
			$endtime = strtotime($_GPC['time']['end']) + 86399;
			$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
			$paras[':starttime'] = $starttime;
			$paras[':endtime'] = $endtime;
		}

		if (!empty($_GPC['pay_type'])) {
			$condition .= " AND o.pay_type = '{$_GPC['pay_type']}'";
		} elseif ($_GPC['pay_type'] === '0') {
			$condition .= " AND o.pay_type = '{$_GPC['pay_type']}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND o.orderno LIKE '%{$_GPC['keyword']}%'";
		}
		 if (!empty($_GPC['member'])) {
			$condition .= " AND (a.cname LIKE '%{$_GPC['member']}%' or a.tel LIKE '%{$_GPC['member']}%')";
		 }
		if ($status != '') {
			$condition .= " AND o.status = '" . intval($status) . "'";
		}
		if ($is_tuan != '') {
			$pp = 1;
			$condition .= " AND o.is_tuan = 1";
		}
		$sql = "select o.* , a.cname,a.tel from ".tablename('tg_order')." o"
				." left join ".tablename('tg_address')." a on o.addressid = a.id "
				. " where $condition ORDER BY o.status DESC, o.createtime DESC "
				. "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql,$paras);
		$paytype = array (
				'0' => array('css' => 'default', 'name' => '未支付'),
				'1' => array('css' => 'info', 'name' => '在线支付'),
				'3' => array('css' => 'warning', 'name' => '货到付款')
		);
		$orderstatus = array (
				'9' => array('css' => 'default', 'name' => '已取消'),
				'0' => array('css' => 'danger', 'name' => '待付款'),
				'1' => array('css' => 'info', 'name' => '待发货'),
				'2' => array('css' => 'warning', 'name' => '待收货'),
				'3' => array('css' => 'success', 'name' => '已完成')
		);
		foreach ($list as &$value) {
			$s = $value['status'];
			$value['statuscss'] = $orderstatus[$value['status']]['css'];
			$value['status'] = $orderstatus[$value['status']]['name'];
			if ($s < 1) {
				$value['css'] = $paytype[$s]['css'];
				$value['paytype'] = $paytype[$s]['name'];
				continue;
			}
			$value['css'] = $paytype[$value['paytype']]['css'];
			if ($value['paytype'] == 2) {
				if (empty($value['transid'])) {
					$value['paytype'] = '支付宝支付';
				} else {
					$value['paytype'] = '微信支付';
				}
			} else {
				$value['paytype'] = $paytype[$value['paytype']]['name'];
			}
		}

		$total = pdo_fetchcolumn(
					'SELECT COUNT(*) FROM ' . tablename('tg_order') . " o "
					." left join ".tablename('tg_address')." a on o.addressid = a.id "
					." WHERE $condition", $paras);
		$pager = pagination($total, $pindex, $psize);
	} elseif ($operation == 'detail') {
		$id = intval($_GPC['id']);
		$is_tuan = intval($_GPC['is_tuan']);
		$item = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE id = :id", array(':id' => $id));
		if (empty($item)) {
			message("抱歉，订单不存在!", referer(), "error");
		}
		if (checksubmit('confirmsend')) {
			if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
				message('请输入快递单号！');
			}
			
			pdo_update(
				'tg_order',
				array(
					'status' => 2,						
					'express' => $_GPC['express'],
					
					'expresssn' => $_GPC['expresssn'],
				),
				array('id' => $id)
			);
			message('发货操作成功！', referer(), 'success');
		}
		if (checksubmit('cancelsend')) {
			$item = pdo_fetch("SELECT transid FROM " . tablename('shopping_order') . " WHERE id = :id", array(':id' => $id));
			if (!empty($item['transid'])) {
				$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
			}
			pdo_update(
				'tg_order',
				array(
					'status' => 1
				),
				array('id' => $id)
			);
			message('取消发货操作成功！', referer(), 'success');
		}
		if (checksubmit('finish')) {
			pdo_update('tg_order', array('status' => 3), array('id' => $id));
			message('订单操作成功！', referer(), 'success');
		}
		if (checksubmit('refund')) {
			
			message('退款成功?！', referer(), 'success');
		}
		if (checksubmit('cancel')) {
			pdo_update('tg_order', array('status' => 1), array('id' => $id));
			message('取消完成订单操作成功！', referer(), 'success');
		}
		if (checksubmit('cancelpay')) {
			pdo_update('tg_order', array('status' => 0), array('id' => $id));
			//设置库存
			$this->setOrderStock($id, false);
			message('取消订单付款操作成功！', referer(), 'success');
		}
		if (checksubmit('confrimpay')) {
			pdo_update('shopping_order', array('status' => 1, 'pay_type' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
			//设置库存
			$this->setOrderStock($id);
			message('确认订单付款操作成功！', referer(), 'success');

		}

		if (checksubmit('close')) {
			$item = pdo_fetch("SELECT transid FROM " . tablename('shopping_order') . " WHERE id = :id", array(':id' => $id));
			if (!empty($item['transid'])) {
				$this->changeWechatSend($id, 0, $_GPC['reson']);
			}
			pdo_update('shopping_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
			message('订单关闭操作成功！', referer(), 'success');
		}

		if (checksubmit('open')) {
			pdo_update('tg_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
			message('开启订单操作成功！', referer(), 'success');
		}
		// $dispatch = pdo_fetch("SELECT * FROM " . tablename('shopping_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
		// if (!empty($dispatch) && !empty($dispatch['express'])) {
		// 	$express = pdo_fetch("select * from " . tablename('shopping_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
		// }
		$item['user'] = pdo_fetch("SELECT * FROM " . tablename('tg_address') . " WHERE id = {$item['addressid']}");
		$goods = pdo_fetchall("select * from" . tablename('tg_goods') ."WHERE id={$item['g_id']}");
		$item['goods'] = $goods;
	} elseif ($operation == 'delete') {

		/*订单删除*/

		$orderid = intval($_GPC['id']);
		$tuan_id = intval($_GPC['tuan_id']);
		if(!empty($tuan_id)){
            if(pdo_delete('tg_order', array('tuan_id' => $tuan_id))){
             	message('团订单删除成功', $this->createWebUrl('order', array('op' => 'tuan')), 'success');
            }	
		}
		if (pdo_delete('tg_order', array('id' => $orderid))) {
			message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
		} else {
			message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
		}
	} elseif ($operation == 'tuan') {
		
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$is_tuan = $_GPC['is_tuan'];
		$condition = "uniacid = :weid";
		$paras = array(':weid' => $_W['uniacid']);
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND tuan_id LIKE '%{$_GPC['keyword']}%'";
		}
		if ($is_tuan != '') {
			$condition .= " AND is_tuan = 1";
		}
		$sql = "select DISTINCT tuan_id from".tablename('tg_order').
		"where $condition order by createtime desc ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$tuan_id = pdo_fetchall($sql,$paras);
		foreach ($tuan_id as $key => $tuan) {
			$alltuan = pdo_fetchall("select * from".tablename('tg_order')."where tuan_id={$tuan['tuan_id']}");
			$ite = array();
              foreach ($alltuan as $num => $all) {
              $ite[$num] = $all['id'];
              $goods = pdo_fetch("select * from".tablename('tg_goods')."where id = {$all['g_id']}");
              }
			 
              $tuan_id[$key]['itemnum'] = count($ite);
              $tuan_id[$key]['groupnum'] = $goods['groupnum'];

              $tuan_first_order = pdo_fetch("SELECT * FROM".tablename('tg_order')."where tuan_id={$tuan['tuan_id']} and tuan_first = 1");
              $hours=$tuan_first_order['endtime'];
              $time = time();
              $date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
              $endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
              $date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
              $lasttime = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）
              $tuan_id[$key]['lasttime'] = $lasttime;
		}
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order')." WHERE $condition ", $paras);
		$pager = pagination($total, $pindex, 10);			
	} elseif ($operation == 'tuan_detail'){
		$num = intval($_GPC['num']);
		$tuan_id = intval($_GPC['tuan_id']);//指定团的id
		$is_tuan = intval($_GPC['is_tuan']);
		$orders = pdo_fetchall("SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = {$tuan_id}");
		foreach ($orders as $key => $order) {
          $address = pdo_fetch("SELECT * FROM".tablename('tg_address')."where id={$order['addressid']}");
          $orders[$key]['cname'] = $address['cname'];
          $orders[$key]['tel'] = $address['tel'];
          $orders[$key]['province'] = $address['province'];
          $orders[$key]['city'] = $address['city'];
          $orders[$key]['county'] = $address['county'];
          $orders[$key]['detailed_address'] = $address['detailed_address'];
          $goods = pdo_fetch("select * from".tablename('tg_goods')."where id={$order['g_id']}");
			
		}
		$goodsid  = array();
		foreach ($orders as $key => $value) {
			$goodsid['id'] = $value['g_id'];
		}
		$goods2 = pdo_fetch("SELECT * FROM " . tablename('tg_goods') . " WHERE id = {$goodsid['id']}");
		if (empty($orders)) {
			message("抱歉，该团购不存在!", referer(), "error");
		}
		foreach ($orders as $key => $value) {
			$it['status'] = $value['status'];
		}
		//是否过期
		$sql2= "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and tuan_first = :tuan_first";
		$params2 = array(':tuan_id'=>$tuan_id,':tuan_first'=>1);
		$tuan_first_order = pdo_fetch($sql2, $params2);
		$hours=$tuan_first_order['endtime'];
		$time = time();
		$date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
		$endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
		
		$date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
		$lasttime2 = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）
		//确认发货
		if (checksubmit('confirmsend')) {
			pdo_update(
				'tg_order',
				array(
					'status' => 2					
				),
				array('tuan_id' => $tuan_id)
			);
			message('发货操作成功！', referer(), 'success');
		}
		//取消发货
		if (checksubmit('cancelsend')) {
			
			pdo_update(
				'tg_order',
				array(
					'status' => 1
				),
				array('tuan_id' => $tuan_id)
			);
			message('取消发货操作成功！', referer(), 'success');
		}
		//确认完成订单
		if (checksubmit('finish')) {
			pdo_update('tg_order', array('status' => 3), array('tuan_id' => $tuan_id));
			message('订单操作成功！', referer(), 'success');
		}
		//取消完成订单（状态为已支付）
		if (checksubmit('cancel')) {
			pdo_update('tg_order', array('status' => 1), array('tuan_id' => $tuan_id));
			message('取消完成订单操作成功！', referer(), 'success');
		}
		//取消支付
		if (checksubmit('cancelpay')) {
			pdo_update('tg_order', array('status' => 0), array('tuan_id' => $tuan_id));
			message('取消团订单付款操作成功！', referer(), 'success');
		}
		//确认支付
		if (checksubmit('confrimpay')) {
			pdo_update('tg_order', array('status' => 1, 'pay_type' => 2),  array('tuan_id' => $tuan_id));
			message('团订单付款操作成功！', referer(), 'success');
		}
	}
	include $this->template('order');
?>
