<?php

defined('IN_IA') or exit('Access Denied');

if(!defined('INC_PATH')){
	define('INC_PATH', IA_ROOT.'/addons/meepo_logistics/inc/');
}
function mload() {
	static $mloader;
	if(empty($mloader)) {
		$mloader = new Mloader();
	}
	return $mloader;
}

class Mloader {

	private $cache = array();

	function func($name) {
		global $_W;
		if (isset($this->cache['func'][$name])) {
			return true;
		}
		$file = INC_PATH . 'core/function/' . $name . '.func.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['func'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Helper Function ' . $file, E_USER_ERROR);
			return false;
		}
	}
	
	function plus($name){
		global $_W;
		if (isset($this->cache['plus'][$name])) {
			return true;
		}
		$file = INC_PATH . 'core/function/' . $name . '.plus.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['plus'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid PLUS Function ' . $name . '.plus.php', E_USER_ERROR);
			return false;
		}
	}

	function model($name) {
		global $_W;
		if (isset($this->cache['model'][$name])) {
			return true;
		}
		$file = INC_PATH . 'core/model/' . $name . '.mod.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['model'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Model ' . $name . '.mod.php', E_USER_ERROR);
			return false;
		}
	}

	function classs($name) {
		global $_W;
		if (isset($this->cache['class'][$name])) {
			return true;
		}
		$file = INC_PATH . 'core/class/' . $name . '.class.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['class'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Class ' . $name . '.class.php', E_USER_ERROR);
			return false;
		}
	}

	function web($name) {
		global $_W;
		if (isset($this->cache['web'][$name])) {
			return true;
		}
		$file = INC_PATH . 'web/common/' . $name . '.func.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['web'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Web Helper ' . $name . '.func.php', E_USER_ERROR);
			return false;
		}
	}

	function app($name) {
		global $_W;
		if (isset($this->cache['app'][$name])) {
			return true;
		}
		$file = INC_PATH . 'mobile/common/' . $name . '.func.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['app'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid App Function ' . $name . '.func.php', E_USER_ERROR);
			return false;
		}
	}

}