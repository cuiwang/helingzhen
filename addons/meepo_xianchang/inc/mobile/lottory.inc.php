<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/pc_init.php';
$lottory_config = pdo_fetch("SELECT * FROM ".tablename($this->lottory_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($lottory_config['title'])){
	$lottory_config['title'] = '抽奖';
}
include $this->template('lottory');