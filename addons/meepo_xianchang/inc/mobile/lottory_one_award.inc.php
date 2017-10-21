<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_GPC, $_W;
$weid = $_W['uniacid'];
if($_W['isajax']){
	$rid = intval($_GPC['rid']);
	$award_id = intval($_GPC['award_id']);
	$data = pdo_fetch("SELECT * FROM ".tablename($this->lottory_award_table)." WHERE weid=:weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$award_id));
	$data['luck_img'] = tomedia($data['luck_img']);
	$result = array('result'=>0,'data'=>$data);
	die(json_encode($result));
}