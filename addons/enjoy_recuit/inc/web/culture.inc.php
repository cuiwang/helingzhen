<?php


global $_GPC;
global $_W;
$uniacid = $_W['uniacid'];
load()->func('tpl');
$item = pdo_fetch('select * from ' . tablename('enjoy_recuit_culture') . ' where uniacid=' . $uniacid . '');
$item['share_title'] = ((empty($item['share_title']) ? '{cname}-{pname}' : $item['share_title']));
$item['share_desc'] = ((empty($item['share_desc']) ? '这个职位很适合你，快来投递吧！' : $item['share_desc']));
$item['share_icon'] = ((empty($item['share_icon']) ? '../addons/enjoy_recuit/template/mobile/images/zhao.jpg' : $item['share_icon']));
$item['share_credit'] = ((empty($item['share_credit']) ? '0' : $item['share_credit']));

if (checksubmit('submit')) {
	$data = array('uniacid' => $uniacid, 'cname' => $_GPC['cname'], 'logo' => $_GPC['logo'], 'email' => $_GPC['email'], 'mobile' => $_GPC['mobile'], 'place' => $_GPC['place'], 'intro' => $_GPC['intro'], 'cact' => $_GPC['cact'], 'culture' => $_GPC['culture'], 'quest' => $_GPC['quest'], 'share_title' => trim($_GPC['share_title']), 'share_desc' => trim($_GPC['share_desc']), 'share_icon' => trim($_GPC['share_icon']), 'share_credit' => trim($_GPC['share_credit']), 'createtime' => TIMESTAMP);
	$exist = pdo_fetchcolumn('select count(*) from ' . tablename('enjoy_recuit_culture') . ' where uniacid=' . $uniacid . '');

	if (0 < $exist) {
		$res = pdo_update('enjoy_recuit_culture', $data, array('uniacid' => $uniacid));
		$message = '信息更新成功';
	}
	 else {
		$res = pdo_insert('enjoy_recuit_culture', $data);
		$message = '信息插入成功';
	}

	message($message, $this->createWebUrl('Culture'), 'success');
}


include $this->template('culture');
