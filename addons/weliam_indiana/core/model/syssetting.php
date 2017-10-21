<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
require_once IA_ROOT. '/addons/weliam_indiana/core/inc/pdo.func.php';

class Welian_Indiana_Syssetting {
	function syssetting_load($key = '') {
		global $_W;
		cache_load('syssetting');
		if (empty($_W['syssetting'])) {
			$settings = pdo_fetch_many('weliam_indiana_syssetting', array(), array('key', 'value'), 'key');
			if (is_array($settings)) {
				foreach ($settings as $k => &$v) {
					$settings[$k] = iunserializer($v['value']);
				}
			} else {
				$settings = array();
			}
			$_W['syssetting'] = $settings;
			cache_write('syssetting', $settings);
		}
		return $_W['syssetting'];
	}
	
	function syssetting_save($data, $key) {
		global $_W;
		if (empty($key)) {
			return FALSE;
		}
		
		$record = array();
		$record['value'] = iserializer($data);
		$exists = pdo_select_count('weliam_indiana_syssetting', array('key'=>$key));
		if ($exists) {
			$return = pdo_update('weliam_indiana_syssetting', $record, array('key' => $key));
		} else {
			$record['key'] = $key;
			$return = pdo_insert('weliam_indiana_syssetting', $record);
		}
		cache_write('syssetting', '');
		
		return $return;
	}
	
	function syssetting_read($key){
		global $_W;
		$settings = pdo_fetch_one('weliam_indiana_syssetting', array('key'=>$key), array('value'));
		if (is_array($settings)) {
			$settings = iunserializer($settings['value']);
		} else {
			$settings = '';
		}
		return $settings;
	}
	
} 
