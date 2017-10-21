<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Tyzm_Present{
	public function __construct() {
		global $_W;
	}
	
	function upcredit($openid,$type,$num,$mode){//积分处理
		load()->model('mc');
		$uid = mc_openid2uid($openid);
		$creditname=self::getcreditname($type);
		mc_credit_update($uid,$type,$num,array(0,'投票赠送 '.$creditname.'：'.$num),$mode);
	}
	function getcreditname($key) {//积分名称
		$creditnames = uni_setting($_W['uniacid'], array('creditnames'));
		if($creditnames) {
			foreach($creditnames['creditnames'] as $index=>$creditname) {
				if($creditname['enabled'] == 0) {
					unset($creditnames['creditnames'][$index]);
				}
			}
			$select_credit = implode(', ', array_keys($creditnames['creditnames']));
		} else {
			$select_credit = '';
		}
		return $creditnames['creditnames'][$key]['title'];
	}
}