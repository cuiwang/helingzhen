<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Credit {
	//积分，微赞表
	public function getCreditByOpenid($openid = '',$uniacid=0) {
		global $_W;
		if(strpos($openid, "machine") == FALSE){
			load()->model('mc');
			$uid = self::mc_openidTouid($openid,$uniacid);
			$result = mc_fetch($uid, array('credit1'));
			return $result;
		}
	} 
	
	public function updateCredit1($openid = '',$uniacid=0,$credit1=0,$mess = '积分操作') {
		global $_W;
		if(strpos($openid, "machine") == FALSE){
			load()->model('mc');
			$uid = self::mc_openidTouid($openid,$uniacid);
			/*if($_W['member']['credit1'] < -$credit1 && $credit1 < 0){
				return -1;
			}*/
			mc_credit_update($uid, 'credit1', $credit1, array($uid, '【夺宝】'.$mess,'weliam_indiana'));
		}
		
	}
	
	public function updateCredit2($openid = '',$uniacid=0,$credit2=0 , $mess = '余额操作') {
		global $_W;
		if(strpos($openid, "machine") == FALSE){
			load()->model('mc');
			$uid = self::mc_openidTouid($openid,$uniacid);
			/*if($_W['member']['credit2'] < -$credit2 && $credit2 < 0){
				return -1;
			}*/
			mc_credit_update($uid, 'credit2', $credit2, array($uid, '【夺宝】'.$mess));
		}
		
	}
	
	function mc_openidTouid($openid='',$uniacid=0) {
		if (is_numeric($openid)) {
			return $openid;
		}
		if (is_string($openid)) {
			$sql = 'SELECT uid FROM ' . tablename('mc_mapping_fans') . ' WHERE `uniacid`=:uniacid AND `openid`=:openid';
			$pars = array();
			$pars[':uniacid'] = $uniacid;
			$pars[':openid'] = $openid;
			$uid = pdo_fetchcolumn($sql, $pars);
			return $uid;
		}
		if (is_array($openid)) {
			$uids = array();
			foreach ($openid as $k => $v) {
				if (is_numeric($v)) {
					$uids[] = $v;
				} elseif (is_string($v)) {
					$fans[] = $v;
				}
			}
			if (!empty($fans)) {
				$sql = 'SELECT uid, openid FROM ' . tablename('mc_mapping_fans') . " WHERE `uniacid`=:uniacid AND `openid` IN ('" . implode("','", $fans) . "')";
				$pars = array(':uniacid' => $uniacid);
				$fans = pdo_fetchall($sql, $pars, 'uid');
				$fans = array_keys($fans);
				$uids = array_merge((array)$uids, $fans);
			}
			return $uids;
		}
		return false;
	}
	
	function checkpay($tid = ''){
		global $_W;
		if(!empty($tid)){
			$pays = pdo_fetch('select * from'.tablename('core_paylog')."where tid = '{$tid}' and uniacid = '{$_W['uniacid']}' and module = 'weliam_indiana'");
			return $pays;
		}else{
			return $pays;
		}
	}
} 
