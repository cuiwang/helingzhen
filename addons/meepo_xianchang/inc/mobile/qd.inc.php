<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/pc_init.php';
$qd_config = pdo_fetch("SELECT * FROM ".tablename($this->qd_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($qd_config)){
	message('请先配置签到');
}
$qds = pdo_fetchall("SELECT `nick_name`,`avatar`,`openid` FROM ".tablename($this->qd_table)." WHERE weid = :weid AND rid = :rid AND level = :level ORDER BY createtime ASC",array(':weid'=>$weid,':rid'=>$rid,':level'=>'1'));
if(!empty($qds) && is_array($qds)){
		foreach($qds as &$row){
			$row['bd_data'] = iunserializer(pdo_fetchcolumn("SELECT `data` FROM ".tablename($this->bd_data_table)." WHERE weid=:weid AND rid=:rid AND openid=:openid",array(':weid'=>$weid,':rid'=>$rid,':openid'=>$row['openid'])));
		}
		unset($row);
}
$qd_maxid = pdo_fetchcolumn("SELECT max(id) FROM ".tablename($this->qd_table)." WHERE weid = :weid AND rid = :rid AND level = :level",array(':weid'=>$weid,':rid'=>$rid,':level'=>'1'));
if(!$qd_maxid){
	$qd_maxid = 0;
}else{
	$qd_maxid = intval($qd_maxid);
}
$qd_nums = count($qds);
include $this->template('qd');