<?php
defined('IN_IA') or exit('Access Denied');

class We7_couponModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W;
		load()->model('mc');
		if ($this->message['event'] == 'user_get_card') {
			if (empty($this->message['isgivebyfriend'])) {
				$coupon_record = pdo_get('coupon_record', array('card_id' => trim($this->message['cardid']), 'openid' => trim($this->message['fromusername']), 'status' => 1, 'code' => ''), array('id'));
				if (!empty($coupon_record)) {
					pdo_update('coupon_record', array('code' => trim($this->message['usercardcode'])), array('id' => $coupon_record['id']));
				} else {
					$fans_info = mc_fansinfo($this->message['fromusername']);
					$coupon_info = pdo_get('coupon', array('card_id' => $this->message['cardid']));
					$pcount = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('coupon_record') . " WHERE `openid` = :openid AND `couponid` = :couponid", array(':couponid' => $coupon_info['id'], ':openid' => trim($this->message['fromusername'])));
					if ($pcount < $coupon_info['get_limit'] && $coupon_info['quantity'] > 0) {
						$insert_data = array(
							'uniacid' => $fans_info['uniacid'],
							'card_id' => $this->message['cardid'],
							'openid' => $this->message['fromusername'],
							'code' => $this->message['usercardcode'],
							'addtime' => TIMESTAMP,
							'status' => '1',
							'uid' => $fans_info['uid'],
							'grantmodule' => 'we7_coupon',
							'remark' => '用户通过投放扫码',
							'couponid' => $coupon_info['id'],
						);
						pdo_insert('coupon_record', $insert_data);
						pdo_update('coupon', array('quantity' => $coupon_info['quantity'] - 1, 'dosage' => $coupon_info['dosage'] + 1), array('uniacid' => $fans_info['uniacid'], 'id' => $coupon_info['id']));
					}
				}
			} else {
				$old_record = pdo_get('coupon_record', array('openid' => trim($this->message['friendusername']), 'card_id' => trim($this->message['cardid']), 'code' => trim($this->message['oldusercardcode'])));
				pdo_update('coupon_record', array('addtime' => TIMESTAMP, 'givebyfriend' => intval($this->message['isgivebyfriend']), 'openid' => trim($this->message['fromusername']), 'code' => trim($this->message['usercardcode']), 'status' => 1), array('id' => $old_record['id']));
			}
		} elseif ($this->message['event'] == 'user_del_card') {
			//用户删除卡券事件
			$card_id = trim($this->message['cardid']);
			$openid = trim($this->message['fromusername']);
			$code = trim($this->message['usercardcode']);
			pdo_update('coupon_record', array('status' => 4), array('card_id' => $card_id, 'openid' => $openid, 'code' => $code));
		} elseif ($this->message['event'] == 'user_consume_card') {
			//核销卡券事件
			$card_id = trim($this->message['cardid']);
			$openid = trim($this->message['fromusername']);
			$code = trim($this->message['usercardcode']);
			pdo_update('coupon_record', array('status' => 3), array('card_id' => $card_id, 'openid' => $openid, 'code' => $code));
		}
	}
}