<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('display');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

if ($op == 'display') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'weid' => $_W['uniacid'],
			'location_p' => $_GPC['district']['province'],
			'location_c' => $_GPC['district']['city'],
			'location_a' => $_GPC['district']['district'],
			'version' => $_GPC['version'],
			'user' => $_GPC['user'],
			'reg' => $_GPC['reg'],
			'regcontent' => $_GPC['regcontent'],
			'bind' => $_GPC['bind'],
			'ordertype' => $_GPC['ordertype'],
			'paytype1' => $_GPC['paytype1'],
			'paytype2' => $_GPC['paytype2'],
			'paytype3' => $_GPC['paytype3'],
			'is_unify' => $_GPC['is_unify'],
			'tel' => $_GPC['tel'],
			'refund' => intval($_GPC['refund']),
			'email' => $_GPC['email'],
			'mobile' => $_GPC['mobile'],
			'template' => $_GPC['template'],
			'smscode' => $_GPC['smscode'],
			'templateid' => trim($_GPC['templateid']),
			'refuse_templateid' => trim($_GPC['refuse_templateid']),
			'confirm_templateid' => trim($_GPC['confirm_templateid']),
			'check_in_templateid' => trim($_GPC['check_in_templateid']),
			'finish_templateid' => trim($_GPC['finish_templateid']),
			'nickname' => trim($_GPC['nickname']),
		);
		if ($data['template'] && $data['templateid'] == '') {
			message('请输入模板ID', referer(), 'info');
		}
		//检查填写的昵称是否是关注了该公众号的用户
		if (!empty($data['nickname'])) {
			$from_user = pdo_get('mc_mapping_fans', array('nickname' => $data['nickname'], 'uniacid' => $_W['uniacid']));
			if (empty($from_user)){
				message('输入的昵称错误或没有关注该公众号，请重新输入！');
			}
		}
		if (!empty($id)) {
			pdo_update('storex_set', $data, array('id' => $id));
		} else {
			pdo_insert('storex_set', $data);
		}
		$cachekey = "wn_storex_set:{$_W['uniacid']}";
		cache_delete($cachekey);
		$set = get_storex_set();
		message('保存设置成功!', referer(), 'success');
	}
	$set = get_storex_set();
	include $this->template('hotelset');
}