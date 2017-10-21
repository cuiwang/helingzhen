<?php
/**
 * 切粽子模块微站定义
 *
 * @author 
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_qzzModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_W, $_GPC;
		$settings=$this->module['config'];
		include $this->template('index');
	}

}