<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/pc_init.php';
$wall_config = pdo_fetch("SELECT * FROM ".tablename($this->wall_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($wall_config)){
	message('请先配置现场上墙');
}
if($wall_config['show_style']==1){
	$wall_config['show_style'] = 0;
}	
$small = 0;
include $this->template('dm');