<?php
/**
 * 微画报模块微站定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_pictorialModuleSite extends WeModuleSite {

	public function doMobileDetail() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$huabao = pdo_fetch("SELECT * FROM ".tablename('hx_pictorial')." WHERE id = :id", array(':id' => $id));
		if (empty($huabao)) {
			message('画报不存在或是已经被删除！');
		}
		$result['list'] = pdo_fetchall("SELECT * FROM ".tablename('hx_pictorial_photo')." WHERE pictorialid = :huabaoid ORDER BY displayorder DESC", array(':huabaoid' => $huabao['id']));
		foreach ($result['list'] as &$photo) {
			$photo['items'] = pdo_fetchall("SELECT * FROM ".tablename('hx_pictorial_item')." WHERE photoid = :photoid", array(':photoid' => $photo['id']));
		}
		include $this->template('detail');
	}

	public function doMobileList() {
		global $_W, $_GPC;
		//$_W['styles'] = mobile_styles();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$result['list'] = pdo_fetchall("SELECT * FROM ".tablename('hx_pictorial')." WHERE weid = '{$_W['uniacid']}' AND isview = '1' ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_pictorial') . " WHERE weid = '{$_W['uniacid']}' AND isview = '1'");
		$result['pager'] = pagination($total, $pindex, $psize);
		include $this->template('list');
	}
	public function doWebQuery() {
		global $_W, $_GPC;
		$kwd = $_GPC['keyword'];
		$sql = 'SELECT * FROM ' . tablename('hx_pictorial') . ' WHERE `weid`=:weid AND `title` LIKE :title';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':title'] = "%{$kwd}%";
		$ds = pdo_fetchall($sql, $params);
		foreach($ds as &$row) {
			$r = array();
			$r['id'] = $row['id'];
			$r['title'] = $row['title'];
			$r['description'] = $row['content'];
			$r['thumb'] = $row['thumb'];
			$row['entry'] = $r;
		}
		include $this->template('query');
	}
	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W, $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		if (!empty($_GPC['createtime'])) {
			$c_s = strtotime($_GPC['createtime']['start']);
			$c_e = strtotime($_GPC['createtime']['end']);
			$condition .= " AND createtime >= '$c_s' AND createtime <= '$c_e'";
		}
		if (empty($_GPC['createtime'])) {
			$c_s = time() - 86400*30;
			$c_e = time() + 84400;
		}
		$list = pdo_fetchall("SELECT * FROM ".tablename('hx_pictorial')." WHERE weid = '{$_W['uniacid']}' $condition ORDER BY displayorder DESC, id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hx_pictorial') . " WHERE weid = '{$_W['uniacid']}' $condition");
		$pager = pagination($total, $pindex, $psize);
		if (!empty($list)) {
			foreach ($list as &$row) {
				$row['total'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('hx_pictorial_photo')." WHERE pictorialid = :pictorialid", array(':pictorialid' => $row['id']));
			}
		}
		load()->func('tpl');
		include $this->template('list');
	}

	public function doWebPhoto() {
		global $_W, $_GPC;
		$pictorialid = intval($_GPC['pictorialid']);
		$pictorial = pdo_fetch("SELECT id, type FROM ".tablename('hx_pictorial')." WHERE id = :id", array(':id' => $pictorialid));
		if (empty($pictorial)) {
			message('画报不存在或是已经被删除！');
		}

		if (checksubmit('submit')) {
			if (!empty($_GPC['item'])) {
				if (!empty($_GPC['id'])) {
					$data = array(
						'weid' => $_W['uniacid'],
						'pictorialid' => intval($_GPC['pictorialid']),
						'photoid' => intval($_GPC['photoid']),
						'type' => $_GPC['type'],
						'item' => $_GPC['item'],
						'url' => $_GPC['url'],
						'x' => $_GPC['x'],
						'y' => $_GPC['y'],
						'animation' => $_GPC['animation'],
						);
					pdo_update('hx_pictorial_item', $data, array('id' => $_GPC['id']));
				}else{
					$data = array(
						'weid' => $_W['uniacid'],
						'pictorialid' => intval($_GPC['pictorialid']),
						'photoid' => intval($_GPC['photoid']),
						'type' => $_GPC['type'],
						'item' => $_GPC['item'],
						'url' => $_GPC['url'],
						'x' => $_GPC['x'],
						'y' => $_GPC['y'],
						'animation' => $_GPC['animation'],
						'createtime' => time(),
						);
					pdo_insert('hx_pictorial_item', $data);
				}
			}
			if (!empty($_GPC['attachment-new'])) {
				foreach ($_GPC['attachment-new'] as $index => $row) {
					if (empty($row)) {
						continue;
					}
					$data = array(
						'weid' => $_W['uniacid'],
						'pictorialid' => $pictorialid,
						'title' => $_GPC['title-new'][$index],
						'url' => $_GPC['url-new'][$index],
						'attachment' => $_GPC['attachment-new'][$index],
						'displayorder' => $_GPC['displayorder-new'][$index],
						'createtime' => time(),
					);
					pdo_insert('hx_pictorial_photo', $data);
				}
			}
			if (!empty($_GPC['attachment'])) {
				foreach ($_GPC['attachment'] as $index => $row) {
					if (empty($row)) {
						continue;
					}
					$data = array(
						'weid' => $_W['uniacid'],
						'pictorialid' => $pictorialid,
						'title' => $_GPC['title'][$index],
						'url' => $_GPC['url'][$index],
						'attachment' => $_GPC['attachment'][$index],
						'displayorder' => $_GPC['displayorder'][$index],
					);
					pdo_update('hx_pictorial_photo', $data, array('id' => $index));
				}
			}
			message('画报更新成功！', $this->createWebUrl('photo', array('pictorialid' => $pictorialid)));
		}
		$photos = pdo_fetchall("SELECT * FROM ".tablename('hx_pictorial_photo')." WHERE pictorialid = :id ORDER BY displayorder DESC", array(':id' => $pictorialid));
		foreach ($photos as &$photo1) {
			$photo1['items'] = pdo_fetchall("SELECT * FROM ".tablename('hx_pictorial_item')." WHERE photoid = :photoid", array(':photoid' => $photo1['id']));
		}
		load()->func('tpl');
		include $this->template('photo');
	}

	public function doWebItem() {
		global $_W, $_GPC;
		$pictorialid = intval($_GPC['pictorialid']);
		$photoid = intval($_GPC['photoid']);
		$id = intval($_GPC['id']);
		$photo = pdo_fetch("SELECT * FROM ".tablename('hx_pictorial_photo')." WHERE id = :id", array(':id' => $photoid));
		if (empty($photo)) {
			message('场景不存在或是已经被删除！');
		}
		if (!empty($id)) {
			$item = pdo_fetch("SELECT * FROM ".tablename('hx_pictorial_item')." WHERE id = :id", array(':id' => $id));
		}
		load()->func('tpl');
		include $this->template('item');
	}

	public function doWebDelete(){
		global $_W, $_GPC;
		$type = $_GPC['type'];
		$id = intval($_GPC['id']);
		if ($type == 'photo') {
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM ".tablename('hx_pictorial_photo')." WHERE id = :id", array(':id' => $id));
				if (empty($item)) {
					message('图片不存在或是已经被删除！');
				}
				pdo_delete('hx_pictorial_photo', array('id' => $item['id']));
			}
		} elseif ($type == 'pictorial') {
			$pictorial = pdo_fetch("SELECT id, thumb FROM ".tablename('hx_pictorial')." WHERE id = :id", array(':id' => $id));
			if (empty($pictorial)) {
				message('画报不存在或是已经被删除！');
			}
			pdo_delete('hx_pictorial', array('id' => $id));
			pdo_delete('hx_pictorial_photo', array('pictorialid' => $id));
		}
		message('删除成功！', referer(), 'success');
	}

	public function doWebRdelete(){
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		if (!empty($id)) {
			$item = pdo_fetch("SELECT * FROM ".tablename('hx_pictorial_reply')." WHERE id = :id", array(':id' => $id));
			if (empty($item)) {
				message('项目不存在或是已经被删除！');
			}
			pdo_delete('hx_pictorial_reply', array('id' => $item['id']));
		}
		message('删除成功！', referer(), 'success');
	}

	public function doWebAdd() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		if (!empty($id)) {
			$item = pdo_fetch("SELECT * FROM ".tablename('hx_pictorial')." WHERE id = :id" , array(':id' => $id));
			if (empty($item)) {
				message('抱歉，画报不存在或是已经删除！', '', 'error');
			}
		}
		if (checksubmit('submit')) {
			if (empty($_GPC['title'])) {
				message('请输入画报名称！');
			}
			$data = array(
				'weid' => $_W['uniacid'],
				'title' => $_GPC['title'],
				'thumb' => $_GPC['thumb'],
				'loading' => $_GPC['loading'],
				'music' => $_GPC['music'],
				'open' => $_GPC['open'],
				'ostyle' => $_GPC['ostyle'],
				'icon' => $_GPC['icon'],
				'share' => $_GPC['share'],
				'content' => $_GPC['content'],
				'displayorder' => intval($_GPC['displayorder']),
				'isloop' => intval($_GPC['isloop']),
				'isview' => intval($_GPC['isview']),
				'type' => intval($_GPC['type']),
				'createtime' => TIMESTAMP,
			);
			if ($_GPC['mset'][0]) {
				$data['mauto'] = 1;
			}else{
				$data['mauto'] = 0;
			}
			if ($_GPC['mset'][1]) {
				$data['mloop'] = 1;
			}else{
				$data['mloop'] = 0;
			}
			if (empty($id)) {
				pdo_insert('hx_pictorial', $data);
			} else {
				unset($data['createtime']);
				pdo_update('hx_pictorial', $data, array('id' => $id));
			}
			message('画报更新成功！', $this->createWebUrl('list'), 'success');
		}
		load()->func('tpl');
		include $this->template('add');
	}

}