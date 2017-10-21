<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/pc_init.php';
$ddp_config = pdo_fetch("SELECT * FROM ".tablename($this->ddp_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($ddp_config)){
	$ddp_config['ddp_type'] = 1;
}
$real_man_user = array();
$manwhere = '';
$luck_man_user = pdo_fetchall("SELECT `openid`,	`avatar`, `toavatar`,`nick_name`,`tonick_name` FROM ".tablename($this->ddp_record_table)." WHERE rid=:rid AND weid=:weid ORDER BY createtime DESC",array(':rid'=>$rid,':weid'=>$weid));
if(!empty($luck_man_user)){
	foreach($luck_man_user as $row){
		$real_man_user[] = $row['openid'];
	}
	 $manwhere .= "AND openid NOT IN  ('".implode("','", $real_man_user)."')";
}
$real_woman_user = array();
$womanwhere = '';
$luck_woman_user = pdo_fetchall("SELECT `toopenid`  FROM ".tablename($this->ddp_record_table)." WHERE rid=:rid AND weid=:weid ORDER BY createtime DESC",array(':rid'=>$rid,':weid'=>$weid));
if(!empty($luck_woman_user)){
	foreach($luck_woman_user as $row){
		$real_woman_user[] = $row['toopenid'];
	}
	 $womanwhere .= "AND openid NOT IN  ('".implode("','", $real_woman_user)."')";
}
$man = array();
$woman =  array();
if($ddp_config['ddp_type']==1){
	
	$man  = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND sex=:sex AND status=:status {$manwhere} ",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':sex'=>'1',':status'=>'1'));
	$woman  = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND sex=:sex AND status=:status {$womanwhere}",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':sex'=>'2',':status'=>'1'));
}else{
	$total_persons  = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND status=:status {$manwhere} {$womanwhere}",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':status'=>'1'));
	if($total_persons>0){
		$man_nums = round($total_persons/2);
		$man  = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND status=:status {$manwhere} {$womanwhere} ORDER BY RAND() LIMIT 0,".$man_nums,array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':status'=>'1'));
		if(!empty($man) && is_array($man)){
			$woman_ids = array();  
			$woman_ids = array_map('array_shift', $man); 
			$woman_where = " AND id NOT IN  ('".implode("','", $woman_ids)."')";
			$woman  = pdo_fetchall("SELECT * FROM ".tablename($this->user_table)." WHERE rid=:rid AND weid = :weid AND isblacklist=:isblacklist AND status=:status {$manwhere} {$womanwhere} {$woman_where}",array(':rid'=>$rid,':weid'=>$weid,':isblacklist'=>'1',':status'=>'1'));
		}
	}
}
include $this->template('ddp');