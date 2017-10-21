<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
if($_W['isajax']){
	$where = '';
	$luck_user = pdo_fetchall("SELECT `openid`,`mobile` FROM ".tablename($this->xysjh_record_table)." WHERE rid=:rid AND weid=:weid ORDER BY createtime DESC",array(':rid'=>$rid,':weid'=>$weid));
	if(!empty($luck_user)){
		foreach($luck_user as $row){
			$real_user[] = $row['openid'];
		}
		 $where .= "AND openid NOT IN  ('".implode("','", $real_user)."')";
	}
	$need_bd = pdo_fetchcolumn("SELECT `show` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
	if($need_bd==0){
		$mobiles = pdo_fetchall("SELECT `mobile`,`openid` FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND  status=:status AND mobile!=:mobile {$where}",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':status'=>'1',':mobile'=>''));
	}else{
		$mobiles = pdo_fetchall("SELECT `mobile`,`openid` FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND can_lottory=:can_lottory AND status=:status AND mobile!=:mobile {$where}",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':can_lottory'=>'1',':status'=>'1',':mobile'=>''));
	}
	$data = array();
	$data['ret'] = 0;
	$data['data'] = $mobiles;
	$data['luck_data'] = $luck_user;
	die(json_encode($data));
}