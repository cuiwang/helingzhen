<?php
defined('IN_IA') or exit('Access Denied');
load()->classs('coupon');
class WnCoupon extends coupon {
	/*
	 * 生成发送卡券的card_ext 字段
	 * $id 卡券id(微赞系统中的id)
	 * 悟空源码网www.5 kym.com出品
	 * $openid 粉丝openid 如果不为空,则只有该粉丝可以领取发放的卡券
	 * $type 发送卡券类型 membercard（会员卡） 或 coupon（卡券） 默认为coupon
	 * */
	public function BuildCardExt($id, $openid = '') {
		$card_id = pdo_fetchcolumn('SELECT card_id FROM ' . tablename('storex_coupon') . ' WHERE id = :id', array(':id' => $id));
		if (empty($card_id)) {
			return error(-1, '卡券id不合法');
		}
		$time = TIMESTAMP;
		$sign = array($card_id, $time);
		$signature = $this->SignatureCard($sign);
		if (is_error($signature)) {
			return $signature;
		}
		$cardExt = array('timestamp' => $time, 'signature' => $signature);
		$cardExt = json_encode($cardExt);
		return array('card_id' => $card_id, 'card_ext' => $cardExt);
	}

}
