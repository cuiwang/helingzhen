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
	$man_id = intval($_GPC['man_id']);
	$women_id = intval($_GPC['women_id']);
	$sql = "SELECT `nick_name`,`avatar`,`openid` FROM ".tablename($this->user_table)." WHERE id = :id AND rid = :rid  AND weid=:weid";
	$param = array(':id' =>$man_id, ':rid' => $rid,':weid' =>$weid);
	$man = pdo_fetch($sql,$param);
	if(empty($man)){
		$result = error(0,'error');
		die(json_encode($result));
	}
	$sql = "SELECT `nick_name`,`avatar`,`openid` FROM ".tablename($this->user_table)." WHERE id = :id AND rid = :rid  AND weid=:weid";
	$param = array(':id' =>$women_id, ':rid' => $rid,':weid' =>$weid);
	$woman = pdo_fetch($sql,$param);
	if(empty($woman)){
		$result = error(0,'error');
		die(json_encode($result));
	}
	$insert = array();
	$insert['weid'] = $weid;
	$insert['rid'] = $rid;
	$insert['openid'] = $man['openid'];
	$insert['nick_name'] = $man['nick_name'];
	$insert['avatar'] = $man['avatar'];
	$insert['toopenid'] = $woman['openid'];
	$insert['tonick_name'] = $woman['nick_name'];
	$insert['toavatar'] = $woman['avatar'];
	$insert['createtime'] = time();
	$insert_result = pdo_insert($this->ddp_record_table ,$insert);
	if(!empty($insert_result)){
		//$insert_id = pdo_insertid();
		$result = error(0,'success');
	}else{
		$result = error(0,'error');
	}
	die(json_encode($result));
}