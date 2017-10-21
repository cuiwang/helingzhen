<?php
defined('IN_IA') or exit('Access Denied');

function _calc_current_frames2(&$frames) {
	global $_W,$_GPC;
	if(!empty($frames) && is_array($frames)) {
		foreach($frames as &$frame) {
			foreach($frame['items'] as &$fr) {
				if(count($fr['actions']) == 2){
					if($fr['actions']['1'] == $_GPC[$fr['actions']['0']]){
						$fr['active'] = 'active';
					}
				}elseif(count($fr['actions']) == 4){
					if($fr['actions']['1'] == $_GPC[$fr['actions']['0']] && $fr['actions']['3'] == $_GPC[$fr['actions']['2']]){
						$fr['active'] = 'active';
					}
				}else{
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
					if(!empty($_GPC['ac'])) {
						$get['ac'] = $_GPC['ac'];
					}
					if(!empty($_GPC['status'])) {
						$get['status'] = $_GPC['status'];
					}
					if(!empty($_GPC['op'])) {
						$get['op'] = $_GPC['op'];
					}
					if(!empty($_GPC['m'])) {
						$get['m'] = $_GPC['m'];
					}
					$diff = array_diff_assoc($urls, $get);
				
					if(empty($diff)) {
						$fr['active'] = 'active';
					}else{
						$fr['active'] = '';
					}
				}
			}
		}
	}
}

//后台管理列表生成
function getstoreFrames(){
	global $_W;
	$frames = array();
	$frames['store']['title'] = '<i class="fa fa-gear"></i>&nbsp;&nbsp; 商城设置';
	$frames['store']['items'] = array();
	$frames['store']['items']['setting']['url'] = web_url('setting/display');
	$frames['store']['items']['setting']['title'] = '系统设置';
	$frames['store']['items']['setting']['actions'] = array();
	$frames['store']['items']['setting']['active'] = '';
	
	$frames['store']['items']['cart']['url'] = web_url('cart/display');
	$frames['store']['items']['cart']['title'] = '购物设置';
	$frames['store']['items']['cart']['actions'] = array('do','cart');
	$frames['store']['items']['cart']['active'] = '';

	$frames['page']['title'] = '<i class="fa fa-inbox"></i>&nbsp;&nbsp; 主页管理';
	$frames['page']['items'] = array();
	
	$frames['page']['items']['adv']['url'] = web_url('adv/display');
	$frames['page']['items']['adv']['title'] = '幻灯片';
	$frames['page']['items']['adv']['actions'] = array('do','adv');
	$frames['page']['items']['adv']['active'] = '';
	
	$frames['page']['items']['nav']['url'] = web_url('navi/display');
	$frames['page']['items']['nav']['title'] = '导航栏';
	$frames['page']['items']['nav']['actions'] = array('do','navi');
	$frames['page']['items']['nav']['active'] = '';
	
	$frames['page']['items']['notice']['url'] = web_url('notice/display');
	$frames['page']['items']['notice']['title'] = '公告栏';
	$frames['page']['items']['notice']['actions'] = array('do','notice');
	$frames['page']['items']['notice']['active'] = '';
	
	$frames['setting']['title'] = '<i class="fa fa-inbox"></i>&nbsp;&nbsp; 入口管理';
	$frames['setting']['items'] = array();
	
	$frames['setting']['items']['index']['url'] = web_url('entry/index');
	$frames['setting']['items']['index']['title'] = '首页入口';
	$frames['setting']['items']['index']['actions'] = array('op','index');
	$frames['setting']['items']['index']['active'] = '';
	
	$frames['setting']['items']['person']['url'] = web_url('entry/person');
	$frames['setting']['items']['person']['title'] = '个人中心';
	$frames['setting']['items']['person']['actions'] = array('op','person');
	$frames['setting']['items']['person']['active'] = '';
	
	$frames['setting']['items']['rule']['url'] = web_url('entry/rule');
	$frames['setting']['items']['rule']['title'] = '开奖规则';
	$frames['setting']['items']['rule']['actions'] = array('op','rule');
	$frames['setting']['items']['rule']['active'] = '';
	
	return $frames;
}

function getgoodsFrames(){
	global $_W;
	$frames = array();
	$frames['goods']['title'] = '<i class="fa fa-gift"></i>&nbsp;&nbsp; 商品管理';
	$frames['goods']['items'] = array();
	$frames['goods']['items']['display']['url'] = web_url('goods/display');
	$frames['goods']['items']['display']['title'] = '商品列表';
	$frames['goods']['items']['display']['actions'] = array();
	$frames['goods']['items']['display']['active'] = '';

	$frames['goods']['items']['recover']['url'] = web_url('goods/recover');
	$frames['goods']['items']['recover']['title'] = '商品回收站';
	$frames['goods']['items']['recover']['actions'] = array();
	$frames['goods']['items']['recover']['active'] = '';
	
	$frames['goods']['items']['edit']['url'] = web_url('goods/edit');
	$frames['goods']['items']['edit']['title'] = '添加商品';
	$frames['goods']['items']['edit']['actions'] = array();
	$frames['goods']['items']['edit']['active'] = '';
	
	$frames['other']['title'] = '<i class="fa fa-bookmark"></i>&nbsp;&nbsp; 其他管理';
	$frames['other']['items'] = array();
	$frames['other']['items']['category']['url'] = web_url('category/display');
	$frames['other']['items']['category']['title'] = '商品分类';
	$frames['other']['items']['category']['actions'] = array('do','category');
	$frames['other']['items']['category']['active'] = '';
	
	return $frames;
}

function getorderFrames(){
	global $_W;
	$frames = array();
	$frames['order']['title'] = '<i class="fa fa-list"></i>&nbsp;&nbsp; 订单管理';
	$frames['order']['items'] = array();
	
	$frames['order']['items']['received']['url'] = web_url('order');
	$frames['order']['items']['received']['title'] = '快递订单';
	$frames['order']['items']['received']['actions'] = array('op','');
	$frames['order']['items']['received']['active'] = '';

	$frames['order']['items']['alonebuy']['url'] = web_url('order/alone_order');
	$frames['order']['items']['alonebuy']['title'] = '全价购买订单';
	$frames['order']['items']['alonebuy']['actions'] = array('op','alone_order');
	$frames['order']['items']['alonebuy']['active'] = '';
	return $frames;
}

function getmemberFrames(){
	global $_W;
	$frames = array();
	$frames['member']['title'] = '<i class="fa fa-user"></i>&nbsp;&nbsp; 会员管理';
	$frames['member']['items'] = array();
	$frames['member']['items']['display']['url'] = web_url('member/display');
	$frames['member']['items']['display']['title'] = '会员管理';
	$frames['member']['items']['display']['actions'] = array('do','member');
	$frames['member']['items']['display']['active'] = '';
	
	$frames['member']['items']['showorder']['url'] = web_url('showorder/display');
	$frames['member']['items']['showorder']['title'] = '晒单管理';
	$frames['member']['items']['showorder']['actions'] = array('do','showorder');
	$frames['member']['items']['showorder']['active'] = '';
	
	return $frames;
}

function getappFrames(){
	global $_W,$_GPC;
	$frames = array();
	$frames['plug_list']['title'] = '<i class="fa fa-cloud"></i>&nbsp;&nbsp; 应用列表';
	$frames['plug_list']['items'] = array();

	$frames['plug_list']['items']['plug_list']['url'] = web_url('plug_list');
	$frames['plug_list']['items']['plug_list']['title'] = '所有应用';
	$frames['plug_list']['items']['plug_list']['actions'] = array();
	$frames['plug_list']['items']['plug_list']['active'] = '';
	$frames['plug_list']['items']['plug_list']['append']['url'] = web_url('plug_list');
	$frames['plug_list']['items']['plug_list']['append']['title'] = '<i class="fa fa-plus"></i>';
	
	if($_GPC['do'] == 'plug_list'){
		$frames['app']['title'] = '<i class="fa fa-cloud"></i>&nbsp;&nbsp; 运营工具';
		$frames['app']['items'] = array();
	
		$frames['app']['items']['machine_data']['url'] = web_url('machine');
		$frames['app']['items']['machine_data']['title'] = '机器人管理';
		$frames['app']['items']['machine_data']['actions'] = array();
		$frames['app']['items']['machine_data']['active'] = '';
	
		$frames['app']['items']['setting_note']['url'] = web_url('sms/display');
		$frames['app']['items']['setting_note']['title'] = '自定义短信';
		$frames['app']['items']['setting_note']['actions'] = array();
		$frames['app']['items']['setting_note']['active'] = '';
	}
	
	if($_GPC['do'] == 'sms'){
		$frames['sms']['title'] = '<i class="fa fa-cloud"></i>&nbsp;&nbsp; 自定义短信';
		$frames['sms']['items'] = array();
	
		$frames['sms']['items']['note_display']['url'] = web_url('sms/display');
		$frames['sms']['items']['note_display']['title'] = '短信模板';
		$frames['sms']['items']['note_display']['actions'] = array();
		$frames['sms']['items']['note_display']['active'] = '';
		
		$frames['sms']['items']['note_add']['url'] = web_url('sms/add');
		$frames['sms']['items']['note_add']['title'] = '添加模板';
		$frames['sms']['items']['note_add']['actions'] = array();
		$frames['sms']['items']['note_add']['active'] = '';
		
		$frames['note_setting_title']['title'] = '<i class="fa fa-cloud"></i>&nbsp;&nbsp; 短信设置';
		$frames['note_setting_title']['items'] = array();
	
		$frames['note_setting_title']['items']['note_setting']['url'] = web_url('sms/setting');
		$frames['note_setting_title']['items']['note_setting']['title'] = '短信设置';
		$frames['note_setting_title']['items']['note_setting']['actions'] = array();
		$frames['note_setting_title']['items']['note_setting']['active'] = '';
		
		$frames['note_setting_title']['items']['note_param']['url'] = web_url('sms/param');
		$frames['note_setting_title']['items']['note_param']['title'] = '参数设置';
		$frames['note_setting_title']['items']['note_param']['actions'] = array();
		$frames['note_setting_title']['items']['note_param']['active'] = '';
	}
	
	if($_GPC['do'] == 'machine'){
		$frames['app']['title'] = '<i class="fa fa-cloud"></i>&nbsp;&nbsp; 机器人管理';
		$frames['app']['items'] = array();
	
		$frames['app']['items']['machine_data']['url'] = web_url('machine');
		$frames['app']['items']['machine_data']['title'] = '机器人管理';
		$frames['app']['items']['machine_data']['actions'] = array();
		$frames['app']['items']['machine_data']['active'] = '';
	}
	return $frames;
}

function getdataFrames(){
	global $_W;
	$frames = array();
	$frames['data']['title'] = '<i class="fa fa-pie-chart"></i>&nbsp;&nbsp; 管理数据';
	$frames['data']['items'] = array();
	
	$frames['data']['items']['goods_data']['url'] = web_url('record/display');
	$frames['data']['items']['goods_data']['title'] = '交易记录';
	$frames['data']['items']['goods_data']['actions'] = array('op','display');
	$frames['data']['items']['goods_data']['active'] = '';
	
	$frames['data']['items']['order_data']['url'] = web_url('record/recharge');
	$frames['data']['items']['order_data']['title'] = '充值记录';
	$frames['data']['items']['order_data']['actions'] = array('op','recharge');
	$frames['data']['items']['order_data']['active'] = '';
	
	return $frames;
}

function getsysFrames(){
	global $_W;
	$frames = array();
	$frames['sys']['title'] = '<i class="fa fa-cloud"></i>&nbsp;&nbsp; 系统设置';
	$frames['sys']['items'] = array();



	
	return $frames;
}

function get_top_menus(){
	global $_W;
	$frames = array();
	$frames['store']['title'] = '<i class="fa fa-desktop"></i>&nbsp;&nbsp; 商城';
	$frames['store']['url'] = web_url('adv/display');
	$frames['store']['active'] = 'store';
	
	$frames['goods']['title'] = '<i class="fa fa-gift"></i>&nbsp;&nbsp; 商品';
	$frames['goods']['url'] = web_url('goods/display');
	$frames['goods']['active'] = 'goods';
	
	$frames['order']['title'] = '<i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; 订单';
	$frames['order']['url'] = web_url('order');
	$frames['order']['active'] = 'order';
	
	$frames['member']['title'] = '<i class="fa fa-user"></i>&nbsp;&nbsp; 会员';
	$frames['member']['url'] = web_url('member');
	$frames['member']['active'] = 'member';
	
	$frames['data']['title'] = '<i class="fa fa-area-chart"></i>&nbsp;&nbsp; 数据';
	$frames['data']['url'] = web_url('record/display');
	$frames['data']['active'] = 'data';
	
	$frames['app']['title'] = '<i class="fa fa-cubes"></i>&nbsp;&nbsp; 应用';
	$frames['app']['url'] = web_url('plug_list');
	$frames['app']['active'] = 'app';
	

	
	return $frames;
}
