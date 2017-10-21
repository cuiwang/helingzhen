<?php
 
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Setting_EweiShopV2Page extends PluginWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$data = m('common')->getSysset('app');
		$template_sms = com_run('sms::sms_temp');
		if (empty($template_sms) || !is_array($template_sms)) {
			$template_sms = array();
		}

		if (empty($data) || !is_array($data)) {
			$data = array();
		}

		if ($_W['ispost']) {
			ca('app.setting.edit');
			$data['appid'] = trim($_GPC['data']['appid']);
			$data['secret'] = trim($_GPC['data']['secret']);
			$data['isclose'] = intval($_GPC['data']['isclose']);
			$data['closetext'] = trim($_GPC['data']['closetext']);
			$data['openbind'] = intval($_GPC['data']['openbind']);
			$data['sms_bind'] = intval($_GPC['data']['sms_bind']);
			$data['bindtext'] = trim($_GPC['data']['bindtext']);
			$data['hidecom'] = intval($_GPC['data']['hidecom']);
			$data['sendappurl'] = trim($_GPC['data']['sendappurl']);
			m('common')->updateSysset(array('app' => $data));
			plog('app.setting.edit', '保存基本设置');
			show_json(1);
		}

		include $this->template();
	}
}

?>
