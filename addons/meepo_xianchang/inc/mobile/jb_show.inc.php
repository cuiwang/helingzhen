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
	$jb_id = intval($_GPC['jb_id']);
	$data = pdo_fetch("SELECT * FROM ".tablename($this->jb_table)." WHERE weid=:weid AND rid=:rid AND id=:id",array(':weid'=>$weid,':rid'=>$rid,':id'=>$jb_id));
	if(!empty($data['tx'])){
		$data['tx'] = tomedia($data['tx']);
		$data['des'] = html_entity_decode($data['des']);
	}
	$result = array('result'=>0,'data'=>$data);
	die(json_encode($result));
}