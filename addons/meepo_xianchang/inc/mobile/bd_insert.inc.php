<?php
global $_W,$_GPC;
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
if($_W['isajax']){
	
	$openid = $_W['openid'];
	$check = pdo_fetchcolumn("SELECT `mobile` FROM ".tablename($this->user_table)." WHERE weid=:weid AND rid=:rid AND openid = :openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid));
	if($rid && !empty($openid) && empty($check)){
		$data = array();
		$data['weid'] = $weid;
		$data['rid'] = $rid;
		$data['openid'] = $openid;
		$bd_data = iunserializer(pdo_fetchcolumn("SELECT `xm` FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid)));
		$temp = array();
		if(!empty($bd_data) && is_array($bd_data)){
			foreach($bd_data as $row){
				if($row['zd_name']=='mobile'){
					$check_mobile = pdo_fetchcolumn("SELECT `id` FROM ".tablename($this->user_table)." WHERE weid=:weid AND rid=:rid AND mobile=:mobile",array(':weid'=>$weid,':rid'=>$rid,':mobile'=>$_GPC['mobile']));
					if($check_mobile){
							die(json_encode(error(-1,'该手机号已经被录入、请更换其他手机号')));
					}
				}
				$temp[$row['zd_name']] = $_GPC[$row['zd_name']];
			}
		}
		$data['data'] = iserializer($temp);
		pdo_update($this->user_table,array('mobile'=>$_GPC['mobile']),array('weid'=>$weid,'rid'=>$rid,'openid'=>$openid));
		$data_id = pdo_fetchcolumn("SELECT `id` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid = :openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid));
		if(empty($data_id)){
			pdo_insert($this->bd_data_table,$data);
		}else{
			pdo_update($this->bd_data_table,$data,array('weid'=>$weid,'rid'=>$rid,'id'=>$data_id));
		}
		die(json_encode(error(0,'success')));
	}else{
		die(json_encode(error(-1,'录入失败啦')));
	}
}