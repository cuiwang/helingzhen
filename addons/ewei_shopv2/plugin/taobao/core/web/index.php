<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Index_EweiShopV2Page extends PluginWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;

		if (cv('taobao.main')) {
			$sql = 'SELECT * FROM ' . tablename('ewei_shop_category') . ' WHERE `uniacid` = :uniacid ORDER BY `parentid`, `displayorder` DESC';
			$category = m('shop')->getFullCategory(true, true);
			$shopset = $_W['shopset']['shop'];
			load()->func('tpl');
			include $this->template();
		}
		else if (cv('taobao.jingdong')) {
			header('location: ' . webUrl('taobao/jingdong'));
			exit();
		}
		else if (cv('taobao.one688')) {
			header('location: ' . webUrl('taobao/one688'));
			exit();
		}
		else {
			if (cv('taobao.taobaocsv')) {
				header('location: ' . webUrl('taobao/taobaocsv'));
				exit();
			}
		}
	}

	public function fetch()
	{
		global $_GPC;
		set_time_limit(0);
		$ret = array();
		$url = $_GPC['url'];
		$cates = $_GPC['cate'];

		if (is_numeric($url)) {
			$itemid = $url;
		}
		else {
			preg_match('/id\\=(\\d+)/i', $url, $matches);

			if (isset($matches[1])) {
				$itemid = $matches[1];
			}
		}

		if (empty($itemid)) {
			exit(json_encode(array('result' => 0, 'error' => '未获取到 itemid!')));
		}

		$ret = $this->model->get_item_taobao($itemid, $_GPC['url'], $cates);
		plog('taobao.main', '淘宝抓取宝贝 淘宝id:' . $itemid);
		exit(json_encode($ret));
	}
}

?>
