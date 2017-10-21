<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

function cache_memcache() {
	global $_W;
	static $memcacheobj;
	if (!extension_loaded('memcache')) {
		return error(1, 'Class Memcache is not found');
	}
	if (empty($memcacheobj)) {
		$config = $_W['config']['setting']['memcache'];
		$memcacheobj = new Memcache();
		if($config['pconnect']) {
			$connect = $memcacheobj->pconnect($config['server'], $config['port']);
		} else {
			$connect = $memcacheobj->connect($config['server'], $config['port']);
		}
		if(!$connect) {
			return error(-1, 'Memcache is not in work');
		}
	}
	return $memcacheobj;
}


function cache_read($key, $forcecache = true) {
	$key = cache_namespace($key);
	
	$memcache = cache_memcache();
	if (is_error($memcache)) {
		return $memcache;
	}
	$result = $memcache->get(cache_prefix($key));
	if (empty($result) && empty($forcecache)) {
		$dbcache = pdo_get('core_cache', array('key' => $key), array('value'));
		if (!empty($dbcache['value'])) {
			$result = iunserializer($dbcache['value']);
			$memcache->set(cache_prefix($key), $result);
		}
	}
	return $result;
}



function cache_search($key) {
	return cache_read(cache_prefix($key));
}


function cache_write($key, $value, $ttl = 0, $forcecache = true) {
	$key = cache_namespace($key);
	
	$memcache = cache_memcache();
	if (is_error($memcache)) {
		return $memcache;
	}
	if (empty($forcecache)) {
		$record = array();
		$record['key'] = $key;
		$record['value'] = iserializer($value);
		pdo_insert('core_cache', $record, true);
	}
	if ($memcache->set(cache_prefix($key), $value, MEMCACHE_COMPRESSED, $ttl)) {
		return true;
	} else {
		return false;
	}
}


function cache_delete($key, $forcecache = true) {
	$origins_cache_key = $key;
	$key = cache_namespace($key);
	
	$memcache = cache_memcache();
	if (is_error($memcache)) {
		return $memcache;
	}
	
	if (empty($forcecache)) {
		pdo_delete('core_cache', array('key' => $key));
	}
	
	if ($memcache->delete(cache_prefix($key))) {
		unset($GLOBALS['_W']['cache'][$origins_cache_key]);
		return true;
	} else {
		unset($GLOBALS['_W']['cache'][$origins_cache_key]);
		return false;
	}
}



function cache_clean($prefix = '') {
	if (!empty($prefix)) {
		$cache_namespace = cache_namespace($prefix, true);
		unset($GLOBALS['_W']['cache']);
		pdo_delete('core_cache', array('key LIKE' => $cache_namespace . '%'));
		return true;
	}
	$memcache = cache_memcache();
	if (is_error($memcache)) {
		return $memcache;
	}
	if ($memcache->flush()) {
		unset($GLOBALS['_W']['cache']);
		pdo_delete('core_cache');
		return true;
	} else {
		return false;
	}
}


function cache_namespace($key, $forcenew = false) {
	if (!strexists($key, ':')) {
		$namespace_cache_key = $key;
	} else {
		list($key1, $key2) = explode(':', $key);
		if ($key1 == 'we7') {
			$namespace_cache_key = $key2;
		} else {
			$namespace_cache_key = $key1;
		}
	}
	if (!in_array($namespace_cache_key, array('unimodules', 'user_modules'))) {
		return $key;
	}
	
		$namespace_cache_key = 'cachensl:' . $namespace_cache_key;
	$memcache = cache_memcache();
	if (is_error($memcache)) {
		return $memcache;
	}
	$namespace = $memcache->get(cache_prefix($namespace_cache_key));
	if (empty($namespace) || $forcenew) {
		$namespace = random(5);
		$memcache->set(cache_prefix($namespace_cache_key), $namespace, MEMCACHE_COMPRESSED, 0);
	}
	return $namespace . ':' . $key;
}

function cache_prefix($key) {
	return $GLOBALS['_W']['config']['setting']['authkey'] . $key;
}