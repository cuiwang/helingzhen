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
	$data = pdo_fetchall("SELECT * FROM ".tablename($this->lottory_user_table)." WHERE weid=:weid AND rid=:rid AND lottory_id=:lottory_id ORDER BY id ASC",array(':weid'=>$weid,':rid'=>$rid,':lottory_id'=>$award_id));
	if(!empty($data) && is_array($data)){
		foreach($data as &$row){
			$row['bd_data'] = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$row['openid'])));
		}
		unset($row);
	}
	$result = array('result'=>0,'data'=>$data);
	die(json_encode($result));
}