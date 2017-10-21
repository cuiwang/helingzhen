<?php
/**
 * 运营详情
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
$weid = $this->_weid;
$allstore = pdo_fetchall("SELECT * FROM " .tablename($this->table_store). " WHERE weid='{$weid}'");

$starttime = $_GPC['time']['start']?strtotime($_GPC['time']['start']):time();
$endtime   = $_GPC['time']['end']?strtotime($_GPC['time']['end']):time();

$storeid = intval($_GPC['storeid']);
$start   = $_GPC['time']['start']?trim(strtotime($_GPC['time']['start'])):strtotime(date('Y-m-d'));
$end     = $_GPC['time']['end']?trim(strtotime($_GPC['time']['end']))+86399:strtotime(date('Y-m-d'))+86399;

$pindex = max(1, intval($_GPC['page']));
$psize = 20;

$condition = " weid='{$weid}'";
if(!empty($storeid)){
	$condition .= " AND id='{$storeid}'";
}

$storelist = pdo_fetchall("SELECT * FROM " .tablename($this->table_store). " WHERE {$condition} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_store) . " WHERE {$condition}");

$pager = pagination($total, $pindex, $psize);

$alltotal = 0;
foreach($storelist as $key=>$value){
	$orderlist = pdo_fetchall("SELECT * FROM " .tablename($this->table_order). " WHERE weid='{$weid}' AND storeid='{$value[id]}' AND status=3 AND dateline>='{$start}' AND dateline<'{$end}'");
	
	$total = 0;
	foreach($orderlist as $val){
		$ordergoods = pdo_fetchall("SELECT * FROM " .tablename($this->table_order_goods). " WHERE orderid='{$val[id]}'");
		foreach($ordergoods as $v){
			$total += $v['price'];
		}	
	}
	$storelist[$key]['orderamount'] = $total;
	$storelist[$key]['ordercount'] = count($orderlist);
	$alltotal += $total;
}


include $this->template('business');