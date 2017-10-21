<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

//print_r($_GPC["payyz"]);

$item = pdo_fetch("SELECT * FROM " . tablename($this -> table_order) . " WHERE ordersn='{$_GPC['ordersn']}' limit 1");

if ($item['status'] == 1 && $item['payyz'] == $_GPC['payyz'] && !empty($item['transid'])) {
	if ($_COOKIE["user_charge_payyz"] != $_GPC['payyz']) {
		if (!empty($_COOKIE["user_charge_payyz"])) {
			setcookie("user_charge_payyz", '', time() - 1);
		}
		if ($_GPC['type'] == 'gmgift') {
			$giftid = $item['giftid'];
			$tfrom_user = $item['tfrom_user'];
			$item_gift = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen_gift) . ' WHERE id = :id ', array(':id' => $giftid));
			$usergift = pdo_fetch("SELECT * FROM " . tablename($this -> table_user_gift) . ' WHERE giftid = :giftid AND from_user = :from_user AND rid = :rid AND status = 1 ', array(':giftid' => $giftid,':from_user' => $from_user,':rid' => $rid));
			if (empty($usergift)) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'rid' => $rid,
					'giftid' => $giftid,
					'giftnum' => 1,
					'status' => 1,
					'from_user' => $from_user,
					'lasttime' => time(),
					'createtime' => time(),
				);
				pdo_insert($this->table_user_gift, $data);
			}else{
				pdo_update($this->table_user_gift, array('giftnum' => $usergift['giftnum'] + 1, 'lasttime' => time()), array('rid' => $rid,'giftid' => $giftid, 'from_user'=>$from_user));//
			}
			pdo_update($this->table_jifen_gift, array('dhnum' => $item_gift['dhnum'] + 1), array('rid' => $rid,'id' => $giftid));
			pdo_update($this->table_order, array('ispayvote' => '6'), array('id' => $item['id']));
			setcookie("user_charge_payyz", $_GPC['payyz'], time() + 3600 * 24);
			$paydata = array('paystatus'=>'success','ordersn'=> $_GPC['ordersn'],'payyz'=> $item['payyz'] );
			$paymore = base64_encode(base64_encode(iserializer($paydata)));

			$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('giftvote', array('rid' => $rid,  'tfrom_user' => $tfrom_user, 'giftid' => $giftid, 'paymore' => $paymore));
			header("location:$url");
			exit ;

		}else{
			$remark = '微信充值，<span class="label label-warning">增加</span>'.$_GPC['jifen'].'积分';
			$this->addorderlog($rid, $_GPC['ordersn'], $from_user, $_GPC['jifen'], '积分充值', $type = '3', $remark);
			$this -> addjifencharge($_GPC['rid'], $_GPC['from_user'], $_GPC['jifen'], $_GPC['ordersn']);
			setcookie("user_charge_payyz", $_GPC['payyz'], time() + 3600 * 24);
		}
	}
}
if ($_GPC['type'] != 'gmgift') {
	$templatename = $rbasic['templates'];
	$toye = $this -> templatec($templatename, $_GPC['do']);
	include $this -> template($toye);
}