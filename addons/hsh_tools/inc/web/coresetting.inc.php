<?php

global $_W, $_GPC;
load()->func('tpl');

load()->model("setting");
$setting = setting_load();
$copyRight = $setting['copyright'];
$close = $setting['close'];
if (checksubmit('submit')) {
	$copyRightReceive = array(
		'sitename' => $_GPC['sitename'],
		'url' => $_GPC['url'],
		'statcode' => htmlspecialchars_decode($_GPC['statcode']),
		'footerleft' => htmlspecialchars_decode($_GPC['footerleft']),
		'footerright' => htmlspecialchars_decode($_GPC['footerright']),
		'icon' => $_GPC['icon'],
		'flogo' => $_GPC['flogo'],
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
		'showhomepage' => $_GPC['showhomepage'],
	);
	$closeReceive = array(
		'status' => $_GPC['status'],
		'reason' => $_GPC['reason'],
	);
	setting_save($copyRightReceive, "copyright");
	setting_save($closeReceive, "close");
	message('更新设置成功！', "", 'success');
}

include $this->template('coresetting');