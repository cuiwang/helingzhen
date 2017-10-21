<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/__init.php';
if(!in_array('qd',$xianchang['controls'])){
	message('本次活动未开启签到活动！');
}
if(empty($user)){
	message('错误你的信息不存在或是已经被删除！');
}
$qd_config = pdo_fetch("SELECT * FROM ".tablename($this->qd_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
$qd = pdo_fetch("SELECT * FROM ".tablename($this->qd_table)." WHERE weid = :weid AND rid = :rid AND openid = :openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$openid));
$level = $qd['level'];
if(empty($qd)){
	if($qd_config['status']=='2'){
		$level = 2;
	}else{
		$level = 1;
	}
	$data = array('weid'=>$weid,'rid'=>$rid,'nick_name'=>$user['nick_name'],'openid'=>$openid,'avatar'=>$user['avatar'],'level'=>$level,'createtime'=>time());
	$insert_qd = pdo_insert($this->qd_table,$data);
	if(!empty($insert_qd)){
		$qd_id = pdo_insertid();
		$qd = pdo_fetch("SELECT * FROM ".tablename($this->qd_table)." WHERE weid = :weid  AND id = :id",array(':weid'=>$weid,':id'=>$qd_id));
		if($level == 1){
			pdo_update($this->user_table,array('qd_status'=>'1'),array('rid'=>$rid,'openid'=>$openid));
		}
	}else{
		message('签到失败了');
	}
}
$qdnums = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->qd_table)." WHERE weid = :weid AND rid = :rid AND level = :level AND id < :id",array(':weid'=>$weid,':rid'=>$rid,':level'=>'1',':id'=>$qd['id']));
$qdnums = intval($qdnums) + 1;
include $this->template('app_qd');

