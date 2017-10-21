<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require EWEI_SHOPV2_PLUGIN . 'cashier/core/inc/page_cashier.php';
class Order_EweiShopV2Page extends CashierWebPage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = 'AND cashierid=:cashierid';
		$params = array(':uniacid' => $_W['uniacid'], ':cashierid' => $_W['cashierid']);
		if (isset($_GPC['status']) && ($_GPC['status'] !== '')) 
		{
			$condition .= ' AND status=:status';
			$params[':status'] = intval($_GPC['status']);
		}
		if (isset($_GPC['operatorid']) && ($_GPC['operatorid'] !== '')) 
		{
			$condition .= ' AND operatorid=:operatorid';
			$params[':operatorid'] = intval($_GPC['operatorid']);
		}
		if (isset($_GPC['paytype']) && ($_GPC['paytype'] !== '')) 
		{
			$condition .= ' AND paytype=:paytype';
			$params[':paytype'] = intval($_GPC['paytype']);
		}
		if (!(empty($_GPC['time']['start'])) && !(empty($_GPC['time']['end']))) 
		{
			$start = strtotime($_GPC['time']['start']);
			$end = strtotime($_GPC['time']['end']);
			$condition .= ' AND createtime BETWEEN :start AND :end';
			$params[':start'] = intval($start);
			$params[':end'] = intval($end);
		}
		$goods = pdo_fetchall('SELECT id,orderid,isgoods FROM ' . tablename('ewei_shop_cashier_pay_log') . ' WHERE uniacid=:uniacid AND cashierid=:cashierid AND status=0 AND createtime < ' . (time() - (3600 * 12)), array(':uniacid' => $_W['uniacid'], ':cashierid' => $_W['cashierid']), 'id');
		$gids = array();
		$selfgids = array();
		foreach ($goods as $v ) 
		{
			if (!(empty($v['orderid']))) 
			{
				$gids[] = $v['orderid'];
			}
			if (!(empty($v['isgoods']))) 
			{
				$selfgids[] = $v['id'];
			}
		}
		if (!(empty($selfgids))) 
		{
			pdo_query('DELETE FROM ' . tablename('ewei_shop_cashier_pay_log_goods') . ' WHERE logid IN (' . implode(',', $selfgids) . ')');
		}
		if (!(empty($gids))) 
		{
			pdo_query('DELETE FROM ' . tablename('ewei_shop_order') . ' WHERE id IN (' . implode(',', $gids) . ')');
			pdo_query('DELETE FROM ' . tablename('ewei_shop_order_goods') . ' WHERE orderid IN (' . implode(',', $gids) . ')');
		}
		if (!(empty($goods))) 
		{
			pdo_query('DELETE FROM ' . tablename('ewei_shop_cashier_pay_log') . ' WHERE id IN (' . implode(',', array_keys($goods)) . ')');
		}
		$sql = 'select * from ' . tablename('ewei_shop_cashier_pay_log') . ' where uniacid=:uniacid ' . $condition . ' ORDER BY id desc limit ' . (($pindex - 1) * $psize) . ',' . $psize;
		$list = pdo_fetchall($sql, $params);
		$operator = pdo_fetchall('SELECT id,title FROM ' . tablename('ewei_shop_cashier_operator') . ' WHERE uniacid=:uniacid AND cashierid=:cashierid ORDER BY id DESC', array(':uniacid' => $_W['uniacid'], ':cashierid' => $_W['cashierid']), 'id');
		$total = (int) pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_cashier_pay_log') . ' where uniacid=:uniacid ' . $condition, $params);
		$total_money = (double) pdo_fetchcolumn('select sum(money+deduction) from ' . tablename('ewei_shop_cashier_pay_log') . ' where uniacid=:uniacid ' . $condition, $params);
		$pager = pagination($total, $pindex, $psize);
		include $this->template();
	}
}
?>