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
	$luck_user = pdo_fetchall("SELECT `user_id` FROM ".tablename($this->lottory_user_table)." WHERE rid=:rid AND weid=:weid",array(':rid'=>$rid,':weid'=>$weid));
	if(!empty($luck_user)){
		foreach($luck_user as $row){
			$real_user[] = $row['user_id'];
		}
		 $where .= "AND id NOT IN  ('".implode("','", $real_user)."')";
	}
	$need_bd = pdo_fetchcolumn("SELECT `show` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
	if($need_bd==0){
		$lottory_type = pdo_fetchcolumn("SELECT `lottory_type` FROM ".tablename($this->lottory_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
		if($lottory_type==0){
			$user = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND can_lottory=:can_lottory AND status=:status {$where} ORDER BY nd_id DESC",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':can_lottory'=>'1',':status'=>'1'));
		}else{
			$user = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND can_lottory=:can_lottory AND status=:status AND qd_status=:qd_status {$where} ORDER BY nd_id DESC",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':can_lottory'=>'1',':status'=>'1',':qd_status'=>'1'));
		}
	}else{
		$lottory_type = pdo_fetchcolumn("SELECT `lottory_type` FROM ".tablename($this->lottory_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
		if($lottory_type==1){
			$user = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND can_lottory=:can_lottory AND status=:status AND mobile!=:mobile  AND qd_status=:qd_status {$where} ORDER BY nd_id DESC",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':can_lottory'=>'1',':status'=>'1',':mobile'=>'',':qd_status'=>'1'));
		}else{
			$user = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND can_lottory=:can_lottory AND status=:status AND mobile!=:mobile {$where} ORDER BY nd_id DESC",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':can_lottory'=>'1',':status'=>'1',':mobile'=>''));
		}
	}
	if(!empty($user) && is_array($user) && $need_bd==1){
		foreach($user as &$val){
			$val['bd_data'] = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$val['openid'])));
		}
		unset($val);
	}
	$data = array();
	$data['ret'] = 0;
	$data['data'] = $user;
	die(json_encode($data));
}