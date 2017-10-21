<?php
class Menu_EweiShopV2Page extends PluginWebPage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		$type = intval($_GPC['type']);
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_pc_menu') . ' WHERE type=:type AND uniacid=:uniacid', array(':uniacid' => $_W['uniacid'], ':type' => $type));
		include $this->template();
	}
	public function add() 
	{
		$this->post();
	}
	public function edit() 
	{
		$this->post();
	}
	protected function post() 
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$type = intval($_GPC['type']);
		if ($_W['ispost']) 
		{
			$data = array('uniacid' => $_W['uniacid'], 'title' => trim($_GPC['title']), 'link' => trim($_GPC['link']), 'enabled' => intval($_GPC['enabled']), 'displayorder' => intval($_GPC['displayorder']));
			if (!(empty($id))) 
			{
				pdo_update('ewei_shop_pc_menu', $data, array('id' => $id));
				plog("shop.menu.edit", '修改菜单 ID: ' . $id);
			}
			else 
			{
				$data['createtime'] = time();
				$data['type'] = $type;
				pdo_insert("ewei_shop_pc_menu", $data);
				$id = pdo_insertid();
				plog("shop.menu.add", '添加菜单 ID: ' . $id);
			}
			show_json(1, array("url" => webUrl("pc/menu", array("type" => $type))));
		}
		$menu = pdo_fetch('select * from ' . tablename('ewei_shop_pc_menu') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		include $this->template();
	}
	public function delete() 
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		if (empty($id)) 
		{
			$id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
		}
		$items = pdo_fetchall('SELECT id,title FROM ' . tablename('ewei_shop_pc_menu') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
		foreach ($items as $item ) 
		{
			pdo_delete('ewei_shop_pc_menu', array('id' => $item['id']));
			plog("pc.menu.delete", '删除菜单 ID: ' . $item['id'] . ' 标题: ' . $item['title'] . ' ');
		}
		show_json(1, array("url" => referer()));
	}
	public function displayorder() 
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$displayorder = intval($_GPC['value']);
		$item = pdo_fetchall('SELECT id,title FROM ' . tablename('ewei_shop_pc_menu') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
		if (!(empty($item))) 
		{
			pdo_update('ewei_shop_pc_menu', array('displayorder' => $displayorder), array('id' => $id));
			plog("pc.menu.edit", '修改菜单排序 ID: ' . $item['id'] . ' 标题: ' . $item['title'] . ' 排序: ' . $displayorder . ' ');
		}
		show_json(1);
	}
	public function enabled() 
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		if (empty($id)) 
		{
			$id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
		}
		$items = pdo_fetchall('SELECT id,title FROM ' . tablename('ewei_shop_pc_menu') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
		foreach ($items as $item ) 
		{
			pdo_update('ewei_shop_pc_menu', array('enabled' => intval($_GPC['enabled'])), array('id' => $item['id']));
			plog("pc.menu.edit", (('修改菜单状态<br/>ID: ' . $item['id'] . '<br/>标题: ' . $item['title'] . '<br/>状态: ' . $_GPC['enabled']) == 1 ? '显示' : '隐藏'));
		}
		show_json(1, array("url" => referer()));
	}
}
?>