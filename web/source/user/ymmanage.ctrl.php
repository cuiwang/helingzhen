<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('display', 'post', 'del', 'batch_post');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';

if ($do == 'display') {
	$condition = '' ;
	$params = array();
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	if (!empty($_GPC['yuming'])) {
		$condition .= "WHERE yuming LIKE :yuming";
		$params[':yuming'] = "%{$_GPC['yuming']}%";
	}
	$sql = 'SELECT * FROM ' . tablename('agent_copyright') . $condition . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$lists = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('agent_copyright') . $condition, $params);
	$pager = pagination($total, $pindex, $psize);
	template('user/ymmanage');
}

if ($do == 'del') {
	$id = intval($_GPC['id']);
	$result = pdo_delete('agent_copyright', array('id' => $id));
	if(!empty($result)){
		itoast('删除成功！', url('user/ymmanage/display'), 'success');
	}else {
		itoast('删除失败！请稍候重试！', url('user/ymmanage'), 'error');
	}
	exit;
}
if($do == 'batch_post') {
	if(checksubmit()) {
		if(!empty($_GPC['ids'])) {
			foreach($_GPC['ids'] as $k => $v) {
				$data = array(
					'yuming' => trim($_GPC['yuming'][$k]),
				);
				pdo_update('agent_copyright', $data, array('id' => intval($v)));
			}
			message('域名列表成功', referer(), 'success');
		}
	}
}