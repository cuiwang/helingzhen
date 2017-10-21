<?php
/**
 * 工作人员列表
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
 
if ($_GPC['delid']*1 > 0 ) {
	pdo_delete($this->table_worker,array('id'=>$_GPC['delid']));
	message('删除成功！', $this->createWebUrl('menlist', array()), 'success');
}

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$store_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_store). " WHERE weid='{$_W['uniacid']}'");

$condition = " weid='{$_W[uniacid]}'";
if(!empty($_GPC['storeid'])){
	$condition .= " AND storeid='{$_GPC['storeid']}'";
}
if(!empty($_GPC['worker'])){
	$condition .= " AND name LIKE '%{$_GPC[worker]}%'";
}
if(!empty($_GPC['car'])){
	$condition .= " AND car_num LIKE '%{$_GPC[car]}%'";
}
if(!empty($_GPC['mobile'])){
	$condition .= " AND mobile LIKE '%{$_GPC[mobile]}%'";
}
if(!empty($_GPC['is_own'])){
	$condition .= " AND type = 1";
}
		
$js = pdo_fetchall("SELECT * FROM " . tablename($this->table_worker) . " WHERE {$condition} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM" . tablename($this->table_worker) . " WHERE {$condition}");
$pager = pagination($total, $pindex, $psize);

include $this->template('menlist');