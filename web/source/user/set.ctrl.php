<?php 
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('yuming', 'pifu', 'copyright');
$do = in_array($do, $dos) ? $do : 'copyright';
if ($do == 'copyright') {
$_W['page']['title'] = '高级工具 - 版权设置';
$usergroup = pdo_get('users_group',array('id'=>$_W['user']['groupid']));
if(!$usergroup['domain']){message('抱歉，您不能使用此功能，请联系管理员开通',url('system/welcome',error));}
$setting = pdo_get('agent_copyright',array('uid'=>$_W['uid']));
$settings = $setting ? iunserializer($setting['copyright']) : array();
	if (checksubmit('submit')) {
		$copyright = array(
			'smname' => $_GPC['smname'],
			'sitename' => $_GPC['sitename'],
			'url' => strexists($_GPC['url'], 'http://') ? $_GPC['url'] : "http://{$_GPC['url']}",
			'sitehost' => $_GPC['sitehost'],
			'statcode' => htmlspecialchars_decode($_GPC['statcode']),
			'footerleft' => htmlspecialchars_decode($_GPC['footerleft']),
			'footerright' => htmlspecialchars_decode($_GPC['footerright']),
			'icon' => $_GPC['icon'],
			'ewm' => $_GPC['ewm'],
			'flogo' => $_GPC['flogo'],
			'slides' => iserializer($_GPC['slides']),
			'notice' => $_GPC['notice'],
			'blogo' => $_GPC['blogo'],
			'baidumap' => $_GPC['baidumap'],
			'company' => $_GPC['company'],
			'address' => $_GPC['address'],
			'person' => $_GPC['person'],
			'phone' => $_GPC['phone'],
			'qq' => $_GPC['qq'],
			'email' => $_GPC['email'],
			'keywords' => $_GPC['keywords'],
			'description' => $_GPC['description'],
			'showhomepage' => intval($_GPC['showhomepage']),
		);
		$data = array('uid'=>$_W['uid'],'copyright'=>iserializer($copyright));
		if(empty($setting)){
			pdo_insert('agent_copyright',$data);
		}
		else{
			pdo_update('agent_copyright',$data);
		}
		message('更新设置成功！', url('user/set/copyright'));
	}
template('user/copyright');
}
if ($do == 'yuming') {
$_W['page']['title'] = '高级工具 - 域名设置';
$usergroup = pdo_get('users_group',array('id'=>$_W['user']['groupid']));

$setting = pdo_get('agent_copyright',array('uid'=>$_W['uid']));
$yuming   = $setting['yuming'];
if(checksubmit('submit')){
	
	if (!empty($_W['page']['copyright']['sitehost'])) {
		if (trim($_GPC['yuming']) == $_W['page']['copyright']['sitehost']) {
			message('请填写其他域名，不要填本平台域名！', referer(), 'error');
		}
	}
	$data =array('yuming'=>$_GPC['yuming']);
	if(empty($setting)){
			$data['uid']=$_W['uid'];
			pdo_insert('agent_copyright',$data);
		}
		else{
			pdo_update('agent_copyright',$data, array('uid'=>$_W['uid']));
		}
		message('域名设置成功！', url('user/set/yuming'));
}
template('user/yuming');
}
if ($do == 'pifu') {

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
		message('后台皮肤设置成功！', url('user/set/pifu'));
}
template('user/pifu');
}