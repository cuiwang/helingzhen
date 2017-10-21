<?php
/**
 * 新增/编辑工作人员
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
 
$weid = $_W['uniacid'];

$storeslist = pdo_fetchall("SELECT id,title FROM " . tablename($this->table_store) . " where weid=:weid ORDER BY displayorder DESC,id DESC", array(':weid' => $_W['uniacid']));

if (checksubmit()) {
	$store = pdo_fetch("SELECT * FROM " . tablename($this->table_store) . " where id=:storeid ", array(':storeid' => $_GPC['storeid']));
	$user = pdo_fetch("SELECT a.openid, a.nickname FROM " .tablename('mc_mapping_fans'). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE b.uniacid='{$weid}' AND b.mobile='{$_GPC['mobile']}'");
	$data = array(
		'weid'       => $weid,
		'name'       => trim($_GPC['name']),
		'mobile'     => trim($_GPC['mobile']),
		'openid'     => trim($_GPC['openid'])?trim($_GPC['openid']):$user['openid'],
		'wx_name'    => trim($_GPC['wx_name'])?trim($_GPC['wx_name']):$user['nickname'],
		'car_num'    => $_GPC['car_num'],
		'storeid'    => $_GPC['storeid'],
		'store_name' => $store['title'],
		'sort'       => $_GPC['sort'],
		'isshow'     => $_GPC['isshow'],
		'type'       => 1, 
		'detail'     => $_GPC['detail']
	);

	//检查工作人员是否已存在
	$checkMem = pdo_fetch("SELECT * FROM " . tablename($this->table_worker) . " WHERE weid='{$weid}' AND mobile='{$data['mobile']}' AND storeid='{$_GPC['storeid']}'");
	if($checkMem && $_GPC['id']!=$checkMem['id']){
		message('该工作人员已在该服务点内', '', 'error');
	}

	if(!empty($_GPC['id'])){
		pdo_update($this->table_worker, $data,array('id' => $_GPC['id']));
	}else
		pdo_insert($this->table_worker, $data);
		message('操作成功！', $this->createWebUrl('menlist', array()), 'success');
}

if ($_GPC['id']*1 > 0 ) {
	$js = pdo_fetch("SELECT * FROM " . tablename($this->table_worker) . " WHERE id=:id and weid=:weid", array(':id'=>$_GPC['id'],':weid' => $_W['uniacid']));
}

include $this->template('newmen');