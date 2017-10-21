<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '兑换';

load()->model('mc');

$type = $_GPC['type'];
$type = in_array($type, array('goods', 'coupon', 'exchange_enable')) ? $type : 'coupon';
$uni_setting = pdo_get('uni_settings', array('uniacid' => $_W['uniacid']), array('exchange_enable'));

if ($do == 'coupon_info') {
	$coupon = activity_coupon_info(intval($_GPC['id']));
	message(error(0, $coupon), '', 'ajax');
}
if ($do == 'change_status') {
	$id = $_GPC['id'];
	$status = intval($_GPC['status']);
	pdo_update('activity_exchange', array('status' => $status),array('uniacid' => $_W['uniacid'], 'id' => $id));
	message(error(0), '', 'ajax');
}
if ($do == 'exchange_enable') {
	$status = pdo_update('uni_settings', array('exchange_enable' => intval($_GPC['status'])), array('uniacid' => $_W['uniacid']));
	if (!empty($status)) {
		cache_delete("unisetting:{$_W['uniacid']}");
		message(error(0, '修改成功'), referer(), 'ajax');
	} else {
		message(error(-1, '修改失败'), referer(), 'ajax');
	}
}
if ($type == 'coupon') {
	uni_user_permission_check('activity_coupon_display');
	$dos = array('display', 'post', 'delete');
	$do = $_GPC['do'];
	$do = in_array($do, $dos) ? $do : 'display';
	if ($do == 'post') {
		if (checksubmit('submit')) {
			$start = $_GPC['coupon_start'];
			$end = $_GPC['coupon_end'];
			$post = array(
				'uniacid' => $_W['uniacid'],
				'extra' => $_GPC['coupon'],
				'status' => intval($_GPC['status']),
				'credittype' => $_GPC['credittype'],
				'credit' => abs(intval($_GPC['credit'])),
				'pretotal' => empty($_GPC['pretotal']) ? 1 : intval($_GPC['pretotal']),
				'status' => $_GPC['status'],
				'starttime' => strtotime($_GPC['date']['start']),
				'endtime' => strtotime($_GPC['date']['end']),
			);
			if ($start && $end) {
				$start = strtotime(str_replace('.', '-', $start));
				$end = strtotime(str_replace('.', '-', $end));
				if ($start > $post['starttime'] || $end < $post['starttime'] || $start > $post['endtime'] || $end < $post['endtime']) {
					message('日期范围超过卡券日期范围', '', 'info');
				}
			}
			$post['type'] = COUPON_TYPE;
			if (empty($id)) {
				pdo_insert('activity_exchange', $post);
				message('添加兑换卡券成功', url('activity/exchange/coupon'), 'success');
			}
		}
		$id = intval($_GPC['id']);

		if (!empty($id)) {
			$data = pdo_get('activity_exchange', array('id' => $id, 'uniacid' => $_W['uniacid']));
			$data['coupon'] = pdo_get('coupon', array('uniacid' => $_W['uniacid'], 'id' => $data['extra']));
			$data['coupon']['logo_url'] = tomedia($data['coupon']['logo_url']);
		} else {
			$data['starttime'] = time();
			$data['endtime'] = time();
		}

		$coupons = pdo_fetchall("SELECT * FROM ". tablename('coupon')." WHERE uniacid = :uniacid AND source = ".COUPON_TYPE, array(':uniacid' => $_W['uniacid']), 'id');
		$coupon_exists = pdo_getall('activity_exchange', array('type' => COUPON_TYPE, 'uniacid' => $_W['uniacid']), array(), 'extra');
		$coupon_exists = array_keys($coupon_exists);
		foreach ($coupons as $key => &$coupon) {
			$coupon = activity_coupon_info($coupon['id']);
			if (in_array($key, $coupon_exists)) {
				unset($coupons[$key]);
			}
		}
	}
	if ($do == 'display') {
		$title = trim($_GPC['title']);
		$condition = '';
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		if (!empty($title)) {
			$cids = pdo_fetchall("SELECT * FROM ". tablename('coupon')." WHERE uniacid = :uniacid AND title LIKE :title AND source = :source", array('uniacid' => $_W['uniacid'], ':title' => '%'.$title.'%', ':source' => COUPON_TYPE), 'id');
			$cids = implode('\',\'', array_keys($cids));
			$condition = ' AND extra IN(\''.$cids.'\')';
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('activity_exchange')." WHERE uniacid = :uniacid AND type = :type ".$condition, array(':uniacid' => $_W['uniacid'], ':type' => COUPON_TYPE));
		$list = pdo_fetchall("SELECT * FROM ".tablename('activity_exchange')." WHERE uniacid = :uniacid AND type = :type ".$condition." ORDER BY id desc LIMIT ".($pindex - 1)*$psize.','. $psize, array(':uniacid' => $_W['uniacid'], ':type' => COUPON_TYPE));
		if (!empty($list)) {
			foreach($list as &$ex) {
				$ex['coupon'] = activity_coupon_info($ex['extra']);
				$ex['starttime'] = date('Y-m-d', $ex['starttime']);
				$ex['endtime'] = date('Y-m-d', $ex['endtime']);
			}unset($ex);
		}
		$pager = pagination($total, $pindex, $psize);
	}
	if ($do == 'delete') {
		$id = intval($_GPC['id']);
		$exist = pdo_get('activity_exchange', array('id' => $id, 'uniacid' => $_W['uniacid']));
		if (empty($exist)) {
			message('兑换卡券不存在', url('activity/exchange/coupon'), 'info');
		}
		pdo_delete('activity_exchange', array('id' => $id, 'uniacid' => $_W['uniacid']));
		message('兑换卡券删除成功', url('activity/exchange/coupon'), 'success');
	}
}
if ($type == 'goods') {
	uni_user_permission_check('activity_goods_display');
	$dos = array('display', 'post', 'del', 'record', 'deliver', 'receiver', 'record-del');
	$do = in_array($do, $dos) ? $do : 'display';

	
	$creditnames = array();
	$unisettings = uni_setting($uniacid, array('creditnames'));
	foreach ($unisettings['creditnames'] as $key=>$credit) {
		if (!empty($credit['enabled'])) {
			$creditnames[$key] = $credit['title'];
		}
	}
	if($do == 'post') {
		$id = intval($_GPC['id']);
		if(!empty($id)){
			$item = pdo_fetch('SELECT * FROM '.tablename('activity_exchange').' WHERE id=:id AND uniacid=:uniacid',array(':id'=>$id, ':uniacid'=>$_W['uniacid']));
			if(empty($item)) {
				message('未找到指定兑换礼品或已删除.',url('activity/exchange', array('type' => 'goods')),'error');
			} else {
				$item['extra'] = iunserializer($item['extra']);
			}
		} else {
			$item['starttime'] = TIMESTAMP;
			$item['endtime'] = TIMESTAMP + 6 * 86400;
		}
		if(checksubmit('submit')) {
			$data['title'] = !empty($_GPC['title']) ? trim($_GPC['title']) : message('请输入兑换名称！');
			$data['credittype'] = !empty($_GPC['credittype']) ? trim($_GPC['credittype']) : message('请选择积分类型！');
			$data['credit'] = intval($_GPC['credit']);
			if(empty($_GPC['extra']['title'])) {
				message('请输入兑换礼品的名称');
			}
			$data['extra'] = iserializer($_GPC['extra']);
			$data['thumb'] = trim($_GPC['thumb']);
			$data['status'] = trim($_GPC['status']);
			$data['pretotal'] = intval($_GPC['pretotal']) ? intval($_GPC['pretotal']) : message('请输入每人最大兑换次数');
			$data['total'] = intval($_GPC['total']) ? intval($_GPC['total']) : message('请输入兑换总数');
			$data['type'] = 3;
			$data['description'] = !empty($_GPC['description']) ? trim($_GPC['description']) : message('请输入兑换说明！');

			$starttime = strtotime($_GPC['datelimit']['start']);
			$endtime = strtotime($_GPC['datelimit']['end']);
			if ($endtime == $starttime) {
				$endtime = $endtime + 86399;
			}
			$data['starttime'] = $starttime;
			$data['endtime'] = $endtime;
			if(empty($id)) {
				$data['uniacid'] = $_W['uniacid'];
				pdo_insert('activity_exchange', $data);
				message('添加真实物品兑换成功',url('activity/exchange', array('type' => 'goods')),'success');
			} else {
				pdo_update('activity_exchange', $data, array('id' => $id, 'uniacid'=>$_W['uniacid']));
				message('更新真实物品兑换成功',url('activity/exchange', array('type' => 'goods')),'success');
			}
		}
	}
	if($do == 'display') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$where = ' WHERE type = 3 AND uniacid = :uniacid ';
		$params = array(':uniacid' => $_W['uniacid']);
		$title = trim($_GPC['keyword']);
		if (!empty($title)) {
			$where .= " AND title LIKE '%{$title}%'";
		}

		$list = pdo_fetchall('SELECT * FROM '.tablename('activity_exchange')." $where ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('activity_exchange'). $where , $params);
		$pager = pagination($total, $pindex, $psize);
		foreach ($list as &$row) {
			$extra = iunserializer($row['extra']);
			$row['extra'] = $extra;
			$row['thumb'] = tomedia($row['thumb']);
		}
	}
	if($do == 'del') {
		$id = intval($_GPC['id']);
		if(!empty($id)){
			$item = pdo_fetch('SELECT id FROM '.tablename('activity_exchange').' WHERE id=:id AND uniacid=:uniacid',array(':id'=>$id, ':uniacid'=>$_W['uniacid']));
		}
		if(empty($item)) {
			message('删除失败,指定兑换不存在或已删除.');
		}
		pdo_delete('activity_exchange', array('id'=>$id, 'uniacid'=>$_W['uniacid']));
		message('删除成功.', referer(),'success');
	}
	if($do == 'deliver') {
		$exchanges = pdo_fetchall('SELECT id, title FROM ' . tablename('activity_exchange') . ' WHERE uniacid = :uniacid ORDER BY id DESC', array(':uniacid' => $_W['uniacid']));
		$starttime = empty($_GPC['time']['start']) ? strtotime('-1 month') : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
		$where = " WHERE a.uniacid=:uniacid AND a.createtime >= :starttime AND a.createtime <= :endtime";
		$params = array(
			':uniacid' => $_W['uniacid'],
			':starttime' => $starttime,
			':endtime' => $endtime,
		);
		$uid = addslashes($_GPC['uid']);
		if (!empty($uid)) {
			$where .= ' AND ((a.name=:uid) or (a.mobile = :uid))';
			$params[':uid'] = $uid;
		}
		$exid = intval($_GPC['exid']);
		if (!empty($exid)) {
			$where .= " AND b.id = {$exid}";
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT a.*, b.title,b.extra,b.thumb FROM ".tablename('activity_exchange_trades_shipping'). ' AS a LEFT JOIN ' . tablename('activity_exchange') . ' AS b ON a.exid = b.id ' . " $where ORDER BY tid DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('activity_exchange_trades_shipping') . ' AS a LEFT JOIN ' . tablename('activity_exchange') . ' AS b ON a.exid = b.id '. $where , $params);
		if (checksubmit('export')) {
			$header = array(
				'title' => '标题', 'extra' => '兑换物品', 'name' => '收件人','createtime' => '兑换时间', 'mobile' => '收件人电话', 'zipcode' => '收件人邮编', 'address' => '收件地址', 'status' => '状态'
			);
			$html = "\xEF\xBB\xBF";
			foreach ($header as $li) {
				$html .= $li . "\t ,";
			}
			$html .= "\n";
			foreach ($list as $deliver) {
				foreach ($header as $key => $title) {
					if ($key == 'createtime') {
						$html .= date('Y-m-d', $deliver[$key]) . "\t ,";
					} elseif ($key == 'extra') {
						$extra = iunserializer($deliver[$key]);
						$html .= $extra['title']. "\t ,";
					} elseif ($key == 'status') {
						switch ($deliver['status']){
							case '0' :
								$status = '待发货';
								break;
							case '1' :
								$status = '待收货';
								break;
							case '2' :
								$status = '已收货';
								break;
							case '-1' :
								$status = '已关闭';
								break;
						}
						$html .= $status . "\t ,";
					} else {
						$html .= $deliver[$key] . "\t ,";
					}
				}
				$html .= "\n";
			}
			$html .= "\n";
			header("Content-type:text/csv");
			header("Content-Disposition:attachment; filename=会员数据.csv");
			echo $html;
			exit();
		}
		if(!empty($list)) {
			$uids = array();
			foreach ($list as $row) {
				$uids[] = $row['uid'];
			}
			$members = mc_fetch($uids, array('uid', 'nickname'));
			foreach ($list as &$row) {
				$row['extra'] = iunserializer($row['extra']);
				$row['nickname'] = $members[$row['uid']]['nickname'];
				$row['thumb'] = tomedia($row['thumb']);
			}
		}

		$pager = pagination($total, $pindex, $psize);
	}
	if($do == 'receiver') {
		$id = intval($_GPC['id']);
		$shipping = pdo_fetch('SELECT * FROM ' . tablename('activity_exchange_trades_shipping') . ' WHERE uniacid = :uniacid AND tid = :tid', array(':uniacid' => $_W['uniacid'], ':tid' => $id) );
		if(checksubmit('submit')) {
			$data = array(
				'name'=>$_GPC['realname'],
				'mobile'=>$_GPC['mobile'],
				'province'=>$_GPC['reside']['province'],
				'city'=>$_GPC['reside']['city'],
				'district'=>$_GPC['reside']['district'],
				'address'=>$_GPC['address'],
				'zipcode'=>$_GPC['zipcode'],
				'status'=>intval($_GPC['status'])
			);
			pdo_update('activity_exchange_trades_shipping', $data, array('tid' => $id));
			message('更新发货人信息成功', referer(), 'success');
		}
	}
	if($do == 'record-del') {
		$tid = intval($_GPC['id']);
		if(empty($tid)) {
			message('没有指定的兑换记录', url('activity/exchange/record'), 'error');
		}
		pdo_delete('activity_exchange_trades_shipping', array('uniacid' => $_W['uniacid'], 'tid' => $tid));
		pdo_delete('activity_exchange_trades', array('uniacid' => $_W['uniacid'], 'tid' => $tid));
		message('删除兑换记录成功', url('activity/exchange/record',array('type' => 'goods')), 'success');
	}
}
template('activity/exchange');