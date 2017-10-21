<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/dy_yy/defines.php';
require_once D_INC . 'functions.php';
require_once D_INC . 'core.php';
class Dy_yyModuleSite extends Core {
	public function doMobileIndex() {
		$this -> _exec(__FUNCTION__, 'index', false);
	} 
	public function doWebWeb() {
		$this -> _exec(__FUNCTION__, 'web');
	} 
} 
