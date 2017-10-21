<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Notice_EweiShopV2Page extends PluginWebPage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		if ($_W['ispost']) 
		{
			$data = ((is_array($_GPC['data']) ? $_GPC['data'] : array()));
			$data['openid'] = ((is_array($_GPC['openids']) ? $_GPC['openids'] : array()));
			$data['applyopenid'] = ((is_array($_GPC['applyopenids']) ? $_GPC['applyopenids'] : array()));
			m('common')->updatePluginset(array( 'merch' => array('tm' => $data) ));
			plog('merch.notice.edit', '修改通知设置');
			show_json(1);
		}
		$data = m('common')->getPluginset('merch');
		$salers = array();
		if (!(empty($data['tm']['openid']))) 
		{
			$openids = array();
			$strsopenids = $data['tm']['openid'];
			foreach ($strsopenids as $openid ) 
			{
				$openids[] = '\'' . $openid . '\'';
			}
			$salers = pdo_fetchall('select id,nickname,avatar,openid from ' . tablename('ewei_shop_member') . ' where openid in (' . implode(',', $openids) . ') and uniacid=' . $_W['uniacid']);
		}
		$applysalers = array();
		if (!(empty($data['tm']['applyopenid']))) 
		{
			$applyopenids = array();
			$strsopenids = $data['tm']['applyopenid'];
			foreach ($strsopenids as $openid ) 
			{
				$applyopenids[] = '\'' . $openid . '\'';
			}
			$applysalers = pdo_fetchall('select id,nickname,avatar,openid from ' . tablename('ewei_shop_member') . ' where openid in (' . implode(',', $applyopenids) . ') and uniacid=' . $_W['uniacid']);
		}
		$template_list = pdo_fetchall('SELECT id,title FROM ' . tablename('ewei_shop_member_message_template') . ' WHERE uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));
		$opensms = com('sms');
		$template_sms = array();
		if ($opensms) 
		{
			$template_sms = $opensms->sms_temp();
		}
		include $this->template();
	}
}
?>