<?php
/**
 * 啪啪啪模块定义
 *
 * @author On3
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Demo_pia3Module extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');
		$config = $this->module['config'];
		if(empty($config['hlogo'])){
			$config['hlogo'] = MODULE_URL.'template/style/img/logo.png';
		}
		if(empty($config['flogo'])){
			$config['flogo'] = MODULE_URL.'template/style/img/un-logo.png';
		}
		if(empty($config['target'])){
			$config['target'] = MODULE_URL.'template/style/img/husky.png';
		}
		if(checksubmit()) {
			$dat = $_GPC['dat'];
			$dat['hlogo'] = $_GPC['hlogo'];
			$dat['flogo'] = $_GPC['flogo'];
			$dat['target'] = $_GPC['target'];
			$this->saveSettings($dat);
			message('设置成功..',referer(),'success');
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}