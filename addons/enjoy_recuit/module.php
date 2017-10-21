<?php


defined('IN_IA') || exit('Access Denied');
class Enjoy_recuitModule extends WeModule
{
	public function settingsDisplay($settings)
	{
		global $_W;
		global $_GPC;

		if (checksubmit()) {
			$dat = array('appid' => trim($_GPC['appid']), 'appsecret' => trim($_GPC['appsecret']));

			if ($this->saveSettings($dat)) {
				message('保存成功', 'refresh');
			}

		}


		include $this->template('setting');
	}
}

