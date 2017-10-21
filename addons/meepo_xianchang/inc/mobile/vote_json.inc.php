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
	$vote_id = intval($_GPC['vote_id']);
	$vote_xms = pdo_fetchall("SELECT * FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND weid=:weid AND fid=:fid ORDER BY nums ASC ,displayid ASC",array(':rid'=>$rid,':weid'=>$weid,':fid'=>$vote_id));
	$total =  pdo_fetchcolumn("SELECT SUM(nums) FROM ".tablename($this->vote_xms_table)." WHERE rid=:rid AND weid=:weid AND fid=:fid",array(':rid'=>$rid,':weid'=>$weid,':fid'=>$vote_id));
	$data = array();
	if(!empty($vote_xms) && is_array($vote_xms)){
		foreach($vote_xms as $row){
			$row['per'] = sprintf("%.2f", ($row['nums']/$total)*100);
			$data[] = $row;
		}
	}
	die(json_encode($data));
	
}