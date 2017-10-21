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
	$openid = $_GPC['openid'];
	$mobile = $_GPC['mobile'];
	$sql = "SELECT `nick_name`,`avatar` FROM ".tablename($this->user_table)." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
	$param = array(':openid' =>$openid, ':rid' => $rid,':weid' => $weid);
	$user = pdo_fetch($sql,$param);
	$insert = array();
	$insert['weid'] = $weid;
	$insert['rid'] = $rid;
	$insert['openid'] = $openid;
	$insert['nick_name'] = $user['nick_name'];
	$insert['avatar'] = $user['avatar'];
	$insert['mobile'] = $mobile;
	$insert['createtime'] = time();
	$insert_result = pdo_insert($this->xysjh_record_table ,$insert);
	if(!empty($insert_result)){
		$insert_id = pdo_insertid();
		$result = array('result'=>0,'data'=>$insert_id);
	}else{
		$result = array('result'=>-1,'data'=>-1);
	}
	die(json_encode($result));
}