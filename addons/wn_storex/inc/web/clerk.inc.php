<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('edit', 'delete', 'deleteall', 'showall', 'status', 'clerkcommentlist');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

pdo_delete('storex_clerk', array('weid' => $_W['uniacid'], 'from_user' => ''));
if ($op == 'edit') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$item = pdo_get('storex_clerk', array('id' => $id, 'weid' => $_W['uniacid']));
		if (empty($item)) {
			message('抱歉，用户不存在或是已经删除！', '', 'error');
		}
		if (!empty($item['permission'])) {
			$item['permission'] = iunserializer($item['permission']);
			if (!empty($item['permission'])) {
				foreach ($item['permission'] as $key => $value) {
					$stores_ids[] = $key;
				}
			}
			$store_lists = pdo_getall('storex_bases', array('id' => $stores_ids), array('id', 'store_type', 'title', 'thumb'), 'id');
			if (!empty($store_lists)) {
				foreach ($store_lists as $key => &$store_info) {
					$store_info['thumb'] = tomedia($store_info['thumb']);
					$store_info['permission'] = $item['permission'][$key];
				}
			}
		}
	}
	if (!empty($item['from_user'])) {
		load()->model('mc');
		$uid = mc_openid2uid($item['from_user']);
		$address_info = pdo_getall('mc_member_address', array('uid' => $uid, 'uniacid' => $_W['uniacid']), '', '', 'isdefault DESC');
	}
	$stores = pdo_getall('storex_bases', array('weid' => intval($_W['uniacid'])), array('id', 'title', 'store_type', 'thumb'), 'id');
	foreach ($stores as &$value) {
		$value['thumb'] = tomedia($value['thumb']);
	}
	if (checksubmit('submit')) {
		$data = array(
			'weid' => intval($_W['uniacid']),
			'username' => trim($_GPC['username']),
			'realname' => trim($_GPC['realname']),
			'mobile' => $_GPC['mobile'],
			'status' => intval($_GPC['status']),
			'from_user' => $_GPC['from_user'],
			'permission' => iserializer($_GPC['permission']),
		);
		if (empty($id)) {
			if (empty($data['from_user'])) {
				message('请填写店员的微信openid，否则无法获取到店员信息', '', 'info');
			}
		}
		$from_user = pdo_get('mc_mapping_fans', array('openid' => $data['from_user'], 'uniacid' => $_W['uniacid']));
		if (empty($from_user)) {
			message('关注公众号后才能成为店员', referer(), 'info');
		}
		if (empty($id)) {
			$exist = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_clerk') . " WHERE username=:username ", array(":username" => $data['username']));
			if ($exist > 0) {
				message("用户名 " . $data['username'] . " 已经存在!", "", "error");
			}
			$data['createtime'] = time();
			$result = pdo_get('storex_clerk', array('from_user' => $data['from_user'], 'weid' => $_W['uniacid']));
			if (!empty($result)) {
				pdo_update('storex_clerk', $data, array('id' => $result['id']));
			} else {
				pdo_insert('storex_clerk', $data);
			}
		} else {
			pdo_update('storex_clerk', $data, array('id' => $id, 'weid' => intval($_W['uniacid'])));
		}
		message('用户信息更新成功！', $this->createWebUrl('clerk'), 'success');
	}
	include $this->template('clerk_form');
}

if ($op == 'delete') {
	$id = intval($_GPC['id']);
	pdo_delete('storex_clerk', array('id' => $id));
	message('删除成功！', referer(), 'success');
}

if ($op == 'deleteall') {
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		pdo_delete('storex_clerk', array('id' => $id));
	}
	$this->web_message('规则操作成功！', '', 0);
	exit();
}

if ($op == 'showall') {
	if ($_GPC['show_name'] == 'showall') {
		$show_status = 1;
	} else {
		$show_status = 0;
	}
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		if (!empty($id)) {
			pdo_update('storex_clerk', array('status' => $show_status), array('id' => $id));
		}
	}
	$this->web_message('操作成功！', '', 0);
	exit();
}

if ($op == 'status') {
	$id = intval($_GPC['id']);
	if (empty($id)) {
		message('抱歉，传递的参数错误！', '', 'error');
	}
	$temp = pdo_update('storex_clerk', array('status' => $_GPC['status']), array('id' => $id));
	
	if ($temp == false) {
		message('抱歉，刚才操作数据失败！', '', 'error');
	} else {
		message('状态设置成功！', referer(), 'success');
	}
}

if ($op == 'clerkcommentlist') {
	$id = intval($_GPC['id']);
	$where = ' WHERE `uniacid` = :uniacid';
	$params = array(':uniacid' => intval($_W['uniacid']));
	$sql = 'SELECT COUNT(*) FROM ' . tablename('storex_comment_clerk') . $where;
	$total = pdo_fetchcolumn($sql, $params);
	if ($total > 0) {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$comments = pdo_getall('storex_comment_clerk', array('uniacid' => intval($_W['uniacid'])), array(), '', 'id DESC', ($pindex - 1) * $psize . ',' . $psize);
		$pager = pagination($total, $pindex, $psize);
	}
	include $this->template('clerk_comment');
}

if ($op == 'display') {
	$sql = "";
	$params = array();
	if (!empty($_GPC['realname'])) {
		$sql .= ' AND `realname` LIKE :realname';
		$params[':realname'] = "%{$_GPC['realname']}%";
	}
	if (!empty($_GPC['mobile'])) {
		$sql .= ' AND `mobile` LIKE :mobile';
		$params[':mobile'] = "%{$_GPC['mobile']}%";
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$list = pdo_getall('storex_clerk', array('weid' => $_W['uniacid'], 'realname LIKE' => "%{$_GPC['realname']}%", 'mobile LIKE' => "%{$_GPC['mobile']}%"), array(), '', 'id DESC', ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_clerk') . " WHERE `weid` = '{$_W['uniacid']}' $sql", $params);
	$pager = pagination($total, $pindex, $psize);
	include $this->template('clerk');
}