<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/pc_init.php';
$shake_info = $shake_config = pdo_fetch("SELECT * FROM ".tablename($this->shake_config_table)." WHERE weid=:weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($shake_config)){
	message('请先配置现场摇一摇');
}else{
	$shake_info['user_1'] = tomedia($shake_info['user_1']);
	$shake_info['user_2'] = tomedia($shake_info['user_2']);
	$shake_info['user_3'] = tomedia($shake_info['user_3']);
	$shake_info['user_4'] = tomedia($shake_info['user_4']);
	$shake_info['user_5'] = tomedia($shake_info['user_5']);
	$shake_info['user_6'] = tomedia($shake_info['user_6']);
	$shake_info['user_7'] = tomedia($shake_info['user_7']);
	$shake_info['user_8'] = tomedia($shake_info['user_8']);
	$shake_info['user_9'] = tomedia($shake_info['user_9']);
	$shake_info['user_10'] = tomedia($shake_info['user_10']);
}
$shake_info['slogan_list'] = explode('#',$shake_info['slogan']);
$shake_info['rotate_list'] = pdo_fetchall("SELECT * FROM ".tablename($this->shake_rotate_table)." WHERE weid=:weid AND rid=:rid  ORDER BY id ASC  ",array(':weid'=>$weid,':rid'=>$rid));
$socket_url = $this->module['config']['socket_url'];
if(empty($socket_url)){
	message('请先设置参数设置里的SOCKET地址');
}
include $this->template('shake');