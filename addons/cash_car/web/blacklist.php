<?php
/**
 * 黑名单管理
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */

load()->model('mc');
$weid = $_W['uniacid'];

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$list = pdo_fetchall("SELECT c.id as id,a.nickname as nickname,a.realname as realname,a.mobile as mobile,c.dateline as dateline,c.from_user as from_user,c.status as status FROM " . tablename('mc_members') . " a INNER JOIN " . tablename('mc_mapping_fans') . " b ON a.uid=b.uid INNER JOIN " . tablename($this->table_blacklist) . " c ON b.openid=c.from_user WHERE c.weid=:weid ORDER BY c.dateline DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $_W['uniacid']));

	if (!empty($list)) {
		$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_blacklist) . "  WHERE weid=:weid", array(':weid' => $_W['uniacid']));
	}

	$pager = pagination($total, $pindex, $psize);
} else if ($operation == 'black') {
	$id = $_GPC['id'];

	pdo_delete($this->table_blacklist, array('id' => $id, 'weid' => $weid));

	message('操作成功！', $this->createWebUrl('blacklist', array('op' => 'display')), 'success');
}

include $this->template('blacklist');