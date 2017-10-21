<?php
/**
 * Coreball模块定义
 */
defined('IN_IA') or exit('Access Denied');

class Lee_coreballModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$dat = $_GPC['dat'];
			if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
		}
		
		// 模板中需要用到 "tpl" 表单控件函数的话, 记得一定要调用此方法.
		load()->func('tpl');
		
		//这里来展示设置项表单
		include $this->template('setting');
	}

}