<?php

defined('IN_IA') or exit('Access Denied');

if(!defined('CORE_PATH')){
	trigger_error('Invalid CORE_PATH ');
}

function M($name){
	return mload()->con($name);
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
	function con($name){
		global $_W;
		if (isset($this->cache['con'][$name])) {
			return true;
		}
		$file = CORE_PATH . 'controller/' . $name . '.php';
		if (file_exists($file)) {
			mload()->classs('core');
			include $file;
			$this->cache['controller'][$name] = true;
			return new $name();
		} else {
			mload()->classs('core');
			return new core();
			return false;
		}
	}
	function func($name) {
		global $_W;
		if (isset($this->cache['func'][$name])) {
			return true;
		}
		$file = CORE_PATH . 'function/' . $name . '.func.php';
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
		$file = CORE_PATH . 'function/' . $name . '.plus.php';
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
		$file = CORE_PATH . 'model/' . $name . '.mod.php';
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
		$file = CORE_PATH . 'class/' . $name . '.class.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['class'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Class ' . $file . '.class.php', E_USER_ERROR);
			return false;
		}
	}

}
