<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Index_EweiShopV2Page extends PluginWebPage
{
	public function main()
	{
		global $_W;

		if (cv('commission.agent')) {
			header('location: ' . webUrl('commission/agent'));
			exit();
		}
		else if (cv('commission.apply.view1')) {
			header('location: ' . webUrl('commission/apply', array('status' => 1)));
			exit();
		}
		else if (cv('commission.apply.view2')) {
			header('location: ' . webUrl('commission/apply', array('status' => 2)));
			exit();
		}
		else if (cv('commission.apply.view3')) {
			header('location: ' . webUrl('commission/apply', array('status' => 3)));
			exit();
		}
		else if (cv('commission.apply.view_1')) {
			header('location: ' . webUrl('commission/apply', array('status' => -1)));
			exit();
		}
		else if (cv('commission.increase')) {
			header('location: ' . webUrl('commission/increase'));
			exit();
		}
		else if (cv('commission.notice')) {
			header('location: ' . webUrl('commission/notice'));
			exit();
		}
		else if (cv('commission.cover')) {
			header('location: ' . webUrl('commission/cover'));
			exit();
		}
		else if (cv('commission.level')) {
			header('location: ' . webUrl('commission/level'));
			exit();
		}
		else {
			if (cv('commission.set')) {
				header('location: ' . webUrl('commission/set'));
				exit();
			}
		}
	}

	public function notice()
	{
		global $_W;
		global $_GPC;

		if ($_W['ispost']) {
			$data = (is_array($_GPC['data']) ? $_GPC['data'] : array());
			m('common')->updatePluginset(array(
	'commission' => array('tm' => $data)
	));
			plog('commission.notice.edit', '修改通知设置');
			show_json(1);
		}

		$data = m('common')->getPluginset('commission');
		$template_list = pdo_fetchall('SELECT id,title FROM ' . tablename('ewei_shop_member_message_template') . ' WHERE uniacid=:uniacid and typecode=:typecode ', array(':uniacid' => $_W['uniacid'], ':typecode' => 'commission'));
		include $this->template();
	}

	public function set()
	{
		global $_W;
		global $_GPC;

		if ($_W['ispost']) {
			$data = (is_array($_GPC['data']) ? $_GPC['data'] : array());
			$data['cashcredit'] = intval($data['cashcredit']);
			$data['cashweixin'] = intval($data['cashweixin']);
			$data['cashother'] = intval($data['cashother']);
			$data['cashalipay'] = intval($data['cashalipay']);
			$data['cashcard'] = intval($data['cashcard']);

			if (!empty($data['withdrawcharge'])) {
				$data['withdrawcharge'] = trim($data['withdrawcharge']);
				$data['withdrawcharge'] = floatval(trim($data['withdrawcharge'], '%'));
			}

			$data['withdrawbegin'] = floatval(trim($data['withdrawbegin']));
			$data['withdrawend'] = floatval(trim($data['withdrawend']));
			$data['register_bottom_content'] = m('common')->html_images($data['register_bottom_content']);
			$data['applycontent'] = m('common')->html_images($data['applycontent']);
			$data['regbg'] = save_media($data['regbg']);
			$data['become_goodsid'] = intval($_GPC['become_goodsid']);
			$data['texts'] = is_array($_GPC['texts']) ? $_GPC['texts'] : array();
			m('common')->updatePluginset(array('commission' => $data));
			m('cache')->set('template_' . $this->pluginname, $data['style']);
			$selfbuy = ($data['selfbuy'] ? '开启' : '关闭');
			$become_child = ($data['become_child'] ? ($data['become_child'] == 1 ? '首次下单' : '首次付款') : '首次点击分享连接');

			switch ($data['become']) {
			case '0':
				$become = '无条件';
				break;

			case '1':
				$become = '申请';
				break;

			case '2':
				$become = '消费次数';
				break;

			case '3':
				$become = '消费金额';
				break;

			case '4':
				$become = '购买商品';
				break;
			}

			plog('commission.set.edit', '修改基本设置<br>' . '分销内购 -- ' . $selfbuy . '<br>成为下线条件 -- ' . $become_child . '<br>成为分销商条件 -- ' . $become);
			show_json(1, array('url' => webUrl('commission/set', array('tab' => str_replace('#tab_', '', $_GPC['tab'])))));
		}

		$styles = array();
		$dir = IA_ROOT . '/addons/ewei_shopv2/plugin/' . $this->pluginname . '/template/mobile/';

		if ($handle = opendir($dir)) {
			while (($file = readdir($handle)) !== false) {
				if (($file != '..') && ($file != '.')) {
					if (is_dir($dir . '/' . $file)) {
						$styles[] = $file;
					}
				}
			}

			closedir($handle);
		}

		$data = m('common')->getPluginset('commission');
		$goods = false;

		if (!empty($data['become_goodsid'])) {
			$goods = pdo_fetch('select id,title,thumb from ' . tablename('ewei_shop_goods') . ' where id=:id and uniacid=:uniacid limit 1 ', array(':id' => $data['become_goodsid'], ':uniacid' => $_W['uniacid']));
		}

		include $this->template();
	}
}

?>
