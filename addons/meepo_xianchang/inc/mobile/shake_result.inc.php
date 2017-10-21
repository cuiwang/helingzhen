<?php
global $_W,$_GPC;
$rotate_id = intval($_GPC['rotate_id']);
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
$maxsize = pdo_fetchcolumn("SELECT `maxsize` FROM ".tablename($this->shake_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
$maxsize = empty($maxsize)?20:intval($maxsize);
$rounds = pdo_fetchall("SELECT * FROM ".tablename($this->shake_rotate_table)." WHERE weid=:weid AND rid=:rid ORDER BY id ASC",array(':weid'=>$weid,':rid'=>$rid));
if(!empty($rotate_id) && $rid){
	$data = pdo_fetchall("SELECT * FROM ".tablename($this->shake_user_table)." WHERE rid = :rid AND weid = :weid AND rotate_id = :rotate_id ORDER BY displayorder ASC  LIMIT 0,".$maxsize,array(':rid'=>$rid,':weid'=>$weid,':rotate_id'=>$rotate_id));
	if(!empty($data) && is_array($data)){
		foreach($data as &$val){
			$val['bd_data'] = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$val['openid'])));
		}
		unset($val);
	}
}else{
	$rotate_id = pdo_fetchcolumn("SELECT * FROM ".tablename($this->shake_rotate_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
	$data = pdo_fetchall("SELECT * FROM ".tablename($this->shake_user_table)." WHERE rid = :rid AND weid = :weid AND rotate_id = :rotate_id   ORDER BY displayorder ASC LIMIT 0,".$maxsize,array(':rid'=>$rid,':weid'=>$weid,':rotate_id'=>$rotate_id));
	if(!empty($data) && is_array($data)){
		foreach($data as &$val){
			$val['bd_data'] = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$val['openid'])));
		}
		unset($val);
	}
}
$bd_config = pdo_fetch("SELECT * FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
$lottory_show = array();
if(!empty($bd_config) && $bd_config['show']==1){
	$bd_xm = iunserializer($bd_config['xm']);
	if(!empty($bd_xm) && is_array($bd_xm)){
		foreach($bd_xm as $row){
			if($row['zd_show']==1){
				$lottory_show[] = $row['zd_name'];
			}
		}
		if(!empty($lottory_show)){
			$bd_config['show'] = 1;
		}else{
			$bd_config['show'] = 0;
		}
	}else{
		$bd_config['show'] = 0;
	}
}else{
	$bd_config['show'] = 0;
}
include $this->template('shake_result');