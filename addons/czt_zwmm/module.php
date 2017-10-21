<?php
/**
 * 指纹蜜码模块定义
 *
 * @author 
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_zwmmModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$data = array(
				'subscribe_url'	=>	$_GPC['subscribe_url']
			);
			if($this->saveSettings($data)){
				message('保存成功', 'refresh');
			}
		}
		//这里来展示设置项表单
		load()->func('tpl');
		include $this->template('settings');
	}
}