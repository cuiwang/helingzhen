<?php
class Adv_EweiShopV2Page extends PluginWebPage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_pc_adv') . ' WHERE uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
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
		$adv = pdo_fetch('select * from ' . tablename('ewei_shop_pc_adv') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		if (empty($adv)) 
		{
			message('无效的访问');
		}
		if ($_W['ispost']) 
		{
			$data = array('uniacid' => $_W['uniacid'], 'title' => trim($_GPC['title']), 'link' => trim($_GPC['link']), 'enabled' => intval($_GPC['enabled']), 'src' => trim($_GPC['src']), 'alt' => trim($_GPC['alt']));
			if (!(empty($id))) 
			{
				pdo_update('ewei_shop_pc_adv', $data, array('id' => $id));
				plog("shop.adv.edit", '修改广告 ID: ' . $id);
			}
			else 
			{
				message("无效的访问");
			}
			show_json(1, array("url" => webUrl("pc/adv", array("type" => $type))));
		}
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
		$items = pdo_fetchall('SELECT id,title FROM ' . tablename('ewei_shop_pc_adv') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
		foreach ($items as $item ) 
		{
			pdo_delete('ewei_shop_pc_adv', array('id' => $item['id']));
			plog("pc.adv.delete", '删除广告 ID: ' . $item['id'] . ' 标题: ' . $item['title'] . ' ');
		}
		show_json(1, array("url" => referer()));
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
		$items = pdo_fetchall('SELECT id,title FROM ' . tablename('ewei_shop_pc_adv') . ' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']);
		foreach ($items as $item ) 
		{
			pdo_update('ewei_shop_pc_adv', array('enabled' => intval($_GPC['enabled'])), array('id' => $item['id']));
			plog("pc.adv.edit", (('修改广告状态<br/>ID: ' . $item['id'] . '<br/>标题: ' . $item['title'] . '<br/>状态: ' . $_GPC['enabled']) == 1 ? '显示' : '隐藏'));
		}
		show_json(1, array("url" => referer()));
	}
}
?>