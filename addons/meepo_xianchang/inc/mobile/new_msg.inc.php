<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rid = intval($_GPC['rid']);
	$last_id = intval($_GPC['last_id']);//old max_id
	if($last_id==0){
		$chisotry = pdo_fetchcolumn("SELECT `chistory` FROM ".tablename($this->wall_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
		if($chisotry==0){
			$wall = pdo_fetchall("SELECT * FROM ".tablename($this->wall_table)." WHERE weid = :weid AND rid = :rid AND status = :status ORDER BY createtime DESC",array(':weid'=>$weid,':rid'=>$rid,':status'=>'1'));
		}else{
			$wall = pdo_fetchall("SELECT * FROM ".tablename($this->wall_table)." WHERE weid = :weid AND rid = :rid AND status = :status ORDER BY createtime DESC LIMIT 0,{$chisotry}",array(':weid'=>$weid,':rid'=>$rid,':status'=>'1'));
		}
		$data = array();
	}else{
		$wall = pdo_fetchall("SELECT * FROM ".tablename($this->wall_table)." WHERE weid = :weid AND rid = :rid AND status = :status AND id > :id ORDER BY createtime DESC",array(':weid'=>$weid,':rid'=>$rid,':status'=>'1',':id'=>$last_id));
		
	}
	$show_time = pdo_fetchcolumn("SELECT `show_time` FROM ".tablename($this->wall_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
	if(!empty($wall)){
		foreach($wall as &$row){
			if($show_time){
				$row['nick_name'] = $row['nick_name'].'&nbsp;&nbsp;&nbsp;&nbsp;('.date('m-d H:i:s',$row['createtime']).')';
			}
			$row['bd_data'] = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$row['openid'])));
			$row['content'] = $this->emo($row['content'],'pc');
		}
		unset($row);
	}
	$data = array();
	$data['ret'] = 0;
	$data['data'] = $wall;
	die(json_encode($data));
}