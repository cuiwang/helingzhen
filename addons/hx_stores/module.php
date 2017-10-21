<?php
/**
 * 门店导航模块定义
 *
 * @author 华轩科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_storesModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_GPC, $_W;
		if(checksubmit()) {
			$cfg = array(
				'ak' => $_GPC['ak'],
				'range' => intval($_GPC['range']),	
			);
			if($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		if(!isset($settings['range'])) {
			$settings['range'] = '5';
		}
		include $this->template('settings');
	}

}