<?php
/**
 * 拼团模块微站定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Feng_fightgroupsModuleSite extends WeModuleSite {
//会员信息提取
	public function __construct(){
		global $_W;
		load()->model('mc');
		$profile = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$_W['openid']}'");
		if (empty($profile)) {
			$userinfo = mc_oauth_userinfo();
			if (!empty($userinfo['avatar'])) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'from_user' => $userinfo['openid'],
					'nickname' => $userinfo['nickname'],
					'avatar' => $userinfo['avatar']
				);
				pdo_insert('tg_member', $data);
			}
		}
	}
	/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为微信端页面管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	//微信端首页
	public function doMobileIndex() {
		$this -> __mobile(__FUNCTION__);
	}

	//团购
	public function doMobileGroup() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端商品详情页
	public function doMobileGoodDetails() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端商品详情页ajax
	public function doMobileIndexAjax() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端团购流程详情页
	public function doMobileRules() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端填写收货地址页面
	public function doMobileCreateAdd() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端填订单信息确认页面
	public function doMobileOrderConfirm() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端订单详情页面
	public function doMobileOrderDetails() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端订单页面
	public function doMobilemyOrder() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端取消订单
	public function doMobileCancelMyOrder() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端确认收货
	public function doMobileConfirMreceipt() {
		$this -> __mobile(__FUNCTION__);
	}
	public function doMobilemyGroup() {
		$this -> __mobile(__FUNCTION__);
	}
	//微信端收货地址管理页面
	public function doMobileAddManage() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端个人中心页面
	public function doMobilePerson() {
		$this -> __mobile(__FUNCTION__);
	}

	public function doMobilePay() {
		$this -> __mobile(__FUNCTION__);
	}
	/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为后台页面管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	//后台商品管理页面
	public function doWebGoods() {
		$this -> __web(__FUNCTION__);
	}
	
	//后台订单管理页面
	public function doWebOrder() {
		$this -> __web(__FUNCTION__);
	}
	//后台会员管理页面
	public function doWebMember() {
		$this -> __web(__FUNCTION__);
	}
	public function __web($f_name){
		global $_W,$_GPC;
		checklogin();
		$weid = $_W['uniacid'];
		load()->func('tpl');
		include_once  'web/'.strtolower(substr($f_name,5)).'.php';
	}
	
	public function __mobile($f_name){
		global $_W,$_GPC;
		/*checkauth();*/
		$weid = $_W['uniacid'];
		$share_data = $this->module['config'];
		$to_url = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
		/*$pro_realname = pdo_fetch("SELECT nickname FROM ".tablename('auction_member')." WHERE uniacid = '{$weid}' and from_user ='{$_W['openid']}' ");
		if (empty($pro_realname['nickname'])) {
			message('请先填写您的资料！', $this->createMobileUrl('prodata'), 'warning');
		}*/
		include_once  'mobile/'.strtolower(substr($f_name,8)).'.php';
	}
    public function payResult($params) {
		global $_W;
		$fee = intval($params['fee']);	
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => 1, 'wechat' => 2, 'alipay' => 2, 'delivery' => 3);
		$data['pay_type'] = $paytype[$params['type']];

		$sql = 'SELECT `g_id` FROM ' . tablename('tg_order') . ' WHERE `orderno` = :orderid';
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $params['tid']));
		$sql = 'SELECT * FROM ' . tablename('tg_goods') . ' WHERE `id` = :id';
		$goodsInfo = pdo_fetch($sql, array(':id' => $goodsId));
		// 更改库存
		if (!empty($goodsInfo['gnum'])) {
			pdo_update('tg_goods', array('gnum' => $goodsInfo['gnum'] - 1), array('id' => $goodsId));
			
		}
		// //货到付款
		if ($params['type'] == 'delivery') {
			$data['status'] = 1;
			$data['start_time'] = TIMESTAMP;
			$data['ptime'] = TIMESTAMP;
		}
		if($params['result'] == 'success'){
			$data['ptime'] = TIMESTAMP;
			$data['starttime'] = TIMESTAMP;
		}	
		pdo_update('tg_order', $data, array('orderno' => $params['tid']));
		$tuan_id = pdo_fetch("select * from".tablename('tg_order') . "where orderno = '{$params['tid']}'");
		//删除相同用户相同tuan_id下未支付的订单
		pdo_delete('tg_order', array('openid' => $tuan_id['openid'],'status'=>0,'pay_type'=>0));
		if ($params['from'] == 'return') {
			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				if($tuan_id['is_tuan'] == 0){
					message('支付成功！', $this->createMobileUrl('orderdetails',array('id' => $params['tid'])), 'success');
				}else{
					message('支付成功！', $this->createMobileUrl('group',array('tuan_id' => $tuan_id['tuan_id'])), 'success');
				}
				
			} else {
				if($tuan_id['is_tuan'] == 0){
				message('支付成功！', '../../app/' . $this->createMobileUrl('orderdetails',array('id' => $params['tid'])), 'success');
			   }else{
			   	message('支付成功！', '../../app/' . $this->createMobileUrl('group',array('tuan_id' => $tuan_id['tuan_id'])), 'success');
			   }
			}
		}

	}

}
