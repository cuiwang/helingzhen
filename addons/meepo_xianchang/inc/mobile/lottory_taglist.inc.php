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
	$data = pdo_fetchall("SELECT * FROM ".tablename($this->lottory_award_table)." WHERE weid=:weid AND rid=:rid AND type=:type ORDER BY displayid ASC",array(':weid'=>$weid,':rid'=>$rid,':type'=>'0'));
	$result = array('result'=>0,'data'=>$data);
	die(json_encode($result));
}