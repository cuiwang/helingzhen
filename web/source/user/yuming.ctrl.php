<?php 
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '高级工具 - 版权设置';
$usergroup = pdo_get('users_group',array('id'=>$_W['user']['groupid']));
if(!$usergroup['domain']){message('抱歉，您不能使用此功能，请联系管理员开通',url('system/welcome',error));}
$setting = pdo_get('agent_copyright',array('uid'=>$_W['uid']));
$yuming   = $setting['yuming'];
if(checksubmit('submit')){
	
	if (!empty($_W['page']['copyright']['sitehost'])) {
		if (trim($_GPC['yuming']) == $_W['page']['copyright']['sitehost']) {
			message('请填写其他域名，不要填本平台域名！', referer(), 'error');
		}
	}
	$data =array('uid'=>$_W['uid'],'yuming'=>$_GPC['yuming']);
	if(empty($setting)){
			pdo_insert('agent_copyright',$data);
		}
		else{
			pdo_update('agent_copyright',$data);
		}
		message('域名设置成功！', url('user/yuming'));
}
template('user/yuming');
