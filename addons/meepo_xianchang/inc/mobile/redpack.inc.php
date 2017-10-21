<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/pc_init.php';
$redpack = pdo_fetch("SELECT * FROM ".tablename($this->redpack_rotate_table)." WHERE weid = :weid AND rid=:rid AND status!=:status ORDER BY id ASC LIMIT 1",array(':weid'=>$weid,':rid'=>$rid,':status'=>'3'));
if(empty($redpack)){
	message('抢红包活动已经全部结束啦');
}
$redpack_config = pdo_fetch("SELECT * FROM ".tablename($this->redpack_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($redpack_config)){
	message('请先配置红包参数');
}
$count_redpack = intval(pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->redpack_rotate_table)." WHERE weid = :weid AND rid=:rid AND status!=:status ORDER BY id ASC LIMIT 1",array(':weid'=>$weid,':rid'=>$rid,':status'=>'3')));
include $this->template('redpack');