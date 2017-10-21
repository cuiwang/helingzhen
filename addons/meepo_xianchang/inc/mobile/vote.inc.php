<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/pc_init.php';
$votes = pdo_fetchall("SELECT * FROM ".tablename($this->vote_table)." WHERE rid=:rid AND weid=:weid ORDER BY id ASC",array(':rid'=>$rid,':weid'=>$weid));
$vote_id = intval($_GPC['vote_id']);
if($vote_id){
	$vote = pdo_fetch("SELECT * FROM ".tablename($this->vote_table)." WHERE rid=:rid AND weid=:weid AND id=:id",array(':rid'=>$rid,':weid'=>$weid,':id'=>$vote_id));
	$vote_xms = pdo_fetchall("SELECT * FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND weid=:weid AND fid=:fid ORDER BY nums DESC,displayid   ASC",array(':rid'=>$rid,':weid'=>$weid,':fid'=>$vote_id));
}else{
	$vote = pdo_fetch("SELECT * FROM ".tablename($this->vote_table)." WHERE rid=:rid AND weid=:weid ORDER BY id ASC",array(':rid'=>$rid,':weid'=>$weid));
	$vote_id  = $vote['id'];
	$vote_xms = pdo_fetchall("SELECT * FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND weid=:weid AND fid=:fid ORDER BY nums DESC,displayid   ASC",array(':rid'=>$rid,':weid'=>$weid,':fid'=>$vote['id']));
}
$total =  pdo_fetchcolumn("SELECT SUM(nums) FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND weid=:weid AND fid=:fid",array(':rid'=>$rid,':weid'=>$weid,':fid'=>$vote['id']));
if(!empty($basic_config['bg_vedio'])){
	include $this->template('vote2');
}else{
	include $this->template('vote');
}
