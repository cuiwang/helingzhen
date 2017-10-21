<?php
/**
 * 消消乐模块微站定义
 *
 * @author 猫
 * @url http://www.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Cat_xxlModuleSite extends WeModuleSite {

	public function doMobileGame() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		include $this->template('game');
	}
	public function doWebRule() {
		//这个操作被定义用来呈现 规则列表
	}

}