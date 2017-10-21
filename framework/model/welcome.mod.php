<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

function welcome_notices_get() {
	$notices = pdo_getall('article_notice', array('is_display' => 1), array('id', 'title', 'createtime'), '', 'createtime DESC', array(1,15));
	if(!empty($notices)) {
		foreach ($notices as $key => $notice_val) {
			$notices[$key]['url'] = url('article/notice-show/detail', array('id' => $notice_val['id']));
			$notices[$key]['createtime'] = date('Y-m-d', $notice_val['createtime']);
		}
	}
	return $notices;
}

function welcome_database_backup_days($time) {
	global $_W;
	$cachekey = cache_system_key("back_days:");
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	$backup_days = 0;
	if (is_array($time)) {
		$max_backup_time = $time[0];
		foreach ($time as $key => $backup_time) {
			if ($backup_time <= $max_backup_time) {
				continue;
			}
			$max_backup_time = $backup_time;
		}
		$backup_days = ceil((time() - $max_backup_time) / (3600 * 24));
	}
	if (is_numeric($time)) {
		$backup_days = ceil((time() - $time) / (3600 * 24));
	}
	cache_write($cachekey, $backup_days, 24 * 3600);
	return $backup_days;
}

function welcome_get_cloud_upgrade() {
	cache_load('upgrade');
	if (!empty($_W['cache']['upgrade'])) {
		$upgrade_cache = $_W['cache']['upgrade'];
	}
	if (empty($upgrade_cache) || TIMESTAMP - $upgrade_cache['lastupdate'] >= 3600 * 24 || empty($upgrade_cache['data'])) {
		$upgrade = cloud_build();
	} else {
		$upgrade = $upgrade_cache['data'];
	}
	cache_delete('cloud:transtoken');
	if (is_error($upgrade) || empty($upgrade['upgrade'])) {
		$upgrade = array();
	}
	if (!empty($upgrade['schemas'])) {
		$upgrade['database'] = cloud_build_schemas($schems);
	}
	$file_nums = count($upgrade['files']);
	$database_nums = count($upgrade['database']);
	$script_nums = count($upgrade['scripts']);
	$upgrade['file_nums'] = $file_nums;
	$upgrade['database_nums'] = $database_nums;
	$upgrade['script_nums'] = $script_nums;
	return $upgrade;
}
