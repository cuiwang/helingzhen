<?php 
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '高级工具 - 版权设置';
$usergroup = pdo_get('users_group',array('id'=>$_W['user']['groupid']));
if(!$usergroup['domain']){message('抱歉，您不能使用此功能，请联系管理员开通',url('system/welcome',error));}
$path = IA_ROOT . '/web/themes/';
if(is_dir($path)) {
	if ($handle = opendir($path)) {
		while (false !== ($templatepath = readdir($handle))) {
			if ($templatepath != '.' && $templatepath != '..') {
				if(is_dir($path.$templatepath)){
					$template[] = $templatepath;
				}
			}
		}
	}
}
$setting = pdo_get('agent_copyright',array('uid'=>$_W['uid']));
$pifu   = $setting['pifu'];
if(checksubmit('submit')){
	$data =array('uid'=>$_W['uid'],'pifu'=>$_GPC['pifu']);
	if(empty($setting)){
			pdo_insert('agent_copyright',$data);
		}
		else{
			pdo_update('agent_copyright',$data);
		}
		message('后台皮肤设置成功！', url('user/pifu'));
}
template('user/pifu');
