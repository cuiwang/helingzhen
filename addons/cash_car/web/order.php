<?php
/**
 * 服务点订单管理
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
 
load()->func('tpl');
$action = 'order';
$title = $this->actions_titles[$action];
$storeid = intval($_GPC['storeid']);

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid={$weid} LIMIT 1");

if ($operation == 'display') {
	// 当前服务点信息
	$store = pdo_fetch("SELECT title FROM " .tablename($this->table_store). " WHERE id='{$storeid}'");

	$condition = " weid = '{$_W['uniacid']}'";

	$storeslist = pdo_fetchall("SELECT * FROM " . tablename($this->table_store) . " WHERE {$condition} ");
	$strstoreid = '';
	foreach ($storeslist as $val){
		$strstoreid .= $val['id'].',';
	}
	$strstoreid=rtrim($strstoreid, ",");
	
	$commoncondition .= " AND storeid in ({$strstoreid}) ";
	$commonconditioncount .= " AND storeid in ({$strstoreid}) ";
	
	$commoncondition = " weid = '{$_W['uniacid']}' ";
	if ($storeid != 0) {
		$commoncondition .= " AND storeid={$storeid} ";
	}

	$commonconditioncount = " weid = '{$_W['uniacid']}' ";
	if ($storeid != 0) {
		$commonconditioncount .= " AND storeid={$storeid} ";
	}

	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;
		$commoncondition .= " AND dateline >= :starttime AND dateline <= :endtime ";
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}

	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}

	//洗车工列表
	$worker = pdo_fetchall("SELECT * FROM " .tablename($this->table_worker). " WHERE storeid='{$storeid}'");

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	if (!empty($_GPC['ordersn'])) {
		$commoncondition .= " AND ordersn LIKE '%{$_GPC['ordersn']}%' ";
	}

	if (!empty($_GPC['tel'])) {
		$commoncondition .= " AND tel LIKE '%{$_GPC['tel']}%' ";
	}

	if (!empty($_GPC['username'])) {
		$commoncondition .= " AND username LIKE '%{$_GPC['username']}%' ";
	}

	if (isset($_GPC['status']) && $_GPC['status'] != 0) {
		$commoncondition .= " AND status = '" . intval($_GPC['status']) . "'";
	}

	if (!empty($_GPC['worker'])) {
		$commoncondition .= " AND worker_openid = '{$_GPC['worker']}' ";
	}

	if ($_GPC['out_put'] == 'output') {
		$sql = "select * from " . tablename($this->table_order)
			. " WHERE $commoncondition ORDER BY status DESC, dateline DESC ";
		$list = pdo_fetchall($sql, $paras);
		$orderstatus = array(
			'-1' => array('css' => 'default', 'name' => '已取消'),
			'0' => array('css' => 'danger', 'name' => '待付款'),
			'1' => array('css' => 'info', 'name' => '已确认'),
			'2' => array('css' => 'warning', 'name' => '已付款'),
			'3' => array('css' => 'success', 'name' => '已完成')
		);

		$i = 0;
		foreach ($list as $key => $value) {
			$arr[$i]['ordersn'] = $value['ordersn'];
			$arr[$i]['status'] = $orderstatus[$value['status']]['name'];
			$arr[$i]['totalprice'] = $value['totalprice'];
			$arr[$i]['username'] = $value['username'];
			$arr[$i]['tel'] = $value['tel'];
			$arr[$i]['address'] = $value['address'];
			$arr[$i]['dateline'] = date('Y-m-d H:i:s', $value['dateline']);

			$i++;
		}

		$this->exportexcel($arr, array('订单号', '状态', '总价', '真实姓名', '电话号码', '地址', '时间'), time());
		exit();
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE $commoncondition ORDER BY id desc, dateline DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE $commoncondition", $paras);

	$pager = pagination($total, $pindex, $psize);

	if (!empty($list)) {
		foreach ($list as $row) {
			$userids[$row['from_user']] = $row['from_user'];
		}
	}

	$order_count_all = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} ");
	$order_count_confirm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=1");
	$order_count_pay = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=2");
	$order_count_finish = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=3");
	$order_count_cancel = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=-1");

	$users = fans_search($userids, array('realname', 'resideprovince', 'residecity', 'residedist', 'address', 'mobile', 'qq'));

	//黑名单
	$blacklist = pdo_fetchall("SELECT * FROM ".tablename($this->table_blacklist)." WHERE weid = :weid", array(':weid' => $_W['weid']), 'from_user');

	//服务点列表
	$storelist = pdo_fetchall("SELECT * FROM ".tablename($this->table_store)." WHERE weid = :weid", array(':weid' => $_W['weid']), 'id');

} elseif ($operation == 'detail') {
	//流程 第一步确认付款 第二步确认订单 第三步，完成订单
	$id = intval($_GPC['id']);
	$order = pdo_fetch("SELECT a.*,b.uid,c.credit2 FROM " . tablename($this->table_order) . " a LEFT JOIN " .tablename('mc_mapping_fans'). " b ON a.from_user=b.openid LEFT JOIN " .tablename('mc_members'). " c ON b.uid=c.uid WHERE a.id=:id LIMIT 1", array(':id' => $id));

	if (checksubmit('confrimsign')) {
		pdo_update($this->table_order, array('remark' => $_GPC['remark'], 'sign' => $_GPC['sign'], 'reply' => $_GPC['reply']), array('id' => $id));
		message('操作成功！', referer(), 'success');
	}
	if (checksubmit('finish')) {
		//isfinish
		if ($order['isfinish'] == 0) {
			//如果订单赠送积分大于0，则进行积分赠送
			if($order['totalintegral']>0){
				load()->model('mc');
				$uid = mc_openid2uid($order['from_user']);
				mc_credit_update($uid, 'credit1', $order['totalintegral'], array('1'=>'洗车订单:'.$order['id']));
			}
			pdo_update($this->table_order, array('isfinish' => 1,'finisher'=>'admin', 'finish_time'=>time()), array('id' => $id));
		}
		pdo_update($this->table_order, array('status' => 3, 'remark' => $_GPC['remark']), array('id' => $id));
		message('订单操作成功！', referer(), 'success');
	}
	if (checksubmit('cancel')) {
		pdo_update($this->table_order, array('status' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
		message('取消完成订单操作成功！', referer(), 'success');
	}
	if (checksubmit('confirm')) {
		pdo_update($this->table_order, array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
		message('确认订单操作成功！', referer(), 'success');
	}
	
	if (checksubmit('cancelpay')) {
		pdo_update($this->table_order, array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
		message('操作成功！', referer(), 'success');
	}
	
	if (checksubmit('confrimpay')) {
		pdo_update($this->table_order, array('status' => 2, 'paytype'=>2, 'sign'=>1, 'remark' => $_GPC['remark']), array('id' => $id));
		message('确认订单付款操作成功！', referer(), 'success');
	}
	if (checksubmit('close')) {
		pdo_update($this->table_order, array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
		$result = pdo_update($this->table_order, array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
		if($result){
			$order_goods = pdo_fetchall("SELECT * FROM " .tablename($this->table_order_goods). " WHERE orderid='{$id}'");
			if(in_array($order['status'], array("1","2"))){
				foreach($order_goods as $goods){
					if(!empty($goods['onlycard'])){
						//洗车卡支付
						$membercard =  pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid  AND onlycard=:onlycard", array(':uid' => $order['uid'], ':weid' => $weid, 'onlycard'=>$goods['onlycard']));
						
						$number = array('number'=>$membercard['number']+1);
						$condition = array(
							'uid'      => $order['uid'],
							'weid'     => $weid,
							'onlycard' => $goods['onlycard'],
						);
						pdo_update($this->table_member_onecard, $number, $condition);
						
						//添加会员洗车卡明细
						$onecard_record = array(
							'weid'      => $weid,
							'uid'       => $membercard['uid'],
							'openid'    => $order['from_user'],
							'title'     => $membercard['title'],
							'reduce'    => '1',
							'total'     => $membercard['number']+1,
							'remark'    => "管理员取消洗车订单[".$order['ordersn']."]",
							'add_time'  => time(),
						);
						pdo_insert($this->table_onecard_record, $onecard_record);
					}else{
						$reftotal += $goods['price'];
					}
					unset($membercard);
				}


				if($reftotal>0){
					//添加用户退款金额到账户余额
					pdo_update('mc_members', array('credit2'=>$order['credit2']+$reftotal), array('uid'=>$order['uid']));
					//添加用户余额日志
					$credits_log = array(
						   'uid'        => $user['uid'],
						   'uniacid'    => $weid,
						   'credittype' => 'credit2', //credit2代表余额
						   'num'        => +$reftotal,
						   'operator'   => '0',
						   'createtime' => time(),
						   'remark'     => '取消洗车订单：'.$order['id'],
					 );
					 pdo_insert('mc_credits_record', $credits_log);
				}

				//发送取消订单模版消息
				$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid={$weid} LIMIT 1");
				if($setting['istplnotice']){
					$meal_date = date('Y-m-d',$order['meal_date']);
					$goodsid = pdo_fetchall("SELECT goodsid FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$order['id']}'", array(), 'goodsid');
					$goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
					foreach($goods as $val){
						$goods_name .= $val['title'].'+';
					}
					$goods_name = trim($goods_name, '+');
					//工作人员模版消息
					$messageDatas = array(
						'touser'      => $order['worker_openid'],
						'template_id' => $setting['cmessage'],
						'url'         => "",
						'topcolor'    => "#7B68EE",
						'data'        => array(
							'first'   => array(
								 'value' => urlencode("后台管理员取消预约订单通知"),
								 'color' => "#E43935",
							 ),
							 'OrderSn'=> array(
								 'value' => urlencode($order['ordersn']),
								 'color' => "#428BCA",
							 ),
							 'OrderStatus'  => array(
								 'value' => urlencode("已取消"),
								 'color' => "#2E3A42",
							 ),
							 'remark' => array(
								 'value' => urlencode("\\n订单详细信息\\n用户姓名：{$order['username']}\\n预约日期：{$meal_date}\\n预约时段：{$order['meal_time']}\\n预约车牌：{$order['mycard']}\\n手机号码：{$order['tel']}\\n洗车地址：{$order['address']}\\n服务项目：{$goods_name}"),
								 'color' => "#428BCA",
							 ),
					 
						  )
					);
					$this->send_template_message(urldecode(json_encode($messageDatas)));

					//用户模版消息
					$userMessage = array(
						'touser'      => $order['from_user'],
						'template_id' => $setting['cmessage'],
						'url'         => "",
						'topcolor'    => "#7B68EE",
						'data'        => array(
							'first'   => array(
								 'value' => urlencode("后台管理员取消预约订单通知"),
								 'color' => "#E43935",
							 ),
							 'OrderSn'=> array(
								 'value' => urlencode($order['ordersn']),
								 'color' => "#428BCA",
							 ),
							 'OrderStatus'  => array(
								 'value' => urlencode("已取消"),
								 'color' => "#2E3A42",
							 ),
							 'remark' => array(
								 'value' => urlencode("\\n订单详细信息\\n用户姓名：{$order['username']}\\n预约日期：{$meal_date}\\n预约时段：{$order['meal_time']}\\n预约车牌：{$order['mycard']}\\n手机号码：{$order['tel']}\\n洗车地址：{$order['address']}\\n服务项目：{$goods_name}"),
								 'color' => "#428BCA",
							 ),
					 
						  )
					);
					$this->send_template_message(urldecode(json_encode($userMessage)));
				}
			}
		}

		message('订单关闭操作成功！', referer(), 'success');
	}
	if (checksubmit('open')) {
		pdo_update($this->table_order, array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
		message('开启订单操作成功！', referer(), 'success');
	}

	$item = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $id));

	if(!empty($item['images'])){
		$item['images'] = explode(",", $item['images']);
	}

	$item['user'] = fans_search($item['from_user'], array('realname', 'resideprovince', 'residecity', 'residedist', 'address', 'mobile', 'qq'));
	$item['worker'] = pdo_fetch("SELECT * FROM " .tablename($this->table_worker). " WHERE openid='{$order[worker_openid]}'");

	$goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$item['id']}'", array(), 'goodsid');

	$item['goods'] = $goods;
	
	//遍历该订单下的服务项目评价
	foreach($goods as $k=>$g){
		$evaluate = pdo_fetch("SELECT * FROM " . tablename($this->table_goods_evaluate) . " WHERE orderid=:orderid AND goodsid=:goodsid", array(':orderid'=>$order['id'], ':goodsid'=>$g['goodsid']));
		$item['goods'][$k]['evaluate'] = $evaluate;
	}

} else if ($operation == 'delete') {
	$id = $_GPC['id'];
	pdo_delete($this->table_order, array('id' => $id));
	if($_GPC['refurl']=='allorder'){
		echo "<script>alert('删除成功！');location.href='".$this->createWebUrl('allorder', array('op' => 'display', 'status' => $_GPC['status']))."';</script>";
	}else{
		echo "<script>alert('删除成功！');location.href='".$this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid))."';</script>";
	}
} else if ($operation == 'black') {
	$id = $_GPC['id'];//订单id
	$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id=:id AND weid=:weid  LIMIT 1", array(':id' => $id, ':weid' => $_W['uniacid']));

	if (empty($order)) {
		message('数据不存在!');
	}

	$data = array(
		'weid' => $_W['uniacid'],
		'from_user' => $order['from_user'],
		'status' => 0,
		'dateline' => TIMESTAMP
	);

	$blacker = pdo_fetch("SELECT * FROM " . tablename($this->table_blacklist) . " WHERE from_user=:from_user AND weid=:weid  LIMIT 1", array(':from_user' => $order['from_user'], ':weid' => $_W['uniacid']));

	if (!empty($blacker)) {
		message('该用户已经在黑名单中!', $this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid)));
	}

	pdo_insert($this->table_blacklist, $data);
	message('操作成功！', $this->createWebUrl('order', array('op' => 'display', 'storeid' => $storeid)), 'success');
}
include $this->template('order');