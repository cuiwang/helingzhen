<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/__init.php';
if(!in_array('vote',$xianchang['controls'])){
	message('本次活动未开启投票活动！');
}
if(empty($user)){
	message('错误你的信息不存在或是已经被删除！');
}
$votes = pdo_fetchall("SELECT * FROM ".tablename($this->vote_table)." WHERE rid=:rid AND weid=:weid ORDER BY id ASC",array(':rid'=>$rid,':weid'=>$weid));
$vote_id = intval($_GPC['vote_id']);

if($vote_id){
	$vote = pdo_fetch("SELECT * FROM ".tablename($this->vote_table)." WHERE rid=:rid AND weid=:weid AND			id=:id",array(':rid'=>$rid,':weid'=>$weid,':id'=>$vote_id));
	$vote_xms = pdo_fetchall("SELECT * FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND weid=:weid AND fid=:fid ORDER BY nums DESC,displayid   ASC",array(':rid'=>$rid,':weid'=>$weid,':fid'=>$vote_id));
}else{
	$vote = pdo_fetch("SELECT * FROM ".tablename($this->vote_table)." WHERE rid=:rid AND weid=:weid AND status!=:status ORDER BY id ASC",array(':rid'=>$rid,':weid'=>$weid,':status'=>'3'));
	$vote_id  = $vote['id'];
	$vote_xms = pdo_fetchall("SELECT * FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND weid=:weid AND fid=:fid ORDER BY nums DESC,displayid   ASC",array(':rid'=>$rid,':weid'=>$weid,':fid'=>$vote['id']));
}
if(empty($vote)){
	message('本次活动投票项目已经全部结束、请到大屏幕观看投票结果');
}
$total =  pdo_fetchcolumn("SELECT SUM(nums) FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND weid=:weid AND fid=:fid",array(':rid'=>$rid,':weid'=>$weid,':fid'=>$vote['id']));
if(!$vote['vote_nums']){
	$vote['vote_nums'] = 1;
}
$check_voted = pdo_fetchall("SELECT `vote_xm_id` FROM ".tablename($this->vote_record)." WHERE weid=:weid AND rid=:rid AND openid=:openid AND fid=:fid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid,':fid'=>$vote_id));
$check_voted_id = array();
if(!empty($check_voted)){
	foreach($check_voted as $row){
		$check_voted_id[] = $row['vote_xm_id'];
	}
}

include $this->template('app_vote');