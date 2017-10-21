<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 
 */
defined('IN_IA') or exit('Access Denied');
load()->model('pc');

$do = !empty($do) ? $do : 'page';
$do = in_array($do, array('design', 'page', 'quickmenu', 'uc', 'del')) ? $do : 'page';

if ($do == 'design') {
	$_W['page']['title'] = '专题页面 - 微站功能';
	$multiid = intval($_GPC['multiid']);
	$id = intval($_GPC['id']);
	if (!empty($_GPC['wapeditor'])) {
		$params = $_GPC['wapeditor']['params'];
		if (empty($params)) {
			message('请您先设计电脑端页面.', referer(), 'error');
		}
		$params = json_decode(ihtml_entity_decode($params), true);
		if (empty($params)) {
			message('请您先设计电脑端页面.', referer(), 'error');
		}
		$page = $params[0];
		$html = htmlspecialchars_decode($_GPC['wapeditor']['html'], ENT_QUOTES);
		$data = array(
			'uniacid' => $_W['uniacid'],
			'multiid' => '0',
			'title' => $page['params']['title'],
			'description' => $page['params']['description'],
			'type' => 1,
			'status' => 1,
			'params' => json_encode($params),
			'html' => $html,
			'createtime' => TIMESTAMP,
		);
		if (empty($id)) {
			pdo_insert('fournet_pcpage', $data);
			$id = pdo_insertid();
		} else {
			pdo_update('fournet_pcpage', $data, array('id' => $id));
		}
		if (!empty($page['params']['keyword'])) {
			$cover = array(
				'uniacid' => $_W['uniacid'],
				'title' => $page['params']['title'],
				'keyword' => $page['params']['keyword'],
				'url' => murl('home/page', array('id' => $id), true, false),
				'description' => $page['params']['description'],
				'thumb' => $page['params']['thumb'],
				'module' => 'page',
				'multiid' => $id,
			);
			site_cover($cover);
		}
		message('页面保存成功.', url('fournet/pceditor/design', array('id' => $id, 'multiid' => $multiid)), 'success');
	} else {
		$page = pdo_fetch("SELECT * FROM ".tablename('fournet_pcpage')." WHERE id = :id", array(':id' => $id));
		template('fournet/pceditor');
	}
} elseif ($do == 'page') {
	$_W['page']['title'] = '专题页面 - 微站功能';
	uni_user_permission_check('site_editor_page');
	$page = max(1, intval($_GPC['page']));
	$pagesize = 20;
	$list = pdo_fetchall("SELECT * FROM ".tablename('fournet_pcpage')." WHERE type = '1' AND uniacid = :uniacid LIMIT ".(($page-1) * $pagesize).','.$pagesize, array(':uniacid' => $_W['uniacid']));
	if (!empty($list)) {
		foreach ($list as &$row) {
			$row['params'] = json_decode($row['params'], true);
		}
		unset($row);
	}
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('fournet_pcpage')." WHERE type = '1'");
	$pager = pagination($total, $page, $pagesize);
	template('fournet/pceditor');
} elseif ($do == 'uc') {
	$_W['page']['title'] = '会员中心 - 微站功能';
	uni_user_permission_check('site_editor_uc');
	if (!empty($_GPC['wapeditor'])) {
		$params = $_GPC['wapeditor']['params'];
		if (empty($params)) {
			message('请您先设计电脑端页面.', '', 'error');
		}
		$params = json_decode(ihtml_entity_decode($params), true);
		if (empty($params)) {
			message('请您先设计电脑端页面.', '', 'error');
		}
		$page = $params[0];
		$html = htmlspecialchars_decode($_GPC['wapeditor']['html'], ENT_QUOTES);
		$data = array(
			'uniacid' => $_W['uniacid'],
			'multiid' => '0',
			'title' => $page['params']['title'],
			'description' => $page['params']['description'],
			'type' => 3,
			'status' => 1,
			'params' => json_encode($params),
			'html' => $html,
			'createtime' => TIMESTAMP,
		);
		$id = pdo_fetchcolumn("SELECT id FROM ".tablename('fournet_pcpage')." WHERE uniacid = :uniacid AND type = '3'", array(':uniacid' => $_W['uniacid']));
		if (empty($id)) {
			pdo_insert('fournet_pcpage', $data);
			$id = pdo_insertid();
		} else {
			pdo_update('fournet_pcpage', $data, array('id' => $id));
		}
		if (!empty($page['params']['keyword'])) {
			$cover = array(
				'uniacid' => $_W['uniacid'],
				'title' => $page['params']['title'],
				'keyword' => $page['params']['keyword'],
				'url' => murl('mc/home', array(), true, false),
				'description' => $page['params']['description'],
				'thumb' => $page['params']['cover'],
				'module' => 'mc',
			);
			site_cover($cover);
		}
				$nav = json_decode(ihtml_entity_decode($_GPC['wapeditor']['nav']), true);
		$ids = array(0);
		if (!empty($nav)) {
			foreach ($nav as $row) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $row['name'],
					'position' => 2,
					'url' => $row['url'],
					'icon' => '',
					'css' => iserializer($row['css']),
					'status' => 1,
					'displayorder' => 0,
				);
				if (!empty($row['id'])) {
					pdo_update('fournet_pcnav', $data, array('id' => $row['id']));
				} else {
					pdo_insert('fournet_pcnav', $data);
					$row['id'] = pdo_insertid();
				}
				$ids[] = $row['id'];
			}
		}
		$ids_str = implode(',', $ids);
		pdo_query('DELETE FROM ' . tablename('fournet_pcnav') . " WHERE uniacid = :uniacid AND position = '2' AND id NOT IN ($ids_str)", array(':uniacid' => $_W['uniacid']));
		message('个人中心保存成功.', url('fournet/pceditor/uc'), 'success');
	}
	$navs = pdo_fetchall("SELECT id, icon, css, name, url FROM ".tablename('fournet_pcnav')." WHERE uniacid = :uniacid AND position = '2' ORDER BY displayorder DESC, id ASC", array(':uniacid' => $_W['uniacid']));
	if(!empty($navs)) {
		foreach($navs as &$nav) {
			
			if (!empty($nav['icon'])) {
				$nav['icon'] = tomedia($nav['icon']);
			}
			if (is_serialized($nav['css'])) {
				$nav['css'] = iunserializer($nav['css']);
			}
			if(empty($nav['css']['icon']['icon'])) {
				$nav['css']['icon']['icon'] = 'fa fa-external-link';
			}
		}
	}
	$page = pdo_fetch("SELECT * FROM ".tablename('fournet_pcpage')." WHERE uniacid = :uniacid AND type = '3'", array(':uniacid' => $_W['uniacid']));
	template('fournet/pceditor');
} elseif ($do == 'quickmenu') {
	$_W['page']['title'] = '快捷菜单 - 站点管理 - 微站功能';
	$multiid = intval($_GPC['multiid']);
	$type = intval($_GPC['type']) ? intval($_GPC['type']) : 2;

	if ($_GPC['wapeditor']) {
		$params = $_GPC['wapeditor']['params'];
		if (empty($params)) {
			message('请您先设计电脑端页面.', '', 'error');
		}
		$params = json_decode(html_entity_decode(urldecode($params)), true);
		if (empty($params)) {
			message('请您先设计电脑端页面.', '', 'error');
		}
		$html = htmlspecialchars_decode($_GPC['wapeditor']['html'], ENT_QUOTES);
		$data = array(
			'uniacid' => $_W['uniacid'],
			'multiid' => $multiid,
			'title' => '底部菜单',
			'description' => '',
			'status' => intval($_GPC['status']),
			'type' => $type,
			'params' => json_encode($params),
			'html' => $html,
			'createtime' => TIMESTAMP,
		);
		if ($type == '4') {
			$id = pdo_fetchcolumn("SELECT id FROM ".tablename('fournet_pcpage')." WHERE uniacid = :uniacid AND type = :type", array(':uniacid' => $_W['uniacid'], ':type' => $type));
		} else {
			$id = pdo_fetchcolumn("SELECT id FROM ".tablename('fournet_pcpage')." WHERE multiid = :multiid AND type = :type", array(':multiid' => $multiid, ':type' => $type));
		}
		if (!empty($id)) {
			pdo_update('fournet_pcpage', $data, array('id' => $id));
		} else {
			pdo_insert('fournet_pcpage', $data);
			$id = pdo_insertid();
		}
		message('底部菜单保存成功.', url('fournet/pceditor/quickmenu', array('multiid' => $multiid, 'type' => $type)), 'success');
	}
	if ($type == '4') {
		$page = pdo_fetch("SELECT * FROM ".tablename('fournet_pcpage')." WHERE type = :type AND uniacid = :uniacid", array(':type' => $type, ':uniacid' => $_W['uniacid']));
	} else {
		$page = pdo_fetch("SELECT * FROM ".tablename('fournet_pcpage')." WHERE multiid = :multiid AND type = :type", array(':multiid' => $multiid, ':type' => $type));
	}
	$modules = uni_modules();
	template('fournet/pceditor');
}  elseif ($do == 'del') {
	$id = intval($_GPC['id']);
	pdo_delete('fournet_pcpage', array('id' => $id, 'uniacid' => $_W['uniacid']));
	site_cover_delete($id);
	message('删除微页面成功', referer(), 'success');
}