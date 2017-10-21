<?php
/**
 * 啪啪啪模块微站定义
 *
 * @author On3
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Demo_pia3ModuleSite extends WeModuleSite {

	public function doMobileEntry() {
		global $_W,$_GPC;
		$dat =$this->module['config'];
		if(empty($dat)){
			message('请先去后台设置游戏基本信息..');
		}
		include $this->template('index');
	}

}