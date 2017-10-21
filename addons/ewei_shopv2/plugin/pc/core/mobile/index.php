<?php
error_reporting(0);
if (!(defined("IN_IA"))) 
{
	exit("Access Denied");
}
require EWEI_SHOPV2_PLUGIN . "pc/core/page_mobile.php";
class Index_EweiShopV2Page extends PcMobilePage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		$member = m('member')->getMember($_W['openid'], true);
		$categories = m('shop')->getCategory();
		$slides = pdo_fetchall('select id,advname,link,thumb,backcolor from ' . tablename('ewei_shop_pc_slide') . ' where uniacid=:uniacid and enabled=1 AND type=0 order by displayorder desc', array(':uniacid' => $_W['uniacid']));
		$recommends = pdo_fetchall('select id,advname,link,thumb from ' . tablename('ewei_shop_pc_slide') . ' where uniacid=:uniacid and enabled=1 AND type=1 order by displayorder desc', array(':uniacid' => $_W['uniacid']));
		$new_list = $this->goods_list(array('pagesize' => 5, 'isnew' => 1));
		$hot_list = $this->goods_list(array('pagesize' => 5, 'ishot' => 1));
		$time_list = $this->goods_list(array('pagesize' => 5, 'istime' => 1));
		$discount_list = $this->goods_list(array('pagesize' => 5, 'isdiscount' => 1));
		$recommand_list = $this->goods_list(array('pagesize' => 5, 'isrecommand' => 1));
		$notice_list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_notice') . ' WHERE uniacid=:uniacid AND status=1  ORDER BY displayorder DESC,id DESC limit 4', array(':uniacid' => $_W['uniacid']));
		include $this->template();
	}
	public function goods_list($args = array()) 
	{
		global $_GPC;
		global $_W;
		$_default = array('pagesize' => 10, 'page' => 1, 'isnew' => '', 'ishot' => '', 'isrecommand' => '', 'isdiscount' => '', 'istime' => '', 'keywords' => '', 'cate' => '', 'order' => 'id', 'by' => 'desc');
		$args = array_merge($_default, $args);
		$merch_plugin = p('merch');
		$merch_data = m('common')->getPluginset('merch');
		if ($merch_plugin && $merch_data['is_openmerch']) 
		{
			$args['merchid'] = intval($_GPC['merchid']);
		}
		if (isset($_GPC['nocommission'])) 
		{
			$args['nocommission'] = intval($_GPC['nocommission']);
		}
		$goods = m('goods')->getList($args);
		return $goods;
	}
	public function style($id) 
	{
		switch ($id) 
		{
			case 1: return 'style-red';
			case 2: return "style-pink";
			case 3: return "style-orange";
			case 4: return "style-green";
			case 5: return "style-blue";
			case 6: return "style-purple";
			case 7: return "style-brown";
			default: }
	}
}

?>