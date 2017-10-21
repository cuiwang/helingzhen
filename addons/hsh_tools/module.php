<?php
/**
 * 工具箱模块定义
 *
 * @author hsh
 * @url http://www.hshcs.com
 */
defined('IN_IA') or exit('Access Denied');

class Hsh_toolsModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$dat['appid'] = $_GPC['appid'];
			$dat['secret'] = $_GPC['secret'];
			$dat['smsAccountSid'] = $_GPC['smsAccountSid'];
			$dat['smsAuthToken'] = $_GPC['smsAuthToken'];
			$dat['smsAppId'] = $_GPC['smsAppId'];
			$dat['specialQrCodeCount'] = $_GPC['specialQrCodeCount'];
			if($this->saveSettings($dat)){
				message('配置参数更新成功！', referer(), 'success');
			} else {
				message('配置参数未更新！');
			}
		}
		load()->func('tpl');
		//这里来展示设置项表单
		include $this->template('settings');
	}

}