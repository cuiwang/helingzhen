<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('display','post');
$do = in_array($do, $dos) ? $do : 'display';


if ($do == 'display') {
	uni_user_permission_check('system_user_fields');
	$_W['page']['title'] = '字段管理 - 用户管理';
	$condition = '' ;
	$params = array();
	if (!empty($_GPC['keyword'])) {
		$condition .= " WHERE title LIKE :title";
		$params[':title'] = "%{$_GPC['keyword']}%";
	}
	if (checksubmit('submit')) {
		if (!empty($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $id => $displayorder) {
				pdo_update('profile_fields', array(
				'displayorder' => intval($displayorder),
				'available' => intval($_GPC['available'][$id]),
				'showinregister' => intval($_GPC['showinregister'][$id]),
				'required' => intval($_GPC['required'][$id]),
				), array('id' => $id));
			}
		}
		itoast('资料设置更新成功！', referer(), 'success');
	}
	$sql = "SELECT * FROM " . tablename('profile_fields'). $condition ." ORDER BY displayorder DESC";
	$fields = pdo_fetchall($sql, $params);
	template('user/fields-display');
}

if ($do == 'post') {
	uni_user_permission_check('system_user_fields_post');
	$_W['page']['title'] = '编辑字段 - 用户管理';
	$id = intval($_GPC['id']);

	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			itoast('抱歉，请填写资料名称！', '', '');
		}
		if (empty($_GPC['field'])) {
			itoast('请填写字段名!', '', '');
		}
		if (!preg_match('/^[A-Za-z0-9_]*$/', $_GPC['field'])) {
			itoast('请使用字母或数字或下划线组合字段名!', '', '');
		}
		$data = array(
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'displayorder' => intval($_GPC['displayorder']),
			'available' => intval($_GPC['available']),
			'unchangeable' => intval($_GPC['unchangeable']),
			'showinregister' => intval($_GPC['showinregister']),
			'required' => intval($_GPC['required']),
			'field' => trim($_GPC['field']),
			'field_length' => intval($_GPC['length'])
		);
		$length = intval($_GPC['length']);
		if (empty($id)) {
			pdo_insert('profile_fields', $data);
			if (!pdo_fieldexists('users_profile', $data['field'])) {
				pdo_query("ALTER TABLE ". tablename('users_profile'). " ADD `". $data['field']."` varchar({$length}) NOT NULL default '';");
			}
			if (!pdo_fieldexists('mc_members', $data['field'])) {
				pdo_query("ALTER TABLE ". tablename('mc_members'). " ADD `". $data['field']."` varchar({$length}) NOT NULL default '';");
			}
		} else {
			if (!pdo_fieldexists('users_profile', $data['field'])) {
				pdo_query("ALTER TABLE ". tablename('users_profile'). " ADD `". $data['field']."` varchar({$length}) NOT NULL default '';");
			} else {
				pdo_query("ALTER TABLE ". tablename('users_profile'). " CHANGE `". $data['field']. "` `". $data['field']."` varchar({$length}) NOT NULL default ''");
			}
			if (!pdo_fieldexists('mc_members', $data['field'])) {
				pdo_query("ALTER TABLE ". tablename('mc_members'). " ADD `". $data['field']."` varchar({$length}) NOT NULL default '';");
			} else {
				pdo_query("ALTER TABLE ". tablename('mc_members'). " CHANGE `". $data['field']. "` `". $data['field']."` varchar({$length}) NOT NULL default ''");
			}
			pdo_update('profile_fields', $data, array('id' => $id));
		}
		itoast('更新字段成功！', url('user/fields'), 'success');
	}

	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM ".tablename('profile_fields')." WHERE id = :id", array(':id' => $id));
	}
	template('user/fields-post');
}