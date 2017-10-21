<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');


function cache_read($key) {
	$cachedata = pdo_getcolumn('core_cache', array('key' => $key), 'value');
	if (empty($cachedata)) {
		return '';
	}
	$cachedata = iunserializer($cachedata);
	if (is_array($cachedata) && !empty($cachedata['expire']) && !empty($cachedata['data'])) {
		if ($cachedata['expire'] > TIMESTAMP) {
			return $cachedata['data'];
		} else {
			return '';
		}
	} else {
		return $cachedata;
	}
}


function cache_search($prefix) {
	$sql = 'SELECT * FROM ' . tablename('core_cache') . ' WHERE `key` LIKE :key';
	$params = array();
	$params[':key'] = "{$prefix}%";
	$rs = pdo_fetchall($sql, $params);
	$result = array();
	foreach ((array)$rs as $v) {
		$result[$v['key']] = iunserializer($v['value']);
	}
	return $result;
}


function cache_write($key, $data, $expire = 0) {
	if (empty($key) || !isset($data)) {
		return false;
	}
	$record = array();
	$record['key'] = $key;
	if (!empty($expire)) {
		$cache_data = array(
			'expire' => TIMESTAMP + $expire,
			'data' => $data
		);
	} else {
		$cache_data = $data;
	}
	$record['value'] = iserializer($cache_data);
	return pdo_insert('core_cache', $record, true);
}


function cache_delete($key) {
	$sql = 'DELETE FROM ' . tablename('core_cache') . ' WHERE `key`=:key';
	$params = array();
	$params[':key'] = $key;
	$result = pdo_query($sql, $params);
	return $result;
}


function cache_clean($prefix = '') {
	global $_W;
	if (empty($prefix)) {
		$sql = 'DELETE FROM ' . tablename('core_cache');
		$result = pdo_query($sql);
		if ($result) {
			unset($_W['cache']);
		}
	} else {
		$sql = 'DELETE FROM ' . tablename('core_cache') . ' WHERE `key` LIKE :key';
		$params = array();
		$params[':key'] = "{$prefix}:%";
		$result = pdo_query($sql, $params);
	}
	return $result;
}
