<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Address {
	public function getList($openid = '') {
		global $_W;
		$sql = "SELECT * FROM " . tablename('weliam_indiana_address') . " where uniacid = '{$_W['uniacid']}' and openid='{$openid}'";
		$list = pdo_fetchall($sql);
		return $list;
	} 
	function getAddress($openid){
		global $_W;
		$sql = "SELECT * FROM " . tablename('weliam_indiana_address') . " where uniacid = '{$_W['uniacid']}' and openid='{$openid}' and isdefault = 1 ";
		$address = pdo_fetch($sql);
		return $address;
	}
} 
