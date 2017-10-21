<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$data = array();
if($_W['isajax']){
	$rotate_id = intval($_GPC['rotate_id']);
	$list = pdo_fetchall("SELECT `id`,`avatar`,`nick_name`,`money`,`openid` FROM ".tablename($this->redpack_user_table)." WHERE weid = :weid AND rid=:rid AND money!=:money AND rotate_id =:rotate_id ORDER BY id ASC",array(':weid'=>$weid,':rid'=>$rid,':money'=>'0.0',':rotate_id'=>$rotate_id));
	if(!empty($list) && is_array($list)){
		foreach($list as &$val){
			$val['bd_data'] = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$val['openid'])));
		}
		unset($val);
	}
	$data = array('list'=>$list);
	die(json_encode($data));
}