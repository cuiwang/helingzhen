<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require EWEI_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';
class Data_EweiShopV2Page extends MerchWebPage 
{
	public function __construct($_com = 'virtual') 
	{
		parent::__construct($_com);
	}
	public function main() 
	{
		global $_W;
		global $_GPC;
		$typeid = $_GPC['typeid'];
		if (empty($typeid)) 
		{
			$this->message('Url参数错误！请重试！', merchUrl('goods/virtual/temp'), 'error');
			exit();
		}
		$kw = $_GPC['keyword'];
		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$pindex = max(1, intval($page));
		$psize = 100;
		$type = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_type') . ' WHERE id=:id and uniacid=:uniacid and merchid=:merchid ', array(':id' => $typeid, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
		$type['fields'] = iunserializer($type['fields']);
		$condition = ' and d.typeid=:typeid and d.uniacid=:uniacid and d.merchid=:merchid';
		$params = array(':typeid' => $typeid, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']);
		if ($_GPC['status'] == '0') 
		{
			$condition .= ' and d.openid=\'\'';
		}
		else if ($_GPC['status'] == '1') 
		{
			$condition .= ' and d.openid<>\'\'';
		}
		if (!(empty($_GPC['keyword']))) 
		{
			$_GPC['keyword'] = trim($_GPC['keyword']);
			$condition .= ' and d.pvalue like :pvalue';
			$params[':pvalue'] = '%' . $_GPC['keyword'] . '%';
		}
		$items = pdo_fetchall('SELECT d.*,m.avatar,m.nickname,m.realname,m.mobile  FROM ' . tablename('ewei_shop_virtual_data') . ' d ' . ' left join ' . tablename('ewei_shop_member') . ' m on d.openid<>\'\' and m.openid = d.openid and m.uniacid= d.uniacid ' . ' left join ' . tablename('ewei_shop_order') . ' o on d.orderid<>0 and  o.id = d.orderid ' . ' where  1 ' . $condition . ' order by id desc limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
		foreach ($items as &$row ) 
		{
			$carrier = iunserializer($row['carrier']);
			if (is_array($carrier)) 
			{
				$row['realname'] = $carrier['carrier_realname'];
				$row['mobile'] = $carrier['carrier_mobile'];
			}
		}
		unset($row);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_virtual_data') . ' d ' . ' left join ' . tablename('ewei_shop_member') . ' m on d.openid<>\'\' and m.openid = d.openid and m.uniacid= d.uniacid ' . ' left join ' . tablename('ewei_shop_order') . ' o on d.orderid<>0 and  o.id = d.orderid ' . ' where 1 ' . $condition . ' ', $params);
		$pager = pagination($total, $pindex, $psize);
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
		$typeid = $_GPC['typeid'];
		$editid = $_GPC['id'];
		if (empty($typeid)) 
		{
			show_json(0, 'Url参数错误！请重试！');
			exit();
		}
		$item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_type') . ' WHERE id=:id and uniacid=:uniacid and merchid=:merchid ', array(':id' => $_GPC['typeid'], ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
		if (!(empty($item))) 
		{
			$item['fields'] = iunserializer($item['fields']);
		}
		if (!(empty($editid))) 
		{
			$data = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_data') . ' WHERE id=:id and typeid=:typeid and uniacid=:uniacid and merchid=:merchid ', array(':id' => $editid, ':typeid' => $typeid, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
			$data['edit'] = $editid;
		}
		if ($_W['ispost']) 
		{
			$typeid = intval($_GPC['typeid']);
			if (!(empty($typeid))) 
			{
				$item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_type') . ' WHERE id=:id and uniacid=:uniacid and merchid=:merchid ', array(':id' => $typeid, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
				$item['fields'] = iunserializer($item['fields']);
				if (!(empty($item['fields']))) 
				{
					$tpids = $_GPC['tp_id'];
					foreach ($tpids as $index => $id ) 
					{
						$values = array();
						foreach ($item['fields'] as $key => $name ) 
						{
							$values[$key] = $_GPC['tp_value_' . $key][$index];
						}
						$insert = array('typeid' => $_GPC['typeid'], 'pvalue' => $values['key'], 'fields' => iserializer($values), 'uniacid' => $_W['uniacid'], 'merchid' => $_W['merchid']);
						$datas = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_data') . ' WHERE id=:id and typeid=:typeid and uniacid=:uniacid and merchid=:merchid ', array(':id' => $id, ':typeid' => $typeid, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
						if (empty($datas)) 
						{
							$keydata = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_data') . ' WHERE pvalue=:pvalue  and typeid=:typeid and uniacid=:uniacid and merchid=:merchid', array(':pvalue' => $insert['pvalue'], ':typeid' => $typeid, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
							if (empty($keydata)) 
							{
								pdo_insert('ewei_shop_virtual_data', $insert);
								pdo_update('ewei_shop_virtual_type', 'alldata=alldata+1', array('id' => $item['id']));
							}
							else 
							{
								pdo_update('ewei_shop_virtual_data', $insert, array('id' => $keydata['id']));
							}
						}
						else 
						{
							$keydata = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_data') . ' WHERE pvalue=:pvalue and id<>:id and typeid=:typeid and uniacid=:uniacid and merchid=:merchid', array(':pvalue' => $insert['pvalue'], ':id' => $id, ':typeid' => $typeid, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
							if (empty($keydata)) 
							{
								pdo_update('ewei_shop_virtual_data', $insert, array('id' => $datas['id']));
							}
							else 
							{
								$noinsert .= $insert['pvalue'] . ',';
							}
						}
					}
					com('virtual')->updateStock($typeid);
					mplog('goods.virtual.data.edit', '修改数据 模板ID: ' . $typeid);
					if (!(empty($noinsert))) 
					{
						$tip = '<br>未保存成功的数据：主键=' . $noinsert . '<br>失败原因：已经使用无法更改';
						show_json(1, array('message' => '部分数据保存成功！' . $tip, 'url' => merchUrl('virtual/data', array('typeid' => $typeid))));
					}
					else 
					{
						show_json(1, array('url' => merchUrl('goods/virtual/data', array('typeid' => $typeid))));
					}
				}
			}
		}
		include $this->template();
	}
	public function delete() 
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$typeid = intval($_GPC['typeid']);
		if (empty($id)) 
		{
			$id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
		}
		$types = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_virtual_data') . ' WHERE id in( ' . $id . ' ) AND typeid=' . $typeid . ' and  uniacid=' . $_W['uniacid'] . ' and  merchid=' . $_W['merchid']);
		foreach ($types as $type ) 
		{
			if (!(empty($type['openid']))) 
			{
				continue;
			}
			pdo_delete('ewei_shop_virtual_data', array('id' => $type['id']));
			pdo_update('ewei_shop_virtual_type', 'alldata=alldata-1', array('id' => $type['id']));
			com('virtual')->updateStock($type['id']);
			mplog('goods.virtual.data.delete', '删除数据 模板ID: ' . $typeid . ' 数据ID: ' . $id);
		}
		show_json(1, array('url' => merchUrl('goods/virtual/data', array('typeid' => $typeid))));
	}
	public function export() 
	{
		global $_W;
		global $_GPC;
		$typeid = intval($_GPC['typeid']);
		$type = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_type') . ' WHERE id=:id and uniacid=:uniacid and merchid=:merchid limit 1 ', array(':id' => $typeid, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
		if (empty($type)) 
		{
			$this->message('未找到虚拟物品模板!', '', 'error');
		}
		$type['fields'] = iunserializer($type['fields']);
		$fieldstr = '';
		foreach ($type['fields'] as $key => $name ) 
		{
			$fieldstr .= $name . '(' . $key . ')/';
		}
		$condition = ' and d.typeid=:typeid and d.uniacid=:uniacid and d.merchid=:merchid and d.openid<>\'\'';
		$params = array(':typeid' => $typeid, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']);
		$list = pdo_fetchall('SELECT d.*,o.carrier,m.avatar,m.nickname FROM ' . tablename('ewei_shop_virtual_data') . ' d ' . ' left join ' . tablename('ewei_shop_member') . ' m on m.openid = d.openid and m.uniacid = d.uniacid ' . ' left join ' . tablename('ewei_shop_order') . ' o on o.id = d.orderid ' . ' where  1 ' . $condition . ' order by usetime desc', $params);
		if (empty($list)) 
		{
			$this->message('没有已使用的数据!', '', 'info');
		}
		foreach ($list as &$row ) 
		{
			$datas = iunserializer($row['fields']);
			$valuestr = '';
			foreach ($type['fields'] as $k => $v ) 
			{
				$valuestr .= $datas[$k] . '/';
			}
			$row['values'] = $valuestr;
			$carrier = iunserializer($row['carrier']);
			if (is_array($carrier)) 
			{
				$row['realname'] = $carrier['carrier_realname'];
				$row['mobile'] = $carrier['carrier_mobile'];
			}
			$row['usetime'] = date('Y-m-d H:i', $row['usetime']);
		}
		unset($row);
		$columns = array( array('title' => $fieldstr, 'field' => 'values', 'width' => 24), array('title' => '粉丝昵称', 'field' => 'nickname', 'width' => 12), array('title' => '姓名', 'field' => 'realname', 'width' => 12), array('title' => '手机号', 'field' => 'mobile', 'width' => 12), array('title' => '使用时间', 'field' => 'usetime', 'width' => 12), array('title' => '订单号', 'field' => 'ordersn', 'width' => 24), array('title' => '购买价格', 'field' => 'price', 'width' => 12) );
		m('excel')->export($list, array('title' => $type['title'] . '已使用数据', 'columns' => $columns));
		exit();
	}
	public function temp() 
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_type') . ' WHERE id=:id and uniacid=:uniacid and merchid=:merchid ', array(':id' => $id, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
		if (empty($item)) 
		{
			$this->message('虚拟物品模板不存在', referer(), 'error');
		}
		$item['fields'] = iunserializer($item['fields']);
		$columns = array();
		foreach ($item['fields'] as $key => $name ) 
		{
			$columns[] = array('title' => $name . '(' . $key . ')', 'field' => '', 'width' => 24);
		}
		m('excel')->export(array(), array('title' => '数据模板', 'columns' => $columns));
	}
	public function import() 
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['typeid']);
		$item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_type') . ' WHERE id=:id and uniacid=:uniacid and merchid=:merchid ', array(':id' => $id, ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
		if (empty($item)) 
		{
			$this->message('虚拟物品模板不存在', referer(), 'error');
		}
		$rows = m('excel')->import('excelfile');
		$item['fields'] = iunserializer($item['fields']);
		foreach ($rows as $rownum => $col ) 
		{
			$data = array( 'typeid' => $id, 'pvalue' => $col[0], 'fields' => array(), 'uniacid' => $_W['uniacid'], 'merchid' => $_W['merchid'] );
			$index = 0;
			foreach ($item['fields'] as $k => $f ) 
			{
				$data['fields'][$k] = $col[$index];
				++$index;
			}
			$data['fields'] = iserializer($data['fields']);
			$datas[] = $data;
		}
		foreach ($datas as $d ) 
		{
			$olddata = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_data') . ' WHERE pvalue=:pvalue and typeid=:typeid and uniacid=:uniacid and merchid=:merchid ', array(':pvalue' => $d['pvalue'], ':typeid' => $_GPC['typeid'], ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
			if (empty($olddata)) 
			{
				pdo_insert('ewei_shop_virtual_data', $d);
				pdo_update('ewei_shop_virtual_type', 'alldata=alldata+1', array('id' => $item['id']));
			}
			else if (empty($olddata['openid'])) 
			{
				pdo_update('ewei_shop_virtual_data', $d, array('id' => $olddata['id']));
			}
			else 
			{
				$noinsert .= $d['pvalue'] . ',';
			}
			$noinsert = '';
		}
		com('virtual')->updateStock($id);
		if (!(empty($noinsert))) 
		{
			$tip = '<br>未保存成功的数据：主键=' . $noinsert . '<br>失败原因：已经使用无法更改';
			$this->message('部分数据保存成功！' . $tip, '', 'warning');
		}
		else 
		{
			$this->message('导入成功！', merchUrl('goods/virtual/data', array('typeid' => $_GPC['typeid'])));
		}
	}
	public function tpl() 
	{
		global $_W;
		global $_GPC;
		$kw = $_GPC['kw'];
		$item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_virtual_type') . ' WHERE id=:id and uniacid=:uniacid and merchid=:merchid', array(':id' => $_GPC['typeid'], ':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']));
		$item['fields'] = iunserializer($item['fields']);
		$num = $_GPC['numlist'];
		include $this->template('goods/virtual/data/tpl');
	}
}
?>