<?php
/** * 游戏联盟模块微站定义 * * @author Yoby&&huce * @url  */
defined('IN_IA') or exit('Access Denied');

class Hc_gameuuModule extends WeModule {

	public function settingsDisplay($settings) {
				global $_GPC, $_W;				load()->func('tpl');
  					if(!isset($settings['gamen'])) {

				$settings['gamen'] = '3';

			}
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
				$dat = array(
			'gamen' => $_GPC['gamen'],
			'gamew' => $_GPC['gamew'],
			);
			$this->saveSettings($dat);
			message('保存成功', 'refresh');
		}
		include $this->template('setting');
	}

}