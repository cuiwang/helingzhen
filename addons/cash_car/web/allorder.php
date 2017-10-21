<?php
/**
 * 订单概览
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */

load()->func('tpl');
$weid = $_W['uniacid'];
$action = 'allorder';
$title = "订单概览";
$storeid = intval($_GPC['storeid']);

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($operation == 'display') {

	//服务点条件
	$condition = " weid = '{$_W['uniacid']}'";
	//订单检索条件
	$commoncondition = " weid = '{$_W['uniacid']}' ";
	//订单统计条件
	$commonconditioncount = " weid = '{$_W['uniacid']}' ";

	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;
		$commoncondition .= " AND dateline >= {$starttime} AND dateline <= {$endtime} ";
		$commonconditioncount .= " AND dateline >= {$starttime} AND dateline <= {$endtime} ";
	}

	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	if (!empty($_GPC['ordersn'])) {
		$commoncondition .= " AND ordersn LIKE '%{$_GPC['ordersn']}%' ";
	}

	if (!empty($_GPC['tel'])) {
		$commoncondition .= " AND tel LIKE '%{$_GPC['tel']}%' ";
	}

	if (!empty($_GPC['username'])) {
		$commoncondition .= " AND username LIKE '%{$_GPC['username']}%' ";
	}

	if (isset($_GPC['status']) && $_GPC['status'] != 0) {
		$commoncondition .= " AND status = '" . intval($_GPC['status']) . "'";
	}

	if (!empty($storeid)) {
		$commoncondition .= " AND storeid = '{$storeid}' ";
		$commonconditioncount .= " AND storeid = '{$storeid}' ";
	}

	$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE $commoncondition ORDER BY id desc, dateline DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);

	if ($_GPC['out_put'] == 'output') {
		$outputlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE $commoncondition ORDER BY id desc, dateline DESC");

		foreach($outputlist as $k1=>$v1){
			/* 订单服务项目 */
			$goods = pdo_fetchall("SELECT title FROM " .tablename($this->table_order_goods). " WHERE weid='{$weid}' AND orderid='{$v1['id']}' ");
			foreach($goods as $g){
				$goods_name .= $g['title']."，";
			}
			$outputlist[$k1]['goods_name'] = trim($goods_name, "，");
			unset($goods_name);

			/* 订单服务工作人员 */
			if(!empty($v1['worker_openid'])){
				$outputlist[$k1]['worker'] = pdo_fetch("SELECT name FROM " .tablename($this->table_worker). " WHERE weid='{$weid}' AND openid='{$v1['worker_openid']}'");
			}

			/* 服务点名称 */
			$outputlist[$k1]['store_name'] = pdo_fetch("SELECT title FROM " .tablename($this->table_store). " WHERE weid='{$weid}' AND id='{$v1['storeid']}'");
		}

		$orderstatus = array(
			'-1' => array('css' => 'default', 'name' => '已取消'),
			'0' => array('css' => 'danger', 'name' => '待付款'),
			'1' => array('css' => 'info', 'name' => '待接单'),
			'2' => array('css' => 'warning', 'name' => '已接单'),
			'3' => array('css' => 'success', 'name' => '已完成')
		);

		$i = 0;
		foreach ($outputlist as $key => $value) {
			$arr[$i]['ordersn']		= $value['ordersn'];
			$arr[$i]['username']	= $value['username'];
			$arr[$i]['tel']			= $value['tel'];
			$arr[$i]['mycard']		= $value['mycard'];
			$arr[$i]['goods_name']  = $value['goods_name'];
			$arr[$i]['paytype']		= $value['paytype']==1?'余额支付':'在线支付';
			$arr[$i]['usecard']		= $value['usecard']==0?'未使用':'使用';
			$arr[$i]['status']		= $orderstatus[$value['status']]['name'];
			$arr[$i]['store_name']  = $value['store_name']['title'];
			$arr[$i]['totalprice']  = $value['totalprice']."元";
			$arr[$i]['worker']      = $value['worker']['name'];
			$arr[$i]['meal_date']   = date('Y-m-d', $value['meal_date'])."，".$value['meal_time'];
			$arr[$i]['dateline']    = date('Y-m-d H:i:s', $value['dateline']);
			if($value['finish_time']>0){
				$arr[$i]['finish_time'] = date('Y-m-d H:i:s', $value['finish_time']);
			}else{
				$arr[$i]['finish_time'] = "未完成";
			}
			
			$i++;
		}

		$this->exportexcel($arr, array('订单编号', '姓名', '电话', '车牌号码','服务内容','支付方式','洗车卡支付', '订单状态', '服务点', '总金额','工作人员','预约时间','下单时间','完成时间'), "微洗车数据导出".date('Y-m-d',time()));
		exit();
	}

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE $commoncondition", $paras);

	$pager = pagination($total, $pindex, $psize);

	if (!empty($list)) {
		foreach ($list as $key=>$row) {
			$userids[$row['from_user']] = $row['from_user'];
			$list[$key]['store'] = pdo_fetch("SELECT title FROM " . tablename($this->table_store) . " WHERE weid={$weid} AND id='{$row['storeid']}'");
		}
	}

	$order_count_all = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} ");
	$order_count_confirm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=1");
	$order_count_pay = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=2");
	$order_count_finish = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=3");
	$order_count_cancel = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . "  WHERE {$commonconditioncount} AND status=-1");

	$users = fans_search($userids, array('realname', 'resideprovince', 'residecity', 'residedist', 'address', 'mobile', 'qq'));

	//服务点列表
	$storelist = pdo_fetchall("SELECT * FROM ".tablename($this->table_store)." WHERE weid = :weid", array(':weid' => $_W['weid']), 'id');
}

include $this->template('allorder');