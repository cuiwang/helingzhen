<?php
/**
*/
defined('IN_IA') or exit('Access Denied');

class Teaxia_christmasModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC,$_W; 
		include $this->template('index');
	}

}