<?php
global $_W, $_GPC;
$this -> backlists();
$id = intval($_GPC['id']);
//订单ID
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'print_list';
if ($op == 'print_post') {
	if ($id > 0) {
		$item = pdo_fetch('SELECT * FROM ' . tablename('wz_tuan_print') . ' WHERE uniacid = :uniacid AND id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
	}
	if (empty($item)) {
		$item = array('status' => 1, 'print_nums' => 1);
	}
	if (checksubmit('submit')) {
		$data['status'] = intval($_GPC['status']);
		$data['mode'] = intval($_GPC['mode']);
		$data['name'] = !empty($_GPC['name']) ? trim($_GPC['name']) : message('打印机名称不能为空', '', 'error');
		$data['print_no'] = !empty($_GPC['print_no']) ? trim($_GPC['print_no']) : message('机器号不能为空', '', 'error');
		$data['member_code'] = $_GPC['member_code'];
		$data['key'] = !empty($_GPC['key']) ? trim($_GPC['key']) : message('打印机key不能为空', '', 'error');
		$data['print_nums'] = intval($_GPC['print_nums']) ? intval($_GPC['print_nums']) : 1;
		if (!empty($_GPC['qrcode_link']) && (strexists($_GPC['qrcode_link'], 'http://') || strexists($_GPC['qrcode_link'], 'https://'))) {
			$data['qrcode_link'] = trim($_GPC['qrcode_link']);
		}
		$data['uniacid'] = $_W['uniacid'];
		$data['sid'] = $sid;
		if (!empty($item) && $id) {
			pdo_update('wz_tuan_print', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
		} else {
			pdo_insert('wz_tuan_print', $data);
		}
		message('更新打印机设置成功', $this -> createWebUrl('print', array('op' => 'print_list')), 'success');
	}

} elseif ($op == 'print_list') {
	$data = pdo_fetchall('SELECT * FROM ' . tablename('wz_tuan_print') . ' WHERE uniacid = :uniacid ', array(':uniacid' => $_W['uniacid']));
	// include $this->template('print');
} elseif ($op == 'print_del') {
	$id = intval($_GPC['id']);
	pdo_delete('wz_tuan_print', array('uniacid' => $_W['uniacid'], 'id' => $id));
	message('删除打印机成功', referer(), 'success');
} elseif ($op == 'log_del') {
	$id = intval($_GPC['id']);
	pdo_delete('wz_tuan_order_print', array('uniacid' => $_W['uniacid'], 'id' => $id));
	message('删除打印记录成功', referer(), 'success');
} elseif ($op == 'print_log') {
	$id = intval($_GPC['id']);
	$item = pdo_fetch('SELECT * FROM ' . tablename('wz_tuan_print') . ' WHERE uniacid = :uniacid AND id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
	if (empty($item)) {
		message('打印机不存在或已删除', $this -> createWebUrl('print', array('op' => 'print_list')), 'success');
	}
	if (!empty($item['print_no']) && !empty($item['key'])) {
		include_once 'wprint.class.php';
		$wprint = new wprint();
		$status = $wprint -> QueryPrinterStatus($item['print_no'], $item['key']);
		if (is_error($status)) {
			$status = '查询打印机状态失败。请刷新页面重试';
		}
	}
	$condition = ' WHERE a.uniacid = :aid AND a.sid = :sid AND a.pid = :pid';
	$params[':aid'] = $_W['uniacid'];
	$params[':sid'] = $sid;
	$params[':pid'] = $id;
	if (!empty($_GPC['oid'])) {
		$oid = trim($_GPC['oid']);
		$condition .= ' AND a.oid = :oid';
		$params[':oid'] = $oid;
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_order_print') . ' AS a ' . $condition, $params);
	$data = pdo_fetchall('SELECT a.*,b.* FROM ' . tablename('wz_tuan_order_print') . ' AS a LEFT JOIN' . tablename('shopping_order') . ' AS b ON a.oid = b.id' . $condition . ' ORDER BY addtime DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
	$pager = pagination($total, $pindex, $psize);
	// include $this->template('print');
}
include $this -> template('web/print');
?>