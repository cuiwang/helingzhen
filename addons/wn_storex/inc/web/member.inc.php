<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('edit', 'delete', 'deleteall', 'showall', 'status', 'clerk');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

pdo_delete('storex_member', array('weid' => $_W['uniacid'], 'from_user' => ''));
if ($op == 'edit') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$item = pdo_get('storex_member', array('id' => $id, 'weid' => $_W['uniacid']));
		if (empty($item)) {
			message('抱歉，用户不存在或是已经删除！', '', 'error');
		}
	}
	if (checksubmit('submit')) {
		$data = array(
			'weid' => $_W['uniacid'],
			'username' => $_GPC['username'],
			'realname' => $_GPC['realname'],
			'mobile' => $_GPC['mobile'],
			'score' => $_GPC['score'],
			'userbind' => $_GPC['userbind'],
			'isauto' => $_GPC['isauto'],
			'status' => $_GPC['status'],
			'clerk' => $_GPC['clerk'],
			'nickname' => trim($_GPC['nickname'])
		);
		if (!empty($data['clerk'])) {
			if (empty($id)) {
				if (empty($data['nickname'])) {
					message('请填写店员的微信昵称，否则无法获取到店员', '', 'info');
				}
			} else {
				$from_user = pdo_get('storex_member', array('id' => $id, 'weid' => $_W['uniacid']));
				if (empty($from_user['from_user']) && empty($data['nickname'])) {
					message('请填写店员的微信昵称，否则无法获取到店员', '', 'info');
				}
			}
			$from_user = pdo_get('mc_mapping_fans', array('nickname' => $data['nickname'], 'uniacid' => $_W['uniacid']));
			$data['from_user'] = $from_user['openid'];
			if (empty($data['from_user'])) {
				message('关注公众号后才能成为店员', referer(), 'info');
			}
		}
		if (!empty($data['password'])) {
			$data['salt'] = random(8);
			$data['password'] = hotel_member_hash($_GPC['password'], $data['salt']);
			//$data['password'] = md5($_GPC['password']);
		}
		if (empty($id)) {
			$c = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('storex_member') . " WHERE username = :username ", array(':username' => $data['username']));
			if ($c > 0) {
				message('用户名 ' . $data['username'] . ' 已经存在!', '', 'error');
			}
			$data['createtime'] = time();
			pdo_insert('storex_member', $data);
		} else {
			pdo_update('storex_member', $data, array('id' => $id));
		}
		message('用户信息更新成功！', $this->createWebUrl('member',array('clerk' => $data['clerk'])), 'success');
	}
	include $this->template('member_form');
}

if ($op == 'delete') {
	$id = intval($_GPC['id']);
	pdo_delete('storex_member', array('id' => $id));
	pdo_delete('storex_order', array('memberid' => $id));
	message('删除成功！', referer(), 'success');
}

if ($op == 'deleteall') {
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		pdo_delete('storex_member', array('id' => $id));
		pdo_delete('storex_order', array('memberid' => $id));
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
			pdo_update('storex_member', array('status' => $show_status), array('id' => $id));
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
	$temp = pdo_update('storex_member', array('status' => $_GPC['status']), array('id' => $id));
	if ($temp == false) {
		message('抱歉，刚才操作数据失败！', '', 'error');
	} else {
		message('状态设置成功！', referer(), 'success');
	}
}

if ($op == 'clerk') {
	$id = intval($_GPC['id']);
	if (empty($id)) {
		message('抱歉，传递的参数错误！', '', 'error');
	}
	$member = pdo_get('storex_member', array('id' => $id, 'weid' => intval($_W['uniacid'])));
	$clerk = pdo_get('storex_clerk', array('weid' => intval($_W['uniacid']), 'from_user' => $member['from_user']));
	if ($member['clerk'] == 1 && !empty($clerk)) {
		message('已经是店员了，不要重复操作', '', 'error');
	}
	if ($member['clerk'] != 1) {
		$temp = pdo_update('storex_member', array('clerk' => 1), array('id' => $id, 'weid' => intval($_W['uniacid'])));
		if ($temp == false) {
			message('抱歉，操作数据失败！', '', 'error');
		}
	}
	$fields = array('weid', 'userid', 'from_user', 'realname', 'mobile', 'score', 'createtime', 'userbind', 'status', 'username', 'password', 'salt', 'nickname', 'permission');
	$insert = array();
	foreach ($fields as $val) {
		if (!empty($member[$val])) {
			$insert[$val] = $member[$val];
		} else {
			$insert[$val] = '';
		}
		if ($val == 'createtime') {
			$insert['createtime'] = time();
		}
	}
	pdo_insert('storex_clerk', $insert);
	$insert_id = pdo_insertid();
	message('状态设置成功！', $this->createWebUrl('clerk',array('op' => 'edit', 'id' => $insert_id)), 'success');
}

if ($op == 'display') {
	$sql = '';
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
	$list = pdo_getall('storex_member', array('weid' => $_W['uniacid'], 'realname LIKE' => "%{$_GPC['realname']}%", 'mobile LIKE' => "%{$_GPC['mobile']}%"), array(), '', 'id DESC', ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('storex_member') . " WHERE weid = '{$_W['uniacid']}' $sql", $params);
	$pager = pagination($total, $pindex, $psize);
	include $this->template('member');
}