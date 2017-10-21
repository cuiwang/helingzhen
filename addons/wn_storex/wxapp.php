<?php 
use Qiniu\json_decode;

/**
 * 小程序入口
 */
defined('IN_IA') or exit('Access Denied');

class Wn_storexModuleWxapp extends WeModuleWxapp {
	//获取该公众号下的所有酒店信息
	public function doPageRoute(){
		load()->func('communication');
		global $_GPC, $_W;
		$ac = $_GPC['ac'];
		$url_param = $this->actions($ac);
		$url_param['m'] = $_GPC['m'] ? $_GPC['m'] : 'wn_storex';
		$params = json_decode(htmlspecialchars_decode($_GPC['params']), true);
		$params['userid'] = mc_openid2uid($_SESSION['openid']);
		if (empty($params['userid'])) {
			return $this->result(41009, '请重新登录!', array());
		}
		$this->check_login();
		$url = murl('entry', $url_param, true, true);
		$result = ihttp_request($url, $params);
		$result = json_decode($result['content'], true);
		return $this->result($result['message']['errno'], $result['message']['message'], empty($result['message']['data']) ? '' : $result['message']['data']);
	}
	function actions($ac) {
		$actions = array(
			'storeList' => array('do' => 'store', 'op' => 'store_list'),
			'storeDetail' => array('do' => 'store', 'op' => 'store_detail'),
			'storeComment' => array('do' => 'store', 'op' => 'store_comment'),
				
			'categoryClass' => array('do' => 'category', 'op' => 'class'),
			'categorySub' => array('do' => 'category', 'op' => 'sub_class'),
			'moreGoods' => array('do' => 'category', 'op' => 'more_goods'),
				
			'goodsInfo' => array('do' => 'goods', 'op' => 'goods_info'),
			'goodsBuyInfo' => array('do' => 'goods', 'op' => 'info'),
			'goodsOrder' => array('do' => 'goods', 'op' => 'order'),
			'creditGoodsList' => array('do' => 'goods', 'op' => 'display'),
			'creditGoodsDetail' => array('do' => 'goods', 'op' => 'detail'),
			'creditGoodsExchange' => array('do' => 'goods', 'op' => 'exchange'),
			'creditGoodsMine' => array('do' => 'goods', 'op' => 'mine'),
			'creditMineConfirm' => array('do' => 'goods', 'op' => 'confirm'),
				
			'getUserInfo' => array('do' => 'usercenter', 'op' => 'personal_info'),
			'updateUserInfo' => array('do' => 'usercenter', 'op' => 'personal_update'),
			'addressLists' => array('do' => 'usercenter', 'op' => 'address_lists'),
			'deleteAddress' => array('do' => 'usercenter', 'op' => 'address_delete'),
			'defaultAddress' => array('do' => 'usercenter', 'op' => 'address_default'),
			'addressInfo' => array('do' => 'usercenter', 'op' => 'current_address'),
			'editAddress' => array('do' => 'usercenter', 'op' => 'address_post'),
			'userCredits' => array('do' => 'usercenter', 'op' => 'credits_record'),
			'extend' => array('do' => 'usercenter', 'op' => 'extend_switch'),
				
			'orderPay' => array('do' => 'orders', 'op' => 'orderpay'),
			'orderList' => array('do' => 'orders', 'op' => 'order_list'),
			'orderInfo' => array('do' => 'orders', 'op' => 'order_detail'),
			'orderComment' => array('do' => 'orders', 'op' => 'order_comment'),
			'orderCancel' => array('do' => 'orders', 'op' => 'cancel'),
			'orderConfirm' => array('do' => 'orders', 'op' => 'confirm_goods'),
				
			'cardRecharge' => array('do' => 'recharge', 'op' => 'card_recharge'),
			'rechargeAdd' => array('do' => 'recharge', 'op' => 'recharge_add'),
			'rechargePay' => array('do' => 'recharge', 'op' => 'recharge_pay'),
				
			'signInfo' => array('do' => 'sign', 'op' => 'sign_info'),
			'signSing' => array('do' => 'sign', 'op' => 'sign'),
			'remedySign' => array('do' => 'sign', 'op' => 'remedy_sign'),
			'signRecord' => array('do' => 'sign', 'op' => 'sign_record'),
				
			'noticeList' => array('do' => 'notice', 'op' => 'notice_list'),
			'noticeRead' => array('do' => 'notice', 'op' => 'read_notice'),
				
			'receiveCard' => array('do' => 'membercard', 'op' => 'receive_card'),
			'receiveInfo' => array('do' => 'membercard', 'op' => 'receive_info'),
				
			'couponList' => array('do' => 'coupon', 'op' => 'display'),
			'couponExchange' => array('do' => 'coupon', 'op' => 'exchange'),
			'myCoupon' => array('do' => 'coupon', 'op' => 'mine'),
			'couponInfo' => array('do' => 'coupon', 'op' => 'detail'),
			'wxAddCard' => array('do' => 'coupon', 'op' => 'addcard'),
			'wxOpenCard' => array('do' => 'coupon', 'op' => 'opencard'),
			'creditCouponList' => array('do' => 'coupon', 'op' => 'display'),
			'creditCouponDetail' => array('do' => 'coupon', 'op' => 'detail'),
				
			'clerkPermission' => array('do' => 'clerk', 'op' => 'permission_storex'),
			'clerkOrder' => array('do' => 'clerk', 'op' => 'order'),
			'clerkOrderInfo' => array('do' => 'clerk', 'op' => 'order_info'),
			'clerkOrderEdit' => array('do' => 'clerk', 'op' => 'edit_order'),
			'clerkRoom' => array('do' => 'clerk', 'op' => 'room'),
			'clerkRoomInfo' => array('do' => 'clerk', 'op' => 'room_info'),
			'clerkRoomEdit' => array('do' => 'clerk', 'op' => 'edit_room'),
		);
		if (!empty($actions[$ac])) {
			return $actions[$ac];
		}
		return $this->result(-1, '访问失败', array());
	}
	public function doPageLocation() {
		global $_GPC;
		load()->func('communication');
		$url = 'https://api.map.baidu.com/geocoder/v2/?';
		$params = $_GPC['params'];
		$result = ihttp_request($url, $params);
		exit($result['content']);
	}
	//检查登录
	public function check_login(){
		global $_GPC, $_W;
		$info = array();
		if(empty($_SESSION['openid'])){
			return $this->result(41009, '请重新登录!', array());
		}else{
			load()->model('mc');
			$_W['member'] = mc_fetch($_SESSION['openid']);
			$info['code'] = 0;
			$info['message'] = '登录状态不变';
			$weid = intval($_W['uniacid']);
			$user_info = pdo_fetch("SELECT * FROM " . tablename('storex_member') . " WHERE from_user = :from_user AND weid = :weid limit 1", array(':from_user' => $_SESSION['openid'], ':weid' => $weid));
			if(empty($user_info)){
				$member = array();
				$member['weid'] = $weid;
				$member['from_user'] = $_SESSION['openid'];
				
				$member['createtime'] = time();
				$member['isauto'] = 1;
				$member['status'] = 1;
				pdo_insert('storex_member', $member);
				$member['id'] = pdo_insertid();
				if (empty($member['id'])) {
					return $this->result(41009, '请重新登录', array());
				}
			}
		}
		return $info;
	}
}