<?php
class backlist{
	//后台管理列表状态判断
	function _calc_current_frames2(&$frames) {
		global $_W,$_GPC;
		if(!empty($frames) && is_array($frames)) {
			foreach($frames as &$frame) {
				foreach($frame['items'] as &$fr) {
					$query = parse_url($fr['url'], PHP_URL_QUERY);
					parse_str($query, $urls);
					if(defined('ACTIVE_FRAME_URL')) {
						$query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
						parse_str($query, $get);
					} else {
						$get = $_GET;
					}
					if(!empty($_GPC['a'])) {
						$get['a'] = $_GPC['a'];
					}
					if(!empty($_GPC['c'])) {
						$get['c'] = $_GPC['c'];
					}
					if(!empty($_GPC['do'])) {
						$get['do'] = $_GPC['do'];
					}
					if(!empty($_GPC['doo'])) {
						$get['doo'] = $_GPC['doo'];
					}
					if(!empty($_GPC['act'])) {
						$get['act'] = $_GPC['act'];
					}
					if(!empty($_GPC['state'])) {
						$get['state'] = $_GPC['state'];
					}
					if(!empty($_GPC['op'])) {
						$get['op'] = $_GPC['op'];
					}
					if(!empty($_GPC['m'])) {
						$get['m'] = $_GPC['m'];
					}
					$diff = array_diff_assoc($urls, $get);

					if(empty($diff)) {
						$fr['active'] = ' active';
					}
				}
			}
		}
	}

	//后台管理列表生成
	function getModuleFrames($name){
		global $_W;
		$frames = array();
		$frames['goods']['title'] = '<i class="fa fa-inbox"></i>&nbsp;&nbsp; 商品';
		$frames['goods']['items'] = array();
		$frames['goods']['items']['goods']['url'] = url('site/entry/goods/',array('m'=>$name));
		$frames['goods']['items']['goods']['title'] = '商品管理';
		$frames['goods']['items']['goods']['actions'] = array();
		$frames['goods']['items']['goods']['active'] = '';

		$frames['goods']['items']['area']['url'] = url('site/entry/area/',array('m'=>$name));
		$frames['goods']['items']['area']['title'] = '配送方式';
		$frames['goods']['items']['area']['actions'] = array();
		$frames['goods']['items']['area']['active'] = '';

		$frames['goods']['items']['category']['url'] = url('site/entry/category/',array('m'=>$name));
		$frames['goods']['items']['category']['title'] = '分类管理';
		$frames['goods']['items']['category']['actions'] = array();
		$frames['goods']['items']['category']['active'] = '';

		$frames['goods']['items']['adv']['url'] = url('site/entry/adv/',array('m'=>$name));
		$frames['goods']['items']['adv']['title'] = '幻灯片管理';
		$frames['goods']['items']['adv']['actions'] = array();
		$frames['goods']['items']['adv']['active'] = '';

		$frames['order']['title'] = '<i class="fa fa-list"></i>&nbsp;&nbsp; 订单';
		$frames['order']['items'] = array();
		$frames['order']['items']['order']['url'] = url('site/entry/order/',array('m'=>$name));
		$frames['order']['items']['order']['title'] = '订单管理';
		$frames['order']['items']['order']['actions'] = array();
		$frames['order']['items']['order']['active'] = '';
		
		$frames['order']['items']['check']['url'] = url('site/entry/check/',array('m'=>$name));
		$frames['order']['items']['check']['title'] = '核销订单';
		$frames['order']['items']['check']['actions'] = array();
		$frames['order']['items']['check']['active'] = '';
		
		$frames['order']['items']['import']['url'] = url('site/entry/import/',array('m'=>$name));
		$frames['order']['items']['import']['title'] = '批量发货';
		$frames['order']['items']['import']['actions'] = array();
		$frames['order']['items']['import']['active'] = '';

		$frames['group']['title'] = '<i class="fa fa-users"></i>&nbsp;&nbsp; 团购';
		$frames['group']['items'] = array();
		$frames['group']['items']['group']['url'] = url('site/entry/grouporder/',array('m'=>$name));
		$frames['group']['items']['group']['title'] = '团购管理';
		$frames['group']['items']['group']['actions'] = array();
		$frames['group']['items']['group']['active'] = '';
		
		$frames['group']['items']['firstgrouper']['url'] = url('site/entry/firstgrouper/',array('m'=>$name));
		$frames['group']['items']['firstgrouper']['title'] = '团长优惠';
		$frames['group']['items']['firstgrouper']['actions'] = array();
		$frames['group']['items']['firstgrouper']['active'] = '';

		$frames['refund']['title'] = '<i class="fa fa-money"></i>&nbsp;&nbsp; 退款';
		$frames['refund']['items'] = array();
		$frames['refund']['items']['refund']['url'] = url('site/entry/refund/',array('m'=>$name));
		$frames['refund']['items']['refund']['title'] = '退款记录';
		$frames['refund']['items']['refund']['actions'] = array();
		$frames['refund']['items']['refund']['active'] = '';

		$frames['setting']['title'] = '<i class="fa fa-cog"></i>&nbsp;&nbsp; 设置';
		$frames['setting']['items'] = array();
		$frames['setting']['items']['setting']['url'] = url('profile/module/setting/',array('m'=>$name));
		$frames['setting']['items']['setting']['title'] = '参数设置';
		$frames['setting']['items']['setting']['actions'] = array();
		$frames['setting']['items']['setting']['active'] = '';
		
		$frames['setting']['items']['hexiao']['url'] = url('site/entry/hexiao/',array('m'=>$name));
		$frames['setting']['items']['hexiao']['title'] = '线下核销';
		$frames['setting']['items']['hexiao']['actions'] = array();
		$frames['setting']['items']['hexiao']['active'] = '';
		
		$frames['setting']['items']['print']['url'] = url('site/entry/print/',array('m'=>$name));
		$frames['setting']['items']['print']['title'] = '打印机设置';
		$frames['setting']['items']['print']['actions'] = array();
		$frames['setting']['items']['print']['active'] = '';
		return $frames;
	}
}
?>