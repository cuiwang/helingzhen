<?php
/**
 * 用户订单列表
 * ============================================================================
 * 版权所有 2015-2016 米粒源码，并保留所有权利。
 * 网站地址: http://www.webmili.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！已购买用户允许对程序代码进行修改和使用，但是不允许对
 * 程序代码以任何形式任何目的的再发布，作者将保留追究法律责任的权力和最终解
 * 释权。
 * ============================================================================
 */
  
$weid = $this->_weid;
$from_user = $this->_fromuser;

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('orderlist', array('op'=>$_GPC['op'],'status'=>$_GPC['status']), true);
if (isset($_COOKIE[$this->_auth2_openid])) {
	$from_user = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
		if (!empty($userinfo)) {
			$from_user = $userinfo["openid"];
			$nickname = $userinfo["nickname"];
			$headimgurl = $userinfo["headimgurl"];
		} else {
			message('授权失败!');
		}
	} else {
		if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
	}
}
if (empty($from_user)) {
	message('会话已过期，请重新发送关键字!');
}

if($op == 'display'){

	//微信配置信息
	$signPackage = $_W['account']['jssdkconfig'];

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$worker = pdo_fetchall("SELECT * FROM ".tablename($this->table_worker) . " WHERE storeid='{$order[storeid]}'");

	//未付款
	if($_GPC['status'] == 'nopay'){
		$title = '未付款订单';

		$orderlist = pdo_fetchall("SELECT a.*,b.title as store_name FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_store) . " AS b ON a.storeid=b.id  WHERE a.status=0 AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		//数量
		$orderlist_total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE weid='{$weid}' AND status=0 AND from_user='{$from_user}'");
		foreach ($orderlist as $key => $value) {
			$orderlist[$key]['goods'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE weid = '{$weid}' AND orderid={$value['id']}");
		}
	}elseif($_GPC['status'] == 'alreadypay'){
		$title = '已付款订单';

		$orderlist = pdo_fetchall("SELECT a.*,b.title as store_name,b.lat as slat,b.lng as slng FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_store) . " AS b ON a.storeid=b.id  WHERE (a.status=1 OR a.status=2) AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT 20");
		//数量
		$orderlist_total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE weid='{$weid}' AND (status=1 OR status=2) AND from_user='{$from_user}'");
		foreach ($orderlist as $key => $value) {
			$orderlist[$key]['goods'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE weid = '{$weid}' AND orderid={$value['id']}");
			if(!empty($value['worker_openid'])){				
				$orderlist[$key]['worker'] = pdo_fetch("SELECT name AS worker_name,mobile AS worker_mobile FROM " .tablename($this->table_worker). " WHERE weid='{$weid}' AND openid='{$value['worker_openid']}' AND storeid='{$value['storeid']}'");
			}
		}
		
	}elseif($_GPC['status'] == 'complete'){
		$title = '已洗车订单';

		$orderlist = pdo_fetchall("SELECT a.*,b.title as store_name FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_store) . " AS b ON a.storeid=b.id  WHERE (a.status=3) AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		//数量
		$orderlist_total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE weid='{$weid}' AND (status=3) AND from_user='{$from_user}'");
		foreach ($orderlist as $key => $value) {
			$orderlist[$key]['goods'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE weid = '{$weid}' AND orderid={$value['id']}");
			if(!empty($value['worker_openid'])){
				$orderlist[$key]['worker'] = pdo_fetch("SELECT name AS worker_name,mobile AS worker_mobile FROM " .tablename($this->table_worker). " WHERE weid='{$weid}' AND openid='{$value['worker_openid']}' AND storeid='{$value['storeid']}'");
				if(!empty($value['images'])){
					$orderlist[$key]['cashimages'] = explode(",", $value['images']);
				}
			}
		}
	}elseif($_GPC['status'] == 'noevaluate'){
		$title = '待评价订单';

		$orderlist = pdo_fetchall("SELECT a.*,b.title as store_name FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_store) . " AS b ON a.storeid=b.id  WHERE (a.status=3) AND a.from_user='{$from_user}' AND a.is_evaluate=0 ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		//数量
		$orderlist_total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE weid='{$weid}' AND (status=3) AND from_user='{$from_user}' AND is_evaluate=0");
		foreach ($orderlist as $key => $value) {
			$orderlist[$key]['goods'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE weid = '{$weid}' AND orderid={$value['id']}");
			if(!empty($value['worker_openid'])){
				$orderlist[$key]['worker'] = pdo_fetch("SELECT name AS worker_name,mobile AS worker_mobile FROM " .tablename($this->table_worker). " WHERE weid='{$weid}' AND openid='{$value['worker_openid']}' AND storeid='{$value['storeid']}'");
				if(!empty($value['images'])){
					$orderlist[$key]['cashimages'] = explode(",", $value['images']);
				}
			}
		}
	}else{
		$title = '全部订单';
		$orderlist = pdo_fetchall("SELECT a.*,b.title as store_name FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_store) . " AS b ON a.storeid=b.id  WHERE a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		//数量
		$orderlist_total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE weid='{$weid}' AND from_user='{$from_user}'");
		foreach ($orderlist as $key => $value) {
			$orderlist[$key]['goods'] = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE weid = '{$weid}' AND orderid={$value['id']}");
			if(!empty($value['worker_openid'])){
				$orderlist[$key]['worker'] = pdo_fetch("SELECT name AS worker_name,mobile AS worker_mobile FROM " .tablename($this->table_worker). " WHERE weid='{$weid}' AND openid='{$value['worker_openid']}' AND storeid='{$value['storeid']}'");
				if(!empty($value['images'])){
					$orderlist[$key]['cashimages'] = explode(",", $value['images']);
				}
			}
		}
	}

	$pager = $this->mpagination($orderlist_total, $pindex, $psize);

	include $this->template('orderlist');
}


